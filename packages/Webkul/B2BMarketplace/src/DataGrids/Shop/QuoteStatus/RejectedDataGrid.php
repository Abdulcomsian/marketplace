<?php

namespace Webkul\B2BMarketplace\DataGrids\Shop\QuoteStatus;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Rejected status Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RejectedDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'id';

    /**
     * @var string
     */
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
     * @param  \Webkul\B2BMarketplace\Repositories\SupplierRepository $supplierRepository
     * @return void
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;

        $this->invoker = $this;
    }

    public function prepareQueryBuilder()
    {
        $quoteId = request()->id;
        $customerId = auth()->guard('customer')->user()->id;

        $queryBuilder =  DB::table('b2b_marketplace_customer_quotes')
            ->leftJoin('b2b_marketplace_supplier_quote_items','b2b_marketplace_customer_quotes.id', '=', 'b2b_marketplace_supplier_quote_items.quote_id')
            ->leftJoin('b2b_marketplace_suppliers','b2b_marketplace_supplier_quote_items.supplier_id', '=', 'b2b_marketplace_suppliers.id')
            ->leftJoin('product_flat','b2b_marketplace_supplier_quote_items.product_id', '=', 'product_flat.id')
            ->leftJoin('customers','b2b_marketplace_supplier_quote_items.customer_id', '=', 'customers.id')
            ->Select(
                'b2b_marketplace_customer_quotes.id',
                'b2b_marketplace_supplier_quote_items.status',
                'b2b_marketplace_supplier_quote_items.is_requested_quote',
                'b2b_marketplace_supplier_quote_items.quote_id',
                'b2b_marketplace_supplier_quote_items.supplier_id',
                'product_flat.name as product_name',
                'b2b_marketplace_supplier_quote_items.product_id',
                'b2b_marketplace_supplier_quote_items.quantity',
                'b2b_marketplace_supplier_quote_items.created_at',
                'b2b_marketplace_supplier_quote_items.price_per_quantity as quoted_price',
                DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name')
            )
            ->where('b2b_marketplace_supplier_quote_items.status', '=', 'Rejected')
            ->where('b2b_marketplace_supplier_quote_items.customer_id', $customerId)
            ->where('b2b_marketplace_supplier_quote_items.quote_id', $quoteId);

            $this->addFilter('created_at', 'b2b_marketplace_supplier_quote_items.created_at');
            $this->addFilter('product_name', 'product_flat.name');
            $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
            $this->addFilter('quoted_price', 'b2b_marketplace_supplier_quote_items.price_per_quantity');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.shop.rfq.supplier-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('b2b_marketplace::app.shop.rfq.quoted-quantity'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'quoted_price',
            'label' => trans('b2b_marketplace::app.shop.rfq.quoted-price'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);


        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('b2b_marketplace::app.shop.rfq.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('b2b_marketplace::app.shop.rfq.requested-on'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => '',
            'label' => 'Action',
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'filterable' => false,
            'closure' => true,
            'wrapper' => function($row) {
                return '<a href="' . route('b2b_marketplace.customer.request-quote.view',
                [ $row->id, $row->supplier_id, $row->product_id ]) . '">' . trans('b2b_marketplace::app.shop.rfq.response') . '</a>';
            }
        ]);
    }
}