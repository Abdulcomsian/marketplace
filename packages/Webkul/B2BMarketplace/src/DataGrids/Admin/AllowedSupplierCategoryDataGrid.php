<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Supplier Category DataGrid class
 *
 * @author Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2022 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AllowedSupplierCategoryDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    protected $enableFilterMap = true;

    protected $extraFilters = [
        'channels',
        'locales',
    ];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_supplier_allowed_categories')
            ->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_supplier_allowed_categories.supplier_id', 'b2b_marketplace_suppliers.id')
            ->select(
                'b2b_marketplace_supplier_allowed_categories.categories',
                'b2b_marketplace_supplier_allowed_categories.id',
                DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name')
            );

            $this->addFilter('id', 'b2b_marketplace_supplier_allowed_categories.id');
            
            $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('b2b_marketplace::app.admin.suppliers.id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.admin.supplier.supplier-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'edit',
            'method' => 'GET',
            'route' => 'b2b_marketplace.admin.supplier.category.edit',
            'icon' => 'icon pencil-lg-icon',
            'title' => ''
        ], true);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route' => 'b2b_marketplace.admin.supplier.category.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Supplier Category']),
            'icon' => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('b2b_marketplace::app.admin.supplier.delete'),
            'action' => route('b2b_marketplace.admin.supplier.category.mass-delete'),
            'method' => 'POST',
            'title'  => ''
        ], true) ;
    }
}