<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales;

use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;

/**
 * Order controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SupplierRepository object
     *
     * @var mixed
     */
    protected $supplierRepository;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        OrderRepository $orderRepository
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->orderRepository = $orderRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $supplierOrder = $this->orderRepository->findOneWhere([
            'order_id' => $id,
            'supplier_id' => $supplierId
        ]);

        return view($this->_config['view'], compact('supplierOrder'));
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        if (! core()->getConfigData('b2b_marketplace.settings.general.can_cancel_order'))
            return redirect()->back();

        $result = $this->orderRepository->supplierCancelOrder($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }
}
