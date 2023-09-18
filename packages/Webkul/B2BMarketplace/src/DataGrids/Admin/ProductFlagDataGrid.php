<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Product\Repositories\ProductRepository;

/**
 * Product Flag Data Grid class
 *
 * @author Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductFlagDataGrid extends DataGrid
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
    protected $sortOrder = 'desc';

    /**
     * productRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();

        $this->productRepository = $productRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_product_flags')
            ->leftJoin('products', 'products.id', '=', 'b2b_marketplace_product_flags.product_id')
            ->leftJoin('product_flat', 'product_flat.product_id', '=', 'products.id')
            ->where('product_flat.locale',app()->getLocale())
            ->select('b2b_marketplace_product_flags.id',
                'b2b_marketplace_product_flags.reason',
                'b2b_marketplace_product_flags.name',
                'b2b_marketplace_product_flags.email',
                'product_flat.name as product_name',
                'products.id as productId'
            );

        $this->addFilter('id', 'b2b_marketplace_product_flags.id');
        $this->addFilter('reason', 'b2b_marketplace_product_flags.reason' );
        $this->addFilter('email', 'b2b_marketplace_product_flags.email' );
        $this->addFilter('name', 'b2b_marketplace_product_flags.name' );
        $this->addFilter('created_at', 'b2b_marketplace_product_flags.created_at');
        $this->addFilter('product_name', 'product_flat.name');


        if (isset(request()->id)) {
            $productId = request()->id;
            $queryBuilder->where('b2b_marketplace_product_flags.product_id', $productId);
        }

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
            'index' => 'name',
            'label' => trans('b2b_marketplace::app.admin.flag.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('b2b_marketplace::app.admin.flag.email'),
            'type' => 'string',
            'searchable' => true,
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
            'index' => 'product_name',
            'label' => trans('b2b_marketplace::app.admin.products.flag.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function($row) {
                return '<a href="' . route('admin.catalog.products.edit', $row->productId) . '">' . $row->product_name . '</a>';
            }
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('b2b_marketplace::app.admin.suppliers.delete'),
            'action' => route('b2b_marketplace.admin.product-flag.flag.mass-delete'),
            'method' => 'POST',
            'title' => 'delete'
        ]);
    }
}