<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\QuoteRepository;

/**
 * Supplier Order Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RFQDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'quote_id';

    protected $sortOrder = 'desc'; //asc or desc

    /**
     * SupplierRepository object
     *
     * @var Object
     */
    protected $quoteRepository;

    /**
     * Supplier object
     *
     * @var Object
     */
    protected $supplier;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2bMarketplace\Repositories\QuoteRepository $quoteRepository
     * @return void
     */
    public function __construct(QuoteRepository $quoteRepository)
    {
        parent::__construct();

        $this->quoteRepository = $quoteRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_customer_quotes')
            ->leftJoin('b2b_marketplace_customer_quote_items', 'b2b_marketplace_customer_quotes.id', '=', 'b2b_marketplace_customer_quote_items.quote_id')
            ->leftJoin('product_flat', 'b2b_marketplace_customer_quote_items.product_id', '=', 'product_flat.product_id')
            ->select(
                'b2b_marketplace_customer_quotes.quote_title',
                'b2b_marketplace_customer_quotes.name as customer_name',
                'b2b_marketplace_customer_quotes.quote_brief',
                'b2b_marketplace_customer_quote_items.quote_id',
                'product_flat.name as product_name',
                'product_flat.product_id as productId',
                'b2b_marketplace_customer_quotes.id',
                'b2b_marketplace_customer_quote_items.quote_status',
                'b2b_marketplace_customer_quote_items.supplier_id'
            )
            ->addSelect(DB::raw('COUNT(b2b_marketplace_customer_quote_items.quote_id) as total_product'))
            ->addSelect(DB::raw('sum(b2b_marketplace_customer_quote_items.price_per_quantity) as price'))
            ->groupBy('b2b_marketplace_customer_quotes.id');

        $queryBuilder = $queryBuilder->leftJoin('b2b_marketplace_quote_images',
            'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_quote_images.customer_quote_id')
            ->leftJoin('b2b_marketplace_quote_attachments',
            'b2b_marketplace_customer_quotes.id', '=','b2b_marketplace_quote_attachments.customer_quote_id')
            ->groupBy('b2b_marketplace_customer_quotes.id')
            ->addSelect('b2b_marketplace_quote_images.path as image', 'b2b_marketplace_quote_attachments.path as file');

        $this->addFilter('id','b2b_marketplace_customer_quote_items.id');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('quote_status','b2b_marketplace_customer_quote_items.quote_status');
        $this->addFilter('customer_name','b2b_marketplace_customer_quotes.name');
        $this->addFilter('total_product', DB::raw('COUNT(b2b_marketplace_customer_quote_items.quote_id)'));


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
            'index' => 'customer_name',
            'label' => trans('b2b_marketplace::app.admin.rfq.customer-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'supplier_id',
            'label' => trans('Supplier'),
            'type' => 'number',
            'searchable' => false,
            'wrapper' => function ($row) {
                if ($row->supplier_id != null) {

                    echo '<a href="'. route('b2b_marketplace.admin.suppliers.edit', $row->supplier_id) .'" style="font-size: 14px;margin-left: 25px;" >
                    '. $row->supplier_id .'
                    </a>';
                } else {
                    echo '<h3 style="margin-left: 30%;"> -- </h3>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'file',
            'label' => trans('b2b_marketplace::app.shop.rfq.attachment'),
            'type' => 'number',
            'searchable' => false,
            'wrapper' => function ($file) {
                if ($file->file != "") {

                    echo '<a href="'. route('b2b_marketplace.admin.supplier.quote.attachment.download', $file->id) .'" style="font-size: 14px;" >
                    '. trans('b2b_marketplace::app.shop.rfq.download') .'
                    </a>';
                } else {
                    echo '<h3 style="margin-left: 30%;"> -- </h3>';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'total_product',
            'label' => trans('b2b_marketplace::app.admin.rfq.total-product'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'total_price',
            'label' => trans('b2b_marketplace::app.admin.rfq.total-price'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false,
            'wrapper' => function ($row) {
                return $row->total_product * $row->price;
            }
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('b2b_marketplace::app.admin.rfq.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                echo $row->product_name;
            }
        ]);

        $this->addColumn([
            'index' => 'image',
            'label' => trans('b2b_marketplace::app.shop.rfq.sample-image'),
            'type' => 'number',
            'searchable' => false,
            'wrapper' => function ($image) {
                if ($image->image == "") {

                    echo '<a target="new" href="' . bagisto_asset('themes/default/assets/images/Default-Product-Image.png') . '">
                    <img src="' .  bagisto_asset('themes/default/assets/images/Default-Product-Image.png') . '" alt="Sample Images" href="' . bagisto_asset('themes/default/assets/images/Default-Product-Image.png') . '" target="new" height="35px" width="65px" style="cursor:pointer margin-left: 4px;">
                    </a>';
                } else {
                    echo '<a target="new" href="' . \Storage::url($image->image) . '">
                    <img src="' . \Storage::url($image->image) . '" alt="Sample Images" href="' . \Storage::url($image->image) . '" target="new" height="35px" width="65px" style="cursor:pointer margin-left: 4px;">
                    </a> <div><a href="'. route('b2b_marketplace.admin.supplier.quote.images.download', $image->id) .'" style="font-size: 13px;">
                    '. trans('b2b_marketplace::app.shop.rfq.download-all') .'
                    </a></div>';
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
    }
}