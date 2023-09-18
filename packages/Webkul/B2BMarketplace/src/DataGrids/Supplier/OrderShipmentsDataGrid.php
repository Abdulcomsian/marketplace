<?php

namespace Webkul\B2BMarketplace\DataGrids\Supplier;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Sales\Models\OrderAddress;
use DB;

/**
 * Suppplier Order Shipments DataGrid Class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderShipmentsDataGrid extends DataGrid
{
    protected $index = 'shipment_id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $queryBuilder = DB::table('shipments')
                ->leftJoin('addresses as order_address_shipping', function($leftJoin) {
                    $leftJoin->on('order_address_shipping.order_id', '=', 'shipments.order_id')
                        ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
                })
                ->leftJoin('orders as ors', 'shipments.order_id', '=', 'ors.id')
                ->leftJoin('b2b_marketplace_shipments', 'shipments.id', '=', 'b2b_marketplace_shipments.shipment_id')
                ->leftJoin('b2b_marketplace_orders', 'ors.id', '=', 'b2b_marketplace_orders.order_id')
                ->leftJoin('inventory_sources as is', 'shipments.inventory_source_id', '=', 'is.id')
                ->select('shipments.id as shipment_id', 'ors.increment_id as shipment_order_id', 'shipments.total_qty as shipment_total_qty', 'is.name as inventory_source_name', 'ors.created_at as order_date', 'shipments.created_at as shipment_created_at')
                ->addSelect(DB::raw('CONCAT(order_address_shipping.first_name, " ", order_address_shipping.last_name) as shipped_to'))
                ->where('b2b_marketplace_orders.supplier_id', $supplierId);



        $this->addFilter('shipment_id', 'shipments.id');
        $this->addFilter('shipment_order_id', 'shipments.order_id');
        $this->addFilter('shipment_total_qty', 'shipments.total_qty');
        $this->addFilter('inventory_source_name', 'is.name');
        $this->addFilter('order_date', 'ors.created_at');
        $this->addFilter('shipment_created_at', 'shipments.created_at');
        $this->addFilter('shipped_to', DB::raw('CONCAT(order_address_shipping.first_name, " ", order_address_shipping.last_name)'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'shipment_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'shipment_order_id',
            'label' => trans('admin::app.datagrid.order-id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'shipment_total_qty',
            'label' => trans('admin::app.datagrid.total-qty'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'inventory_source_name',
            'label' => trans('admin::app.datagrid.inventory-source'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'order_date',
            'label' => trans('admin::app.datagrid.order-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'shipment_created_at',
            'label' => trans('admin::app.datagrid.shipment-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'shipped_to',
            'label' => trans('admin::app.datagrid.shipment-to'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => trans('b2b_marketplace::app.admin.orders.order-shipment-view'),
            'method' => 'GET',
            'route' => 'b2b_marketplace.sales.shipments.view',
            'icon' => 'icon eye-icon'
        ], true);
    }
}