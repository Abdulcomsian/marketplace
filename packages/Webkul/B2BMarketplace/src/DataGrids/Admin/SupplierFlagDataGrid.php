<?php

namespace Webkul\B2BMarketplace\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Supplier Flag Data Grid class
 *
 * @author Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierFlagDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    protected $enableFilterMap = false;

    /**
     * supplierRepository object
     *
     * @var Object
     */
    protected $supplierRepository;

    /**
     * supplier object
     *
     * @var Object
     */
    protected $supplier;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplierRepository
     * @return void
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        parent::__construct();

        $this->supplierRepository = $supplierRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('b2b_marketplace_supplier_flags')
            ->leftJoin('b2b_marketplace_suppliers', 'b2b_marketplace_suppliers.id', '=', 'b2b_marketplace_supplier_flags.supplier_id')
            ->select(
                'b2b_marketplace_supplier_flags.id',
                'b2b_marketplace_supplier_flags.reason',
                'b2b_marketplace_supplier_flags.name',
                'b2b_marketplace_supplier_flags.email',
                DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name) as supplier_name'),
                'b2b_marketplace_suppliers.id as supplierId'
            );


        $this->addFilter('id', 'b2b_marketplace_supplier_flags.id');
        $this->addFilter('reason', 'b2b_marketplace_supplier_flags.reason' );
        $this->addFilter('email', 'b2b_marketplace_supplier_flags.email' );
        $this->addFilter('name', 'b2b_marketplace_supplier_flags.name' );
        $this->addFilter('created_at', 'b2b_marketplace_supplier_flags.created_at');
        $this->addFilter('supplier_name', DB::raw('CONCAT(b2b_marketplace_suppliers.first_name, " ", b2b_marketplace_suppliers.last_name)'));
        $this->addFilter('supplierId', 'b2b_marketplace_suppliers.id as supplierId');

        if (isset(request()->id) && gettype(request()->id) != 'array') {

            $this->supplier = $this->supplierRepository->findOneWhere(['id'=>request()->id]);

            if(isset($this->supplier) && $this->supplier != null){
                $queryBuilder->where('b2b_marketplace_supplier_flags.supplier_id', $this->supplier->id);
            }
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
            'index' => 'supplier_name',
            'label' => trans('b2b_marketplace::app.admin.suppliers.supplier-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function($row) {
                return '<a href="' . route('b2b_marketplace.admin.suppliers.edit', $row->supplierId) . '">' . $row->supplier_name . '</a>';
            }
        ]);

    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => trans('b2b_marketplace::app.admin.suppliers.delete'),
            'action' => route('b2b_marketplace.admin.supplier-flag.flag.mass-delete'),
            'method' => 'POST',
            'title' => 'delete'
        ]);
    }
}