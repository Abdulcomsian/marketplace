<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Supplier Order Data Grid class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'order_id';

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
     * Supplier object
     *
     * @var Object
     */
    protected $supplier;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2bMarketplace\Repositories\SupplierRepository $supplierRepository
     * @return void
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        parent::__construct();

        $this->supplierRepository = $supplierRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_orders')
                ->leftJoin('orders', 'b2b_marketplace_orders.order_id', '=', 'orders.id')
                ->leftJoin('b2b_marketplace_transactions', 'b2b_marketplace_orders.id', '=', 'b2b_marketplace_transactions.b2b_marketplace_order_id')
                ->select('orders.id', 'b2b_marketplace_orders.order_id', 'b2b_marketplace_orders.base_sub_total', 'b2b_marketplace_orders.base_grand_total', 'b2b_marketplace_orders.base_commission', 'b2b_marketplace_orders.base_supplier_total', 'b2b_marketplace_orders.base_supplier_total_invoiced', 'b2b_marketplace_orders.created_at', 'b2b_marketplace_orders.status', 'is_withdrawal_requested', 'supplier_payout_status', 'b2b_marketplace_orders.supplier_id', 'b2b_marketplace_orders.base_discount_amount')
                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name'))
                ->addSelect(DB::raw('SUM(b2b_marketplace_transactions.base_total) as total_paid'))
                ->groupBy('b2b_marketplace_orders.id');

        if (request()->id) {
            $this->supplier = $this->supplierRepository->find(request()->id);

            $queryBuilder->where('b2b_marketplace_orders.supplier_id', $this->supplier->id);
        } else {
            $queryBuilder->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_orders.supplier_id', '=', 'b2b_marketplace_suppliers.id')
                ->addSelect(DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name'));

            $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
        }

        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));
        $this->addFilter('base_grand_total', 'b2b_marketplace_orders.base_grand_total');
        $this->addFilter('status', 'b2b_marketplace_orders.status');
        $this->addFilter('created_at', 'b2b_marketplace_orders.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'order_id',
            'label' => trans('b2b_marketplace::app.admin.orders.order-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('b2b_marketplace::app.admin.orders.grand-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('b2b_marketplace::app.admin.orders.billed-to'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('b2b_marketplace::app.admin.orders.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'closure' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                if ($row->status == 'processing')
                    return '<span class="badge badge-md badge-success">' . trans("b2b_marketplace::app.admin.orders.processing") . '</span>';
                else if ($row->status == 'completed')
                    return '<span class="badge badge-md badge-success">' . trans("b2b_marketplace::app.admin.orders.completed") . '</span>';
                else if ($row->status == "canceled")
                    return '<span class="badge badge-md badge-danger">' . trans("b2b_marketplace::app.admin.orders.canceled") . '</span>';
                else if ($row->status == "closed")
                    return '<span class="badge badge-md badge-info">' . trans("b2b_marketplace::app.admin.orders.closed") . '</span>';
                else if ($row->status == "pending")
                    return '<span class="badge badge-md badge-warning">' . trans("b2b_marketplace::app.admin.orders.pending") . '</span>';
                else if ($row->status == "pending_payment")
                    return '<span class="badge badge-md badge-warning">' . trans("b2b_marketplace::app.admin.orders.pending-payment") . '</span>';
                else if ($row->status == "fraud")
                    return '<span class="badge badge-md badge-danger">' . trans("b2b_marketplace::app.admin.orders.fraud") . '</span>';
            }
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('b2b_marketplace::app.admin.orders.order-date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);

        if (!request()->id) {
            $this->addColumn([
                'index' => 'supplier_name',
                'label' => trans('b2b_marketplace::app.admin.orders.supplier-name'),
                'type' => 'string',
                'sortable' => true,
                'searchable' => true,
                'filterable' => true
            ]);
        }

        $this->addColumn([
            'index' => 'base_commission',
            'label' => trans('b2b_marketplace::app.admin.orders.commission'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_discount_amount',
            'label' => trans('b2b_marketplace::app.admin.orders.discount'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_supplier_total',
            'label' => trans('b2b_marketplace::app.admin.orders.supplier-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'base_supplier_total_invoiced',
            'label' => trans('b2b_marketplace::app.admin.orders.supplier-total-invoiced'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'total_paid',
            'label' => trans('b2b_marketplace::app.admin.orders.total-paid'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'base_remaining_total',
            'label' => trans('b2b_marketplace::app.admin.orders.remaining-total'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'wrapper' => function($row) {
                if (! is_null($row->total_paid))
                    return core()->formatBasePrice($row->base_supplier_total_invoiced - $row->total_paid);

                return core()->formatBasePrice($row->base_supplier_total_invoiced);
            }
        ]);

        $this->addColumn([
            'index' => 'pay',
            'label' => trans('b2b_marketplace::app.admin.orders.pay'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'closure' => true,
            'wrapper' => function($row) {
                if ($row->supplier_payout_status == 'paid') {
                    return trans('b2b_marketplace::app.admin.orders.already-paid');
                } else if ($row->supplier_payout_status == 'refunded') {
                    return trans('b2b_marketplace::app.admin.orders.refunded');
                } else {
                    $remaining = ! is_null($row->total_paid) ? $row->base_supplier_total_invoiced - $row->total_paid : $row->base_supplier_total_invoiced;

                    if ((float) $remaining) {
                        return '<button class="btn btn-sm btn-primary pay-btn" data-id="' . $row->id . '" supplier-id="' . $row->supplier_id .'">' . trans('b2b_marketplace::app.admin.orders.pay') . '</button>';
                    } else {
                        return trans('b2b_marketplace::app.admin.orders.invoice-pending');
                    }
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.orders.view',
            'icon' => 'icon eye-icon',
            'method' => 'GET',
            'title' => trans('b2b_marketplace::app.admin.orders.view')
        ]);
    }
}