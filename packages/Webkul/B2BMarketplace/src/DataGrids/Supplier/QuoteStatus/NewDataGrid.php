<?php

namespace Webkul\B2BMarketplace\DataGrids\Supplier\QuoteStatus;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * New status Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewDataGrid extends DataGrid
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

        $queryBuilder =  DB::table('b2b_marketplace_customer_quotes')
            ->leftJoin('b2b_marketplace_customer_quote_items','b2b_marketplace_customer_quotes.id', '=', 'b2b_marketplace_customer_quote_items.quote_id')
            ->leftJoin('customers','b2b_marketplace_customer_quote_items.customer_id', '=', 'customers.id')
            ->leftJoin('product_flat', 'b2b_marketplace_customer_quote_items.product_id', '=', 'product_flat.product_id')
            ->Select(
                'b2b_marketplace_customer_quote_items.id',
                'b2b_marketplace_customer_quote_items.status',
                'b2b_marketplace_customer_quote_items.quote_id',
                'product_flat.name as product_name',
                'b2b_marketplace_customer_quote_items.product_id','b2b_marketplace_customer_quote_items.quantity','b2b_marketplace_customer_quote_items.created_at',
                DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name')
            )
            ->where('b2b_marketplace_customer_quote_items.status', '=', 'New')
            ->where('b2b_marketplace_customer_quote_items.supplier_id', '=', $supplierId)
            ->where('product_flat.locale', app()->getLocale());

        $queryBuilder = $queryBuilder->leftJoin('b2b_marketplace_quote_images',
            'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_quote_images.customer_quote_id')
            ->leftJoin('b2b_marketplace_quote_attachments',
            'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_quote_attachments.customer_quote_id')
            ->addSelect(
                'b2b_marketplace_quote_images.path as image', 'b2b_marketplace_quote_attachments.path as file'
            );

            $this->addFilter('customer_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));
            $this->addFilter('id','b2b_marketplace_customer_quote_items.id');
            $this->addFilter('quote_id', 'b2b_marketplace_customer_quote_items.quote_id');
            $this->addFilter('product_name', 'product_flat.name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'file',
            'label' => trans('b2b_marketplace::app.shop.rfq.attachment'),
            'type' => 'number',
            'searchable' => false,
            'wrapper' => function ($file) {
                if ($file->file != "") {

                    echo '<a href="'. route('b2b_marketplace.supplier.quote.attachment.download', $file->id) .'" style="font-size: 14px;" >
                    '. trans('b2b_marketplace::app.shop.rfq.download') .'
                    </a>';
                } else {
                    echo '<h3 style="margin-left: 30%;"> -- </h3>';
                }

            }
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
            'index' => 'customer_name',
            'label' => trans('b2b_marketplace::app.shop.rfq.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'quote_id',
            'label' => trans('Quote Id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'image',
            'label' => trans('b2b_marketplace::app.shop.rfq.sample-image'),
            'type' => 'number',
            'searchable' => false,
            'wrapper' => function ($image) {
                if ($image->image == "") {
                    echo '<h3 style="margin-left: 30%;"> -- </h3>';
                } else {
                    echo '<a target="new" href="' . \Storage::url($image->image) . '">
                    <img src="' . \Storage::url($image->image) . '" alt="Sample Images" href="' . \Storage::url($image->image) . '" target="new" height="31px" width="47px" style="cursor:pointer;">
                    </a> <div><a href="'. route('b2b_marketplace.supplier.quote.images.download', $image->id) .'" style="font-size: 12px;">
                    '. trans('b2b_marketplace::app.shop.rfq.download-all') .'
                    </a></div>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('b2b_marketplace::app.shop.rfq.quantity'),
            'type' => 'number',
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
            'filterable' => false,
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
                return '<a href="' . route('b2b_marketplace.supplier.request-quote.Answered.view',
                [ $row->quote_id, $row->product_id ]) . '">' . trans('b2b_marketplace::app.shop.rfq.response') . '</a>';
            }
        ]);
    }
}