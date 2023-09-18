<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Supplier Review Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewDataGrid extends DataGrid
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
    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_supplier_reviews')
                ->leftJoin('customers', 'b2b_marketplace_supplier_reviews.customer_id', '=', 'customers.id')
                ->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_supplier_reviews.supplier_id', '=', 'b2b_marketplace_suppliers.id')
                ->select('b2b_marketplace_supplier_reviews.id', 'rating', 'b2b_marketplace_supplier_reviews.status', 'comment', 'b2b_marketplace_supplier_reviews.created_at')
                ->addSelect(DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name'))
                ->addSelect(DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name'));

        $this->addFilter('customer_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));
        $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
        $this->addFilter('id', 'b2b_marketplace_supplier_reviews.id');
        $this->addFilter('status', 'b2b_marketplace_supplier_reviews.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('b2b_marketplace::app.admin.reviews.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('b2b_marketplace::app.admin.reviews.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.admin.reviews.supplier-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'rating',
            'label' => trans('b2b_marketplace::app.admin.reviews.rating'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('b2b_marketplace::app.admin.reviews.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'closure' => true,
            'wrapper'    => function ($row) {
                if ($row->status  == 'approved') {
                    return '<span class="badge badge-md badge-success">' . trans('b2b_marketplace::app.admin.reviews.approved') . '</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">' . trans('b2b_marketplace::app.admin.reviews.un-approved') . '</span>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('b2b_marketplace::app.admin.reviews.comment'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('b2b_marketplace::app.admin.reviews.created-at'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'update',
            'label' => trans('b2b_marketplace::app.admin.reviews.update'),
            'title' => trans('b2b_marketplace::app.admin.reviews.update'),
            'action' => route('admin.b2b_marketplace.reviews.massupdate'),
            'method' => 'POST',
            'options' => [
                trans('b2b_marketplace::app.admin.reviews.approve') => 'approved',
                trans('b2b_marketplace::app.admin.reviews.unapprove') => 'unapproved'
            ]
        ]);
    }
}