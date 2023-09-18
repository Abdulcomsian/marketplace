<?php

namespace Webkul\B2BMarketplace\DataGrids\Shop;

use DB;
use Dompdf\Css\Color;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * RequestForQuote Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RequestForQuoteDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'id';

    /**
     * @var integer
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
        $customer = auth()->guard('customer')->user()->id;

        $queryBuilder = DB::table('b2b_marketplace_customer_quotes')
                ->leftJoin('b2b_marketplace_customer_quote_items', 'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_customer_quote_items.quote_id')
                ->leftJoin('product_flat', 'b2b_marketplace_customer_quote_items.product_id', '=', 'product_flat.product_id')
                ->select(
                    'b2b_marketplace_customer_quotes.id','b2b_marketplace_customer_quote_items.quantity','b2b_marketplace_customer_quote_items.quote_status', 'b2b_marketplace_customer_quotes.created_at', 'product_flat.name as product_name',
                    'b2b_marketplace_customer_quote_items.is_requested_quote',
                    'b2b_marketplace_customer_quote_items.product_id',
                    'b2b_marketplace_customer_quotes.name'
                )
                ->where('b2b_marketplace_customer_quote_items.customer_id', $customer);

                $queryBuilder = $queryBuilder->leftJoin('b2b_marketplace_quote_images',
                'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_quote_images.customer_quote_id')
                ->leftJoin('b2b_marketplace_quote_attachments',
                'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_quote_attachments.customer_quote_id')
                ->groupBy('b2b_marketplace_customer_quotes.id')
                ->addSelect('b2b_marketplace_quote_images.path as image', 'b2b_marketplace_quote_attachments.path as file');

        $this->addFilter('created_at', 'b2b_marketplace_customer_quotes.created_at');
        $this->addFilter('name', 'b2b_marketplace_customer_quotes.name');
        $this->addFilter('quote_status', 'b2b_marketplace_customer_quote_items.quote_status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'name',
            'label' => trans('Name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('b2b_marketplace::app.shop.rfq.quantity'),
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

                    echo '<a href="'. route('b2b_marketplace.shop.customers.quote.attachment.download', $file->id) .'" style="font-size: 16px;" >
                    '. trans('b2b_marketplace::app.shop.rfq.download') .'
                    </a>';
                } else {
                    echo '<h3 style="margin-left: 30%;"> -- </h3>';
                }

            }
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
                    <img src="' . \Storage::url($image->image) . '" alt="Sample Images" href="' . \Storage::url($image->image) . '" target="new" height="35px" width="65px" style="cursor:pointer margin-left: 4px;">
                    </a> <a href="'. route('b2b_marketplace.shop.customers.quote.images.download', $image->id) .'" style="font-size: 13px;">
                    '. trans('b2b_marketplace::app.shop.rfq.download-all') .'
                    </a>';
                }

            }
        ]);

        $this->addColumn([
            'index' => 'quote_status',
            'label' => trans('b2b_marketplace::app.shop.rfq.quote-status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($row) {

                if ($row->quote_status == 'New')
                    echo '<span class="badge badge-md badge-success">'.'New'.'</span>';
                if ($row->quote_status == 'Processing')
                    echo '<span class="badge badge-md badge-info">Processing</span>';
                else if ($row->quote_status == 'Completed')
                    echo '<span class="badge badge-md badge-success">Completed</span>';
                else if ($row->quote_status == "Rejected")
                    echo '<span class="badge badge-md badge-danger">Rejected</span>';
                else if ($row->quote_status == "Closed")
                    echo '<span class="badge badge-md badge-info">Closed</span>';
                else if ($row->quote_status == "Pending")
                    echo '<span class="badge badge-md badge-warning">Pending</span>';
                else if ($row->quote_status == "fraud")
                    echo '<span class="badge badge-md badge-danger">Fraud</span>';
                else if ($row->quote_status == "")
                    echo '<span class="badge badge-md badge-success">' .'New'. '</span>';
            }
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('b2b_marketplace::app.shop.rfq.requested-on'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => '',
            'label' => 'Action',
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function($row) {

                if ($row->quote_status == 'New' && $row->is_requested_quote == 1) {
                    echo '<span style="color:#0041ff; display:block;">'.'No Response'.'</span>';
                } else {
                    return '<a href="' . route('b2b_marketplace.supplier.request-quote.status',
                    [ 'new', $row->id, $row->product_id ]) . '">' . '<span class="icon eye-icon">' . '</span>' . '</a>';
                }
            }
        ]);
    }
}