<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales;

use Illuminate\Http\Request;
use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\Sales\Repositories\OrderRepository as BaseOrderRepository;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\Sales\Repositories\ShipmentRepository as BaseShipmentRepository;
use Webkul\Sales\Repositories\OrderItemRepository as BaseOrderItemRepository;
use Webkul\B2BMarketplace\Repositories\ShipmentRepository as ShipmentRepository;

/**
 * Shipment controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentController extends Controller
{
    /*
    * Contains route related configuration
    *
    * @var object
    */
   protected $_config;

   /**
    * Create a new controller instance.
    *
    * @param  \Webkul\B2BMarketplace\Repositories\OrderRepository    $orderRepository
    * @param  \Webkul\B2BMarketplace\Repositories\SupplierRepository   $supplierRepository
    * @param  \Webkul\Sales\Repositories\OrderItemRepository      $baseOrderItemRepository
    * @param  \Webkul\B2BMarketplace\Repositories\ShipmentRepository $shipmentRepository
    * @param  \Webkul\Sales\Repositories\ShipmentRepository as BaseShipmentRepository $baseShipmentRepository
    * @param  \Webkul\Sales\Repositories\OrderRepository  $baseOrderRepository
    * @return void
    */
    public function __construct(
       protected OrderRepository $orderRepository,
       protected SupplierRepository $supplierRepository,
       protected BaseOrderItemRepository $baseOrderItemRepository,
       protected ShipmentRepository $shipmentRepository,
       protected BaseShipmentRepository $baseShipmentRepository,
       protected BaseOrderRepository $baseOrderRepository
    )
    {
       $this->_config = request('_config');
    }

    /**
    * Show the view for the specified resource.
    *
    * @param  int  $orderId
    * @return \Illuminate\View\View
    */
    public function index()
    {
        return view($this->_config(['view']));
    }

   /**
    * Show the view for the specified resource.
    *
    * @param  int  $orderId
    * @return \Illuminate\View\View
    */
    public function create($orderId)
    {
        if (! core()->getConfigData('b2b_marketplace.settings.general.can_create_shipment')) {

            abort(404);
        }

        $supplierId = auth()->guard('supplier')->user()->id;

        $supplierOrder = $this->orderRepository->findOneWhere([
            'order_id' => $orderId,
            'supplier_id' => $supplierId
        ]);

        $order = $this->baseOrderRepository->findOrFail($orderId);

        if (! $order->channel || !$order->canShip()) {
            session()->flash('error', trans('admin::app.sales.shipments.creation-error'));

            return redirect()->back();
        }

        return view($this->_config['view'], compact('supplierOrder', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $orderId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $orderId)
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $supplierOrder = $this->orderRepository->findOneWhere([
            'order_id' => $orderId,
            'supplier_id' => $supplierId
        ]);

        if (! $supplierOrder->canShip()) {
            session()->flash('error', trans('b2b_marketplace::app.supplier.account.sales.shipment.not-allowed'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'shipment.carrier_title' => 'required',
            'shipment.track_number' => 'required',
            'shipment.source' => 'required',
            'shipment.items.*.*' => 'required|numeric|min:0',
        ]);

        $data = array_merge(request()->all(), [
                'vendor_id' => $supplierOrder->supplier_id,
                'supplier' => Null
            ]);

        if (! $this->isInventoryValidate($data)) {
            session()->flash('error', trans('b2b_marketplace::app.supplier.account.sales.shipment.qty-invalid'));

            return redirect()->back();
        }


        $this->baseShipmentRepository->create(array_merge($data, [
            'order_id' => $orderId
        ]));

        session()->flash('success', trans('b2b_marketplace::app.supplier.account.sales.shipment.create-success'));

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Checks if requested quantity available or not
     *
     * @param array $data
     * @return boolean
     */
    public function isInventoryValidate(&$data)
    {
        $valid = false;

        foreach ($data['shipment']['items'] as $itemId => $inventorySource) {
            if ($qty = $inventorySource[$data['shipment']['source']]) {
                $orderItem = $this->baseOrderItemRepository->find($itemId);

                $product = ($orderItem->type == 'configurable')
                        ? $orderItem->child->product
                        : $orderItem->product;

                $inventory = $product->inventories()
                        ->where('inventory_source_id', $data['shipment']['source'])
                        ->where('vendor_id', $data['vendor_id'])
                        ->first();

                if ($orderItem->qty_to_ship < $qty || $inventory->qty < $qty) {
                    return false;
                }

                $valid = true;
            } else {
                unset($data['shipment']['items'][$itemId]);
            }
        }

        return $valid;
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $baseShipment = $this->baseShipmentRepository->findOrFail($id);

        $shipment = $this->shipmentRepository->findOneWhere(['shipment_id' => $id]);

        return view($this->_config['view'], compact('shipment', 'baseShipment'));
    }
}
