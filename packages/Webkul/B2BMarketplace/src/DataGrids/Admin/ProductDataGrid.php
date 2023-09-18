<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Product Data Grid
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'b2b_marketplace_product_id';

    /**
     *
     * @var integer
     */
    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_flat')
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->join('b2b_marketplace_products', 'product_flat.product_id', '=', 'b2b_marketplace_products.product_id')
                ->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_products.supplier_id', '=', 'b2b_marketplace_suppliers.id')

                ->addSelect('b2b_marketplace_products.id as b2b_marketplace_product_id', 'product_flat.product_id', 'product_flat.sku', 'product_flat.name', 'b2b_marketplace_products.price', 'product_flat.price as   product_flat_price', 'b2b_marketplace_products.is_owner', 'b2b_marketplace_products.is_approved',
                DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name'))
                ->where('channel', core()->getCurrentChannelCode())
                ->where('locale', app()->getLocale());

        $queryBuilder = $queryBuilder->leftJoin('product_inventories', function($qb) {
            $qb->on('product_flat.product_id', 'product_inventories.product_id')
                ->where('product_inventories.vendor_id', '<>', 'b2b_marketplace_suppliers.id');
        });

        $queryBuilder
            ->groupBy('b2b_marketplace_products.id')
            ->addSelect(DB::raw('SUM(product_inventories.qty) as quantity'));

        $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
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
            'label' => trans('b2b_marketplace::app.admin.products.product-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.admin.suppliers.supplier-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('b2b_marketplace::app.admin.products.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
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
            'label' => trans('b2b_marketplace::app.admin.products.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->is_owner == 1)
                    return $row->product_flat_price;
                else
                    return $row->price;
            }
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('b2b_marketplace::app.admin.products.quantity'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
            'filterable' => false,
            'wrapper' => function($row) {
                if ($row->quantity == null)
                    return 0;
                else
                    return $row->quantity;
            }
        ]);

        $this->addColumn([
            'index' => 'is_approved',
            'label' => trans('b2b_marketplace::app.admin.products.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'closure' => true,
            'wrapper'    => function ($row) {
                if ($row->is_approved == 1) {
                    return '<span class="badge badge-md badge-success">' . trans('b2b_marketplace::app.admin.products.approved') . '</span>';
                } else {
                    return '<span class="badge badge-md badge-danger">' . trans('b2b_marketplace::app.admin.products.un-approved') . '</span>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'b2b_marketplace.admin.products.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('b2b_marketplace::app.admin.products.delete'),
            'action' => route('b2b_marketplace.admin.products.mass-delete'),
            'title' => trans('b2b_marketplace::app.admin.products.delete'),
            'method' => 'POST'
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => trans('b2b_marketplace::app.admin.products.update'),
            'action' => route('b2b_marketplace.admin.products.mass-update'),
            'method' => 'POST',
            'title' => trans('b2b_marketplace::app.admin.products.update'),
            'options' => [
                trans('b2b_marketplace::app.admin.suppliers.approve') => 1,
                trans('b2b_marketplace::app.admin.suppliers.unapprove') => 0
            ]
        ]);
    }
}