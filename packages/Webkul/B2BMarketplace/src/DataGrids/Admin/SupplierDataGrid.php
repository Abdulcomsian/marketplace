<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Supplier Data Grid
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'id';

    /**
     *
     * @var integer
     */
    protected $sortOrder = 'asc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_suppliers')
            ->select('b2b_marketplace_suppliers.id', 'b2b_marketplace_suppliers.created_at', 'b2b_marketplace_suppliers.email', 'b2b_marketplace_suppliers.is_approved', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name'));

        $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
        $this->addFilter('id', 'b2b_marketplace_suppliers.id');
        $this->addFilter('created_at', 'b2b_marketplace_suppliers.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('b2b_marketplace::app.admin.suppliers.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.admin.suppliers.supplier-name'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('b2b_marketplace::app.admin.suppliers.supplier-email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('b2b_marketplace::app.admin.suppliers.created-at'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product',
            'label' => trans('b2b_marketplace::app.admin.suppliers.add-product'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'closure' => true,
            'wrapper' => function($row) {
                if ($row->is_approved == 1) {
                    return '<a href = "' . route('admin.b2b_marketplace.supplier.product.search', $row->id) . '" class="btn btn-sm btn-primary pay-btn" name="seller_id" value="' . $row->id .'">' . trans('b2b_marketplace::app.admin.suppliers.add-product') . '</a>';
                } else {
                    return '<a href = "' . route('admin.b2b_marketplace.supplier.product.search', $row->id) . '" class="btn btn-sm btn-primary pay-btn" disabled name="seller_id" value="' . $row->id .'">' . trans('b2b_marketplace::app.admin.suppliers.add-product') . '</a>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'is_approved',
            'label' => trans('b2b_marketplace::app.admin.suppliers.is-approved'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'closure' => true,
            'wrapper'    => function ($value) {
                if ($value->is_approved == 1) {
                    return '<span class="badge badge-md badge-success">' . trans('b2b_marketplace::app.admin.suppliers.approved') . '</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">' . trans('b2b_marketplace::app.admin.suppliers.un-approved') . '</span>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'b2b_marketplace.admin.suppliers.edit',
            'icon' => 'icon pencil-lg-icon',
            'title' => trans('b2b_marketplace::app.admin.supplier.edit')
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST',
            'route' => 'b2b_marketplace.admin.suppliers.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'icon trash-icon',
            'title' => trans('b2b_marketplace::app.admin.supplier.delete')
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('b2b_marketplace::app.admin.suppliers.delete'),
            'action' => route('b2b_marketplace.admin.suppliers.mass-delete'),
            'method' => 'POST',
            'title' => trans('b2b_marketplace::app.admin.suppliers.delete')
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => trans('b2b_marketplace::app.admin.suppliers.update'),
            'action' => route('b2b_marketplace.admin.suppliers.mass-update'),
            'method' => 'POST',
            'title' => trans('b2b_marketplace::app.admin.suppliers.update'),
            'options' => [
                trans('b2b_marketplace::app.admin.suppliers.approve') => 1,
                trans('b2b_marketplace::app.admin.suppliers.unapprove') => 0
            ]
        ]);
    }
}