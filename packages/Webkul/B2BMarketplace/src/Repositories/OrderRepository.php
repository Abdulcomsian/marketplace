<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\OrderItemRepository as OrderItem;
use Webkul\Product\Repositories\ProductRepository as BaseProduct;

/**
 * Supplier Order Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderRepository extends Repository
{
    /**
     * SupplierRepository object
     *
     * @var Object
     */
    protected $supplierRepository;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * Base Product Repository object
     *
     * @var Object
     */
    protected $baseProduct;

    /**
     * OrderItem Repository object
     *
     * @var Object
     */
    protected $orderItem;

    /**
     * Order Repository object
     *
     * @var Object
     */
    protected $order;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\SupplierRepository      $supplierRepository
     * @param  Webkul\Product\Repositories\OrderItemRepository   $orderItemRepository
     * @param  Webkul\Product\Repositories\TransactionRepository $transactionRepository
     * @param  Webkul\Product\Repositories\ProductRepository     $baseProduct
     * @param  Illuminate\Container\Container                    $app
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        OrderItemRepository $orderItemRepository,
        ProductRepository $productRepository,
        BaseProduct $baseProduct,
        OrderItem $orderItem,
        Order $order,
        App $app
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->baseProduct = $baseProduct;

        $this->productRepository = $productRepository;

        $this->orderItem = $orderItem;

        $this->order = $order;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Order';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $order = $data['order'];

        Event::dispatch('b2b_marketplace.sales.order.save.before', $data);

        $supplierOrders = [];

        $commissionPercentage = core()->getConfigData('b2b_marketplace.settings.general.commission_per_unit');

        foreach ($order->items()->get() as $item) {

            $supplier = $this->productRepository->getSupplierByProductId($item->product_id);

            if (isset($item->additional['supplier_info']) && !$item->additional['supplier_info']['is_owner']) {
                $supplier = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);
            } else {
                $supplier = $this->productRepository->getSupplierByProductId($item->product_id);
            }

            if (! $supplier)
                continue;

            if (! $supplier->is_approved)
                continue;


            $supplierProduct = $this->productRepository->findOneWhere([
                'product_id' => $item->product->id,
                'supplier_id' => $supplier->id,
            ]);

            if (! $supplierProduct && isset($item->additional['supplier_info'])) {

                $supplierProduct = $this->baseProduct->findOneWhere([
                    'id' => $item->product->id,
                ]);

                $b2b_marketplace_product_id = null;
            } else {

                $b2b_marketplace_product_id  = $supplierProduct->id;

                if (! $supplierProduct->is_approved)
                continue;
            }

            $supplierOrder = $this->findOneWhere([
                    'order_id' => $order->id,
                    'supplier_id' => $supplier->id,
                ]);

            if (! $supplierOrder) {
                $supplierOrders[] = $supplierOrder = parent::create([
                        'status' => 'pending',
                        'supplier_payout_status' => 'pending',
                        'order_id' => $order->id,
                        'supplier_id' => $supplier->id,
                        'commission_percentage' => $commissionPercentage,
                        'is_withdrawal_requested' => 0,
                        'shipping_amount' => $order->shipping_amount,
                        'base_shipping_amount' => $order->base_shipping_amount
                    ]);
            }

            $commission = $baseCommission = 0;
            $supplierTotal = $baseSupplierTotal = 0;

            if ($commissionPercentage) {
                $commission = ($item->total * $commissionPercentage) / 100;
                $baseCommission = ($item->base_total * $commissionPercentage) / 100;

                $supplierTotal = $item->total - $commission;
                $baseSupplierTotal = $item->base_total - $baseCommission;
            }

            $supplierOrderItem = $this->orderItemRepository->create([
                    'b2b_marketplace_product_id' => $b2b_marketplace_product_id,
                    'b2b_marketplace_order_id' => $supplierOrder->id,
                    'order_item_id' => $item->id,
                    'commission' => $commission,
                    'base_commission' => $baseCommission,
                    'supplier_total' => $supplierTotal + $item->tax_amount - $item->discount_amount,
                    'base_supplier_total' => $baseSupplierTotal + $item->base_tax_amount - $item->base_discount_amount
                ]);

            if ($childItem = $item->child) {
                $childSupplierProduct = $this->productRepository->findOneWhere([
                        'product_id' => $childItem->product->id,
                        'supplier_id' => $supplier->id,
                    ]);

                    if (! $childSupplierProduct) {
                        $childSupplierProduct = $this->baseProduct->findOneWhere([
                            'id' => $childItem->product->id,
                        ]);
                    }

                $childSupplierOrderItem = $this->orderItemRepository->create([
                    'b2b_marketplace_product_id' => $childSupplierProduct->id,
                    'b2b_marketplace_order_id' => $supplierOrder->id,
                    'order_item_id' => $childItem->id,
                    'parent_id' => $supplierOrderItem->id
                ]);
            }
        }

        foreach ($supplierOrders as $order) {

            $this->collectTotals($order);

            Event::dispatch('b2b_marketplace.sales.order.save.after', $order);
        }

        session()->forget('b2b_marketplace_shipping_rates');
    }


	   /**
     * @param array $data
     * @return mixed
     */
    public function cancel(array $data)
    {
        $order = $data['order'];

        $supplierOrders = $this->findWhere(['order_id' => $order->id]);

        foreach ($supplierOrders as $supplierOrder) {
            Event::dispatch('b2b_marketplace.sales.order.cancel.before', $supplierOrder);

            $this->updateOrderStatus($supplierOrder);

            Event::dispatch('b2b_marketplace.sales.order.cancel.after', $supplierOrder);
        }
    }


    /**
     * Cancel order
     *
     * @param int $orderId
     * @return mixed
     */
    public function supplierCancelOrder($orderId)
    {
        $supplier = auth()->guard('supplier')->user();

        $supplierOrders = $this->findWhere([
            'order_id' => $orderId,
            'supplier_id' => $supplier->id
        ]);

        foreach ($supplierOrders as $supplierOrder) {
            if (! $supplierOrder->canCancel())
                return false;

            Event::dispatch('b2b_marketplace.sales.order.cancel.before', $supplierOrder);

            foreach ($supplierOrder->items as $item) {
                if ($item->item->qty_to_cancel) {
                    $this->orderItem->returnQtyToProductInventory($item->item);

                    $item->item->qty_canceled += $item->item->qty_to_cancel;

                    $item->item->save();
                }
            }

            $this->updateOrderStatus($supplierOrder);

            $result = $this->order->isInCanceledState($supplierOrder->order);

            if ($result)
                $supplierOrder->order->update(["status" => "canceled"]);

            Event::dispatch('marketplace.sales.order.cancel.after', $supplierOrder);

            return true;
        }
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInCompletedState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyInvoiced = 0;
        $totalQtyShipped = 0;
        $totalQtyRefunded = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items  as $supplierOrderItem) {
            $totalQtyOrdered += $supplierOrderItem->item->qty_ordered;
            $totalQtyInvoiced += $supplierOrderItem->item->qty_invoiced;
            $totalQtyShipped += $supplierOrderItem->item->qty_shipped;
            $totalQtyRefunded += $supplierOrderItem->item->qty_refunded;
            $totalQtyCanceled += $supplierOrderItem->item->qty_canceled;
        }

        if ($totalQtyOrdered != ($totalQtyRefunded + $totalQtyCanceled) &&
            $totalQtyOrdered == $totalQtyInvoiced + $totalQtyRefunded + $totalQtyCanceled &&
            $totalQtyOrdered == $totalQtyShipped + $totalQtyRefunded + $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInCanceledState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items as $supplierOrderItem) {
            $totalQtyOrdered += $supplierOrderItem->item->qty_ordered;
            $totalQtyCanceled += $supplierOrderItem->item->qty_canceled;
        }

        if ($totalQtyOrdered == $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInClosedState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyRefunded = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items  as $supplierOrderItem) {
            $totalQtyOrdered += $supplierOrderItem->item->qty_ordered;
            $totalQtyRefunded += $supplierOrderItem->item->qty_refunded;
            $totalQtyCanceled += $supplierOrderItem->item->qty_canceled;
        }

        if ($totalQtyOrdered == $totalQtyRefunded + $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function updateOrderStatus($order)
    {
        $status = 'processing';

        if ($this->isInCompletedState($order))
            $status = 'completed';

        if ($this->isInCanceledState($order))
            $status = 'canceled';
        elseif ($this->isInClosedState($order))
            $status = 'closed';

        $order->status = $status;
        $order->save();
    }

    /**
     * Updates marketplace order totals
     *
     * @param Order $order
     * @return void
     */
    public function collectTotals($order)
    {
        $order->grand_total = $order->base_grand_total = 0;
        $order->sub_total = $order->base_sub_total = 0;
        $order->tax_amount = $order->base_tax_amount = 0;
        $order->discount_amount_invoiced = $order->base_discount_amount_invoiced = 0;
        $order->commission = $order->base_commission = 0;
        $order->supplier_total = $order->base_supplier_total = 0;
        $order->total_item_count = $order->total_qty_ordered = 0;
        $order->discount_amount = $order->base_discount_amount = 0;

        $shippingCodes = explode('_', $order->order->shipping_method);
        $carrier = current($shippingCodes);
        $shippingMethod = end($shippingCodes);

        $marketplaceShippingRates = session()->get('marketplace_shipping_rates');

        if (isset($marketplaceShippingRates[$carrier])
            && isset($marketplaceShippingRates[$carrier][$shippingMethod])
            && isset($marketplaceShippingRates[$carrier][$shippingMethod][$order->supplier_id])) {
            $supplierShippingRate = $marketplaceShippingRates[$carrier][$shippingMethod][$order->supplier_id];

            $order->shipping_amount = $supplierShippingRate['amount'];
            $order->base_shipping_amount = $supplierShippingRate['base_amount'];
        }

        foreach ($order->items()->get() as $supplierOrderItem) {
            $item = $supplierOrderItem->item;

            $order->discount_amount += $item->discount_amount;
            $order->base_discount_amount += $item->base_discount_amount;

            $order->grand_total += $item->total + $item->tax_amount - $item->discount_amount;
            $order->base_grand_total += $item->base_total + $item->base_tax_amount - $item->base_discount_amount;

            $order->sub_total += $item->total;
            $order->base_sub_total += $item->base_total;

            $order->tax_amount += $item->tax_amount;
            $order->base_tax_amount += $item->base_tax_amount;

            $order->commission += $supplierOrderItem->commission;
            $order->base_commission += $supplierOrderItem->base_commission;

            $order->supplier_total += $supplierOrderItem->supplier_total;
            $order->base_supplier_total += $supplierOrderItem->base_supplier_total;

            $order->total_qty_ordered += $item->qty_ordered;

            $order->total_item_count += 1;
        }

        if ($order->shipping_amount > 0) {
            $order->grand_total += $order->shipping_amount;
            $order->base_grand_total += $order->base_shipping_amount;

            $order->supplier_total += $order->shipping_amount;
            $order->base_supplier_total += $order->base_shipping_amount;
        }

        $order->sub_total_invoiced = $order->base_sub_total_invoiced = 0;
        $order->shipping_invoiced = $order->base_shipping_invoiced = 0;
        $order->commission_invoiced = $order->base_commission_invoiced = 0;
        $order->supplier_total_invoiced = $order->base_supplier_total_invoiced = 0;

        foreach ($order->invoices as $invoice) {
            $order->sub_total_invoiced += $invoice->sub_total;
            $order->base_sub_total_invoiced += $invoice->base_sub_total;

            $order->shipping_invoiced += $invoice->shipping_amount;
            $order->base_shipping_invoiced += $invoice->base_shipping_amount;

            $order->tax_amount_invoiced += $invoice->tax_amount;
            $order->base_tax_amount_invoiced += $invoice->base_tax_amount;

            $order->discount_amount_invoiced += $invoice->discount_amount;
            $order->base_discount_amount_invoiced += $invoice->base_discount_amount;


            $order->commission_invoiced += $commissionInvoiced = ($invoice->sub_total * $order->commission_percentage) / 100;
            $order->base_commission_invoiced += $baseCommissionInvoiced = ($invoice->base_sub_total * $order->commission_percentage) / 100;

            $order->supplier_total_invoiced += $invoice->sub_total - $commissionInvoiced - $order->discount_amount + $invoice->shipping_amount + $invoice->tax_amount;
            $order->base_supplier_total_invoiced += $invoice->base_sub_total - $baseCommissionInvoiced - $order->base_discount_amount + $invoice->base_shipping_amount + $invoice->base_tax_amount;
        }

        $order->grand_total_invoiced = $order->sub_total_invoiced + $order->shipping_invoiced + $order->tax_amount_invoiced - $order->discount_amount_invoiced;
        $order->base_grand_total_invoiced = $order->base_sub_total_invoiced + $order->base_shipping_invoiced + $order->base_tax_amount_invoiced - $order->base_discount_amount_invoiced;

        foreach ($order->refunds as $refund) {
            $order->sub_total_refunded += $refund->sub_total;
            $order->base_sub_total_refunded += $refund->base_sub_total;

            $order->shipping_refunded += $refund->shipping_amount;
            $order->base_shipping_refunded += $refund->base_shipping_amount;

            $order->tax_amount_refunded += $refund->tax_amount;
            $order->base_tax_amount_refunded += $refund->base_tax_amount;

            $order->discount_refunded += $refund->discount_amount;
            $order->base_discount_refunded += $refund->base_discount_amount;
        }

        $order->grand_total_refunded = $order->sub_total_refunded + $order->shipping_refunded + $order->tax_amount_refunded - $order->discount_refunded;

        $order->base_grand_total_refunded = $order->base_sub_total_refunded + $order->base_shipping_refunded + $order->base_tax_amount_refunded - $order->base_discount_refunded;
        $order->save();

        return $order;
    }
}
