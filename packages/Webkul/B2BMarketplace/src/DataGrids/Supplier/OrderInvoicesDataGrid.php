<?php

namespace Webkul\B2BMarketplace\DataGrids\Supplier;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Supplier Order Invoices DataGrid Class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderInvoicesDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $queryBuilder = DB::table('invoices')
                ->leftJoin('orders as ors', 'invoices.order_id', '=', 'ors.id')
                ->Join('b2b_marketplace_orders', 'ors.id', '=', 'b2b_marketplace_orders.order_id')
                ->select('invoices.id as id', 'ors.increment_id as order_id', 'invoices.state as state', 'invoices.base_grand_total as base_grand_total', 'invoices.created_at as created_at')
                ->where('b2b_marketplace_orders.supplier_id', $supplierId);

        $this->addFilter('id', 'invoices.id');
        $this->addFilter('order_id', 'ors.increment_id');
        $this->addFilter('base_grand_total', 'invoices.base_grand_total');
        $this->addFilter('created_at', 'invoices.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'order_id',
            'label' => trans('admin::app.datagrid.order-id'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('admin::app.datagrid.grand-total'),
            'type' => 'price',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.datagrid.invoice-date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => trans('b2b_marketplace::app.admin.orders.order-invoice-view'),
            'method' => 'GET',
            'route' => 'b2b_marketplace.sales.invoices.view',
            'icon' => 'icon eye-icon'
        ], true);
    }
}