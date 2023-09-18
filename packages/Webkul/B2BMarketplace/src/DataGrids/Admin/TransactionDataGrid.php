<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Transaction Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    protected $index = 'id';

    /**
     *
     * @var integer
     */
    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_transactions')
                ->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_transactions.supplier_id', '=', 'b2b_marketplace_suppliers.id')
                ->select('b2b_marketplace_transactions.id', 'transaction_id', 'comment', 'base_total', 'supplier_id')
                ->addSelect(DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name'));

        $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
        $this->addFilter('id', 'b2b_marketplace_transactions.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('b2b_marketplace::app.admin.transactions.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.admin.transactions.supplier-name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'supplier_id',
            'label' => trans('b2b_marketplace::app.admin.transactions.supplier-id'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'transaction_id',
            'label' => trans('b2b_marketplace::app.admin.transactions.transaction-id'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('b2b_marketplace::app.admin.transactions.comment'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_total',
            'label' => trans('b2b_marketplace::app.admin.transactions.total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);
    }
}