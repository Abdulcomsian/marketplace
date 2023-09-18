<?php

namespace Webkul\B2BMarketplace\DataGrids\Supplier;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Order Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'order_id';

    protected $sortOrder = 'desc'; //asc or desc

    /**
     * SupplierRepository object
     *
     * @var Object
     */
    protected $supplierRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplierRepository
     * @return void
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;

        $this->invoker = $this;
    }

    public function prepareQueryBuilder()
    {
        $supplierId =auth()->guard('supplier')->user()->id;

        $queryBuilder = DB::table('b2b_marketplace_orders')
                ->leftJoin('orders', 'b2b_marketplace_orders.order_id', '=', 'orders.id')
                ->select('orders.id', 'b2b_marketplace_orders.order_id', 'b2b_marketplace_orders.base_grand_total', 'b2b_marketplace_orders.grand_total', 'b2b_marketplace_orders.created_at', 'channel_name', 'b2b_marketplace_orders.status', 'orders.order_currency_code')
                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name'))
                ->where('b2b_marketplace_orders.supplier_id', $supplierId);

        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));
        $this->addFilter('id', 'orders.id');
        $this->addFilter('base_grand_total', 'b2b_marketplace_orders.base_grand_total');
        $this->addFilter('grand_total', 'b2b_marketplace_orders.grand_total');
        $this->addFilter('created_at', 'b2b_marketplace_orders.created_at');
        $this->addFilter('status', 'b2b_marketplace_orders.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.sales.orders.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.sales.orders.base-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'grand_total',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.sales.orders.grand-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function($row) {
                if (! is_null($row->grand_total))
                    return core()->formatPrice($row->grand_total, $row->order_currency_code);
            }
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.sales.orders.order-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.sales.orders.status'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'closure' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                if ($row->status == 'processing')
                    return '<span class="badge badge-md badge-success">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.processing") . '</span>';
                else if ($row->status == 'completed')
                    return '<span class="badge badge-md badge-success">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.completed") . '</span>';
                else if ($row->status == "canceled")
                    return '<span class="badge badge-md badge-danger">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.canceled") . '</span>';
                else if ($row->status == "closed")
                    return '<span class="badge badge-md badge-info">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.closed") . '</span>';
                else if ($row->status == "pending")
                    return '<span class="badge badge-md badge-warning">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.pending") . '</span>';
                else if ($row->status == "pending_payment")
                    return '<span class="badge badge-md badge-warning">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.pending-payment") . '</span>';
                else if ($row->status == "fraud")
                    return '<span class="badge badge-md badge-danger">' . trans("b2b_marketplace::app.shop.supplier.account.sales.orders.fraud") . '</span>';
            }
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans("b2b_marketplace::app.shop.supplier.account.sales.orders.billed-to"),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'route' => 'b2b_marketplace.supplier.sales.orders.view',
            'icon' => 'icon eye-icon',
            'method' => 'GET',
            'title' => trans("b2b_marketplace::app.shop.supplier.account.sales.orders.view")
        ], true);
    }
}