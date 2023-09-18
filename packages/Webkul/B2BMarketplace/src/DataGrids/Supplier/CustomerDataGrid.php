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
class CustomerDataGrid extends DataGrid
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
        $supplierId = auth()->guard('supplier')->user()->id;

        $queryBuilder = DB::table('b2b_marketplace_orders')
                ->leftJoin('orders', 'b2b_marketplace_orders.order_id', '=', 'orders.id')
                ->leftJoin('customers','orders.customer_id', '=', 'customers.id')
                ->leftJoin('addresses','customers.id', '=', 'addresses.customer_id')

                ->select('orders.id', 'b2b_marketplace_orders.order_id', 'b2b_marketplace_orders.base_grand_total', 'b2b_marketplace_orders.created_at', 'channel_name', 'b2b_marketplace_orders.status', 'orders.order_currency_code','customers.email', 'addresses.phone as customer_phone','customers.gender'
                ,DB::raw('COUNT(DISTINCT orders.id) as order_count'))

                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name'))

                ->addSelect(DB::raw('CONCAT(addresses.address1, " ", addresses.city," ", addresses.state," " ,addresses.country," ",addresses.postcode) as customer_address'))
                ->where('b2b_marketplace_orders.supplier_id', $supplierId)
                ->groupBy('orders.customer_id');

        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));
        $this->addFilter('customer_phone', 'addresses.phone');
        $this->addFilter('customer_address', DB::raw('CONCAT(addresses.address1, " ", addresses.city," ", addresses.state," " ,addresses.country," ",addresses.postcode)'));
        $this->addFilter('id', 'orders.id');
        $this->addFilter('base_grand_total', 'b2b_marketplace_orders.base_grand_total');
        $this->addFilter('created_at', 'b2b_marketplace_orders.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans("b2b_marketplace::app.shop.supplier.account.customers.customer-name"),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans("b2b_marketplace::app.shop.supplier.account.customers.email"),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_phone',
            'label' => trans("b2b_marketplace::app.shop.supplier.account.customers.phone"),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_address',
            'label' => trans("b2b_marketplace::app.shop.supplier.account.customers.address"),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.customers.base-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'order_count',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.customers.order'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function($count) {
                return '<a href="' . route('b2b_marketplace.supplier.sales.orders.index') . '">' .
                $count->order_count . '</a>';
            }
        ]);
    }
}