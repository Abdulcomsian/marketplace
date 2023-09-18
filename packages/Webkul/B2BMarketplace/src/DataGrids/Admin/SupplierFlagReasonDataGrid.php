<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Suppplier Flag Data Grid class
 *
 * @author Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierFlagReasonDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_supplier_flag_reasons')

        ->select('b2b_marketplace_supplier_flag_reasons.id',
            'b2b_marketplace_supplier_flag_reasons.reason',
            'b2b_marketplace_supplier_flag_reasons.status'
        );

        $this->addFilter('reason', 'b2b_marketplace_supplier_flag_reasons.reason');
        $this->addFilter('status', 'b2b_marketplace_supplier_flag_reasons.status');
        $this->addFilter('id', 'b2b_marketplace_supplier_flag_reasons.id');
        $this->addFilter('created_at', 'b2b_marketplace_supplier_flag_reasons.created_at');

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
            'index' => 'reason',
            'label' => trans('b2b_marketplace::app.admin.products.flag.reason'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->status)
                    return trans('b2b_marketplace::app.admin.suppliers.active');
                else
                    return trans('b2b_marketplace::app.admin.suppliers.inactive');
            }
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'type'    => 'Edit',
            'method'  => 'GET',
            'route'   => 'b2b_marketplace.admin.supplier-flag.reason.edit',
            'icon'    => 'icon pencil-lg-icon',
            'title'   => ''
        ]);

        $this->addAction([
            'type'   => 'Delete',
            'method' => 'Post',
            'route'  => 'b2b_marketplace.admin.supplier-flag.reason.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete'),
            'icon'   => 'icon trash-icon',
            'title'  => ''
        ]);

    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('b2b_marketplace::app.admin.suppliers.delete'),
            'action' => route('b2b_marketplace.admin.supplier-flag.reason.mass-delete'),
            'method' => 'GET',
            'title' => 'delete'
        ]);
    }
}