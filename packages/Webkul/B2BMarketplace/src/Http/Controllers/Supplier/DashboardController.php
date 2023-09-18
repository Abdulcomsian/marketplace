<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\OrderItemRepository as OrderItem;
use Webkul\Customer\Repositories\CustomerRepository as Customer;
use Webkul\B2BMarketplace\Repositories\OrderRepository as MpOrder;
use Webkul\Product\Repositories\ProductInventoryRepository as ProductInventory;


/**
* Dashboard Controller
*
* @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
*/
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var object
     */
    protected $order;

    /**
    * MpOrder object
    *
    * @var object
    */
    protected $MpOrder;

    /**
     * OrderItemRepository object
     *
     * @var object
     */
    protected $orderItem;

    /**
     * ProductInventoryRepository object
     *
     * @var object
     */
    protected $productInventory;

    /**
     * CustomerRepository object
     *
     * @var object
     */
    protected $customer;

    /**
     * SupplierRepository object
     *
     * @var object
    */
    protected $supplier;

    /**
     * string object
     *
     * @var object
     */
    protected $startDate;

    /**
     * string object
     *
     * @var object
     */
    protected $lastStartDate;

    /**
     * string object
     *
     * @var array
     */
    protected $endDate;

    /**
     * string object
     *
     * @var object
     */
    protected $lastEndDate;

    /**
     * Create a new Repository instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository        $customer
     * @param  \Webkul\Sales\Repositories\OrderRepository              $order
     * @param  \Webkul\Sales\Repositories\OrderItemRepository          $orderItem
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository $productInventory
     * @return void
     */
    public function __construct(
        Customer $customer,
        Order $order,
        OrderItem $orderItem,
        ProductInventory $productInventory,
        MpOrder $mpOrder
    )
    {
        $this->middleware('supplier');

        $this->order = $order;

        $this->mpOrder = $mpOrder;

        $this->orderItem = $orderItem;

        $this->customer = $customer;

        $this->productInventory = $productInventory;

        $this->_config = request('_config');
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $this->setStartEndDate();

        $this->supplier = auth()->guard('supplier')->user();

        $statistics = [
            'total_customers' => [
                'previous' => $previous = $this->getCustomersBetweenDates($this->lastStartDate, $this->lastEndDate)->count(),
                'current' => $current = $this->getCustomersBetweenDates($this->startDate, $this->endDate)->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],
            'total_orders' => [
                'previous' => $previous = $this->previousOrders()->count(),
                'current' => $current = $this->currentOrders()->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],

            'total_sales' => [
                'previous' => $previous = $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->lastStartDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->lastEndDate);
                })->sum('base_supplier_total'),
                'current' => $current = $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->startDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->endDate);
                })->sum('base_supplier_total') - $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->startDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->endDate);
                })->sum('base_grand_total_refunded') + $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->startDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->endDate);
                })->sum('base_commission_invoiced'),
                'progress' => $this->getPercentageChange($previous, $current)
            ],
            'avg_sales' => [
                'previous' => $previous = $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->lastStartDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->lastEndDate);
                })->avg('base_supplier_total'),
                'current' => $current = $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->startDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->endDate);
                })->avg('base_supplier_total') - $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->startDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->endDate);
                })->avg('base_grand_total_refunded') + $this->mpOrder->scopeQuery(function($query) {
                    return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                        ->where('b2b_marketplace_orders.created_at', '>=', $this->startDate)
                        ->where('b2b_marketplace_orders.created_at', '<=', $this->endDate);
                })->avg('base_commission_invoiced'),
                'progress' => $this->getPercentageChange($previous, $current)
            ],

            'top_selling_categories' => $this->getTopSellingCategories(),
            'top_selling_products' => $this->getTopSellingProducts(),
            'customer_with_most_sales' => $this->getCustomerWithMostSales(),
            'stock_threshold' => $this->getStockThreshold(),

            'pending_orders' => [
                'pending' => $this->ordersDetails('pending')->count(),
                'previous' => $previous = $this->previousOrdersDetails('pending')->count(),
                'current' => $current = $this->currentOrdersDetails('pending')->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],

            'processing_orders' => [
                'processing' => $this->ordersDetails('processing')->count(),
                'previous' => $previous = $this->previousOrdersDetails('processing')->count(),
                'current' => $current = $this->currentOrdersDetails('processing')->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],

            'completed_orders' => [
                'completed' => $this->ordersDetails('completed')->count(),
                'previous' => $previous = $this->previousOrdersDetails('completed')->count(),
                'current' => $current = $this->currentOrdersDetails('completed')->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],

            'canceled_orders' => [
                'canceled' => $this->ordersDetails('canceled')->count(),
                'previous' => $previous = $this->previousOrdersDetails('canceled')->count(),
                'current' => $current = $this->currentOrdersDetails('canceled')->count(),
                'progress' => $this->getPercentageChange($previous, $current)
            ],

        ];

        foreach (core()->getTimeInterval($this->startDate, $this->endDate) as $interval) {
            $statistics['sale_graph']['label'][] = $interval['start']->format('d M');

            $total = $this->mpOrder->scopeQuery(function($query) use($interval) {
                return $query->where('b2b_marketplace_orders.supplier_id', $this->supplier->id)
                    ->where('b2b_marketplace_orders.created_at', '>=', $interval['start'])
                    ->where('b2b_marketplace_orders.created_at', '<=', $interval['end']);
            })->sum('base_supplier_total');

            $newTotal = core()->convertPrice($total);

            $statistics['sale_graph']['total'][] = $total;
            $statistics['sale_graph']['formated_total'][] = core()->formatBasePrice($total);
        }

        return view($this->_config['view'], compact('statistics'))->with(['startDate' => $this->startDate, 'endDate' => $this->endDate]);
    }
    /**
     * Sets start and end date
     *
     * @return object
     */
    public function setStartEndDate()
    {
        $this->startDate = request()->get('start')
            ? Carbon::createFromTimeString(request()->get('start') . " 00:00:01")
            : Carbon::createFromTimeString(Carbon::now()->subDays(30)->format('Y-m-d') . " 00:00:01");

        $this->endDate = request()->get('end')
            ? Carbon::createFromTimeString(request()->get('end') . " 23:59:59")
            : Carbon::now();

        if ($this->endDate > Carbon::now())
            $this->endDate = Carbon::now();

        $this->lastStartDate = clone $this->startDate;
        $this->lastEndDate = clone $this->startDate;

        $this->lastStartDate->subDays($this->startDate->diffInDays($this->endDate));
    }

    /**
     * get the customer btw dates
     *
     * @return object
     */
    private function getCustomersBetweenDates($start, $end)
    {
        return $this->customer->scopeQuery(function ($query) use ($start, $end) {

            $supplier = auth()->guard('supplier')->user()->id;

            return $query
            ->addSelect('customers.*')
            ->leftJoin('orders', 'orders.customer_id', 'customers.id')
            ->leftJoin('b2b_marketplace_orders', 'orders.id', 'b2b_marketplace_orders.order_id')
            ->where('b2b_marketplace_orders.supplier_id', $supplier)
            ->groupBy('customers.id')
            ->where('customers.created_at', '>=', $start)->where('customers.created_at', '<=', $end)
            ->get();
        });
    }

    /**
     * get the Percentage
     *
     * @return object
     */
    public function getPercentageChange($previous, $current)
    {
        if (! $previous)
            return $current ? 100 : 0;

        return ($current - $previous) / $previous * 100;
    }

    /**
     * get the previous orders
     *
     * @return object
     */
    private function previousOrders()
    {
        return $this->getOrdersBetweenDate($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * get the orders between date
     *
     * @return object
     */
    private function getOrdersBetweenDate($start, $end)
    {
        return $this->order->scopeQuery(function ($query) use ($start, $end) {

            $supplier = auth()->guard('supplier')->user()->id;

            return $query
                ->leftJoin('b2b_marketplace_orders', 'orders.id', 'b2b_marketplace_orders.order_id')
                ->where('orders.created_at', '>=', $start)->where('orders.created_at', '<=', $end)
                ->where('b2b_marketplace_orders.supplier_id', $supplier)
                ->where('orders.status', '<>', 'canceled')->get();
        });
    }

    /**
     * get the current orders
     *
     * @return object
     */
    private function currentOrders()
    {
        return $this->getOrdersBetweenDate($this->startDate, $this->endDate);
    }

    /**
     * get the orders details
     *
     * @return object
     */
    private function ordersDetails($status)
    {
        return $this->order->scopeQuery(function ($query) use ($status) {

            $supplier = auth()->guard('supplier')->user()->id;

            return $query
                ->leftJoin('b2b_marketplace_orders', 'orders.id', 'b2b_marketplace_orders.order_id')
                ->where('b2b_marketplace_orders.supplier_id', $supplier)
                ->where('orders.status', '=', $status)->get();
        });
    }

    /**
     * get the previous orders Details
     *
     * @return object
     */
    private function previousOrdersDetails($status)
    {
        return $this->getOrdersDetailsBetweenDate($status, $this->lastStartDate, $this->lastEndDate);
    }

    /**
     * get the orders Details between date
     *
     * @return object
     */
    private function getOrdersDetailsBetweenDate($status, $start, $end)
    {
        return $this->order->scopeQuery(function ($query) use ($status, $start, $end) {

            $supplier = auth()->guard('supplier')->user()->id;

            return $query
                ->leftJoin('b2b_marketplace_orders', 'orders.id', 'b2b_marketplace_orders.order_id')
                ->where('orders.created_at', '>=', $start)->where('orders.created_at', '<=', $end)
                ->where('b2b_marketplace_orders.supplier_id', $supplier)
                ->where('orders.status', '=', $status)->get();
        });
    }

    /**
     * get the current orders Details
     *
     * @return object
     */
    private function currentOrdersDetails($status)
    {
        return $this->getOrdersDetailsBetweenDate($status, $this->startDate, $this->endDate);
    }

    /**
     * Returns the list of top selling categories
     *
     * @return object
     */
    public function getTopSellingCategories()
    {
        $supplier = auth()->guard('supplier')->user()->id;

        return $this->orderItem->getModel()
            ->leftJoin('products', 'order_items.product_id', 'products.id')
            ->leftJoin('orders', 'order_items.order_id', 'orders.id')
            ->leftJoin('b2b_marketplace_orders', 'orders.id', 'b2b_marketplace_orders.order_id')
            ->leftJoin('product_categories', 'products.id', 'product_categories.product_id')
            ->leftJoin('categories', 'product_categories.category_id', 'categories.id')
            ->leftJoin('category_translations', 'categories.id', 'category_translations.category_id')
            ->where('category_translations.locale', app()->getLocale())
            ->where('order_items.created_at', '>=', $this->startDate)
            ->where('order_items.created_at', '<=', $this->endDate)
            ->where('b2b_marketplace_orders.supplier_id', $supplier)
            ->addSelect(DB::raw('SUM(qty_invoiced - qty_refunded) as total_qty_invoiced'))
            ->addSelect(DB::raw('COUNT(products.id) as total_products'))
            ->addSelect('order_items.id', 'categories.id as category_id', 'category_translations.name')
            ->groupBy('categories.id')
            ->havingRaw('SUM(qty_invoiced - qty_refunded) > 0')
            ->orderBy('total_qty_invoiced', 'DESC')
            ->limit(5)
            ->get();
    }

    /**
     * Returns top selling products
     * @return object
     */
    public function getTopSellingProducts()
    {
        $supplier = auth()->guard('supplier')->user()->id;

        return $this->orderItem->getModel()
                ->leftJoin('b2b_marketplace_order_items', 'order_items.id', 'b2b_marketplace_order_items.order_item_id')
                ->leftJoin('b2b_marketplace_orders', 'b2b_marketplace_order_items.b2b_marketplace_order_id', 'b2b_marketplace_orders.id')
                ->select(DB::raw('SUM(order_items.qty_invoiced - order_items.qty_refunded) as total_qty_invoiced'))
                ->addSelect('order_items.id', 'order_items.product_id', 'product_type', 'name')
                ->where('order_items.created_at', '>=', $this->startDate)
                ->where('order_items.created_at', '<=', $this->endDate)
                ->where('b2b_marketplace_orders.supplier_id', $supplier)
                ->whereNull('order_items.parent_id')
                ->groupBy('order_items.product_id')
                ->havingRaw('SUM(order_items.qty_invoiced - order_items.qty_refunded) > 0')
                ->orderBy('total_qty_invoiced', 'DESC')
                ->limit(5)
                ->get();
    }

    /**
     * Returns top selling products
     *
     * @return mixed
     */
    public function getCustomerWithMostSales()
    {
        $supplier = auth()->guard('supplier')->user()->id;

        return $this->order->getModel()
                ->leftJoin('order_items', 'orders.id', 'order_items.order_id')
                ->leftJoin('b2b_marketplace_orders', 'orders.id', 'b2b_marketplace_orders.order_id')
                ->select(DB::raw('SUM(order_items.qty_invoiced - order_items.qty_refunded) as total_qty_invoiced'))
                ->select(DB::raw('SUM(orders.base_grand_total_invoiced - orders.base_grand_total_refunded) as total_base_grand_total_invoiced'))
                ->addSelect(DB::raw('COUNT(orders.id) as total_orders'))
                ->addSelect('orders.id', 'customer_id', 'customer_email', 'customer_first_name', 'customer_last_name')
                ->where('orders.created_at', '>=', $this->startDate)
                ->where('orders.created_at', '<=', $this->endDate)
                ->where('b2b_marketplace_orders.supplier_id', '<=', $supplier)
                ->groupBy('customer_email')
                ->havingRaw('SUM(qty_invoiced - qty_refunded) > 0')
                ->orderBy('total_base_grand_total_invoiced', 'DESC')
                ->limit(5)
                ->get();
    }

    /**
     * Return stock threshold.
     *
     * @return mixed
     */
    public function getStockThreshold()
    {
        $supplier = auth()->guard('supplier')->user()->id;

        return $this->productInventory->getModel()
            ->leftJoin('products', 'product_inventories.product_id', 'products.id')
            ->leftJoin('b2b_marketplace_products', 'products.id', 'b2b_marketplace_products.product_id')
            ->select(DB::raw('SUM(qty) as total_qty'))
            ->addSelect('product_inventories.product_id')
            ->where('products.type', '!=', 'configurable')
            ->where('b2b_marketplace_products.supplier_id', $supplier)
            ->where('product_inventories.vendor_id', $supplier)
            ->groupBy('product_id')
            ->orderBy('total_qty', 'ASC')
            ->limit(5)
            ->get();
    }
}
