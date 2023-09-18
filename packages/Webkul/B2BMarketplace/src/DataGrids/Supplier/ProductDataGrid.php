<?php

namespace Webkul\B2BMarketplace\DataGrids\Supplier;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Product Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'product_id';

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
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
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

        $queryBuilder =  DB::table('product_flat')
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->join('b2b_marketplace_products', 'product_flat.product_id', '=', 'b2b_marketplace_products.product_id')
            ->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_products.supplier_id', '=', 'b2b_marketplace_suppliers.id')

            ->addSelect('b2b_marketplace_products.id as marketplace_product_id',
             'product_flat.product_id',
              'product_flat.sku', 'product_flat.url_key','product_flat.name', 'product_flat.product_number', 'b2b_marketplace_products.price', 'product_flat.price as product_flat_price', 'b2b_marketplace_products.is_owner', 'b2b_marketplace_products.is_approved',  DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as seller_name'))
            ->where('b2b_marketplace_products.supplier_id', $supplierId)
            ->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale())
            ->distinct();

        $queryBuilder = $queryBuilder->leftJoin('product_inventories', function ($qb) {

            $supplierId = auth()->guard('supplier')->user()->id;

            $qb->on('product_flat.product_id', 'product_inventories.product_id')
                ->where('product_inventories.vendor_id', '=', $supplierId);
        });

        $queryBuilder = $queryBuilder->leftJoin('b2b_marketplace_product_flags', function ($qb) {

            $qb->on('product_flat.product_id', 'b2b_marketplace_product_flags.product_id');
        });

        $queryBuilder
            ->groupBy('product_flat.product_id')
            ->addSelect(DB::raw('SUM(product_inventories.qty) as quantity'));

        $queryBuilder
            ->groupBy('product_flat.product_id')
            ->addSelect(DB::raw('count(b2b_marketplace_product_flags.id) as totalFlags'));

        $this->addFilter('sku', 'product_flat.sku');
        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('price', 'b2b_marketplace_products.price');
        $this->addFilter('is_approved', 'b2b_marketplace_products.is_approved');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'totalFlags',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.flags'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('b2b_marketplace::app.shop.supplier.account.products.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if (! empty($row->sku)) {
                    return "<a href='" . route('shop.productOrCategory.index', $row->sku) . "' target='_blank'>" . $row->name . "</a>";
                }
                return $row->name;
            },
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.quantity'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'filterable' => false,
            'wrapper' => function ($row) {
                if (is_null($row->quantity)) {
                    return 0;
                } else {
                    return $row->quantity;
                }
            }
        ]);

        $this->addColumn([
            'index' => 'is_approved',
            'label' => trans('b2b_marketplace::app.shop.supplier.account.products.is-approved'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'closure' => true,
            'wrapper'    => function ($row) {
                if ($row->is_approved == 1) {
                    return '<span class="badge badge-md badge-success">' . trans('b2b_marketplace::app.shop.supplier.account.products.approved') . '</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">' . trans('b2b_marketplace::app.shop.supplier.account.products.unapproved') . '</span>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title' => trans('b2b_marketplace::app.supplier.account.products.edit'),
            'type' => 'Edit',
            'method' => 'GET',
            'route' => 'b2b_marketplace.supplier.catalog.products.edit',
            'icon' => 'icon pencil-lg-icon'
        ], true);

        $this->addAction([
            'title' => trans('b2b_marketplace::app.supplier.account.products.delete'),
            'method'       => 'POST',
            'route'        => 'b2b_marketplace.supplier.catalog.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ], true);

        $this->addAction([
            'method' => 'GET',
            'route'  => 'supplier.catalog.products.copy',
            'icon'   => 'icon copy-icon',
            'title' => trans('b2b_marketplace::app.supplier.account.products.copy')
        ], true);
    }

    public function prepareMassActions()
    {

        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('b2b_marketplace::app.supplier.account.products.delete'),
            'action' => route('b2b_marketplace.supplier.products.massdelete'),
            'method' => 'POST',
            'title' => trans('b2b_marketplace::app.supplier.account.products.delete')
        ], true);
    }
}
