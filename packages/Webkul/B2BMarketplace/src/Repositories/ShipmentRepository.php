<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;

/**
 * Supplier Shipment Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentRepository extends Repository
{
    /**
     * SupplierRepository object
     *
     * @var Object
     */
    protected $supplierRepository;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $orderRepository;

    /**
     * ShipmentItemRepository object
     *
     * @var Object
     */
    protected $shipmentItemRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository      $productRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository        $orderRepository
     * @param  Webkul\B2BMarketplace\Repositories\ShipmentItemRepository $shipmentItemRepository
     * @param  Illuminate\Container\Container                            $app
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        ShipmentItemRepository $shipmentItemRepository,
        App $app
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->productRepository = $productRepository;

        $this->orderRepository = $orderRepository;

        $this->shipmentItemRepository = $shipmentItemRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Shipment';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $shipment = $data['shipment'];

        Event::dispatch('b2b_marketplace.sales.shipment.save.before', $data);

        $supplierShipments = [];

        foreach ($shipment->items()->get() as $item) {

            if (isset($item->additional['supplier_info'])) {

                $supplier = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);

                $suppliers[] = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);
            } else {
                $supplier = $this->productRepository->getSupplierByProductId($item->product_id);

                $suppliers[] = $this->productRepository->getSupplierByProductId($item->product_id);
            }

            if (! $supplier)
                continue;

            $supplierOrder = $this->orderRepository->findOneWhere([
                'order_id' => $shipment->order->id,
                'supplier_id' => $supplier->id,
            ]);

            if (! $supplierOrder)
                continue;

            $supplierShipment = $this->findOneWhere([
                'shipment_id' => $shipment->id,
                'b2b_marketplace_order_id' => $supplierOrder->id,
            ]);

            if (! $supplierShipment) {

                $supplierShipments[] = $supplierShipment = parent::create([
                    'total_qty' => $item->qty,
                    'shipment_id' => $shipment->id,
                    'b2b_marketplace_order_id' => $supplierOrder->id,
                ]);
            } else {
                $supplierShipment->total_qty += $item->qty;

                $supplierShipment->save();
            }

            $supplierShipmentItem = $this->shipmentItemRepository->create([
                'b2b_marketplace_shipment_id' => $supplierShipment->id,
                'b2b_shipment_item_id' => $item->id,
            ]);


            $product = ($item->order_item->type == 'configurable')
                    ? $item->order_item->child->product
                    : $item->order_item->product;

            $this->shipmentItemRepository->updateProductInventory([
                'shipment' => $shipment,
                'product' => $product,
                'qty' => $item->qty,
                'vendor_id' => $supplier->id
            ]);
        }

        foreach ($suppliers as $supplier) {
            if ($supplier) {
                $supplierOrders = $this->orderRepository->findWhere([
                    'order_id' => $shipment->order->id,
                    'supplier_id' => $supplier->id]
                );

                foreach ($supplierOrders as $supplierOrder) {
                    $this->orderRepository->updateOrderStatus($supplierOrder);
                }
            }
        }

        foreach ($supplierShipments as $supplierShipment) {
            Event::dispatch('b2b_marketplace.sales.shipment.save.after', $supplierShipment);
        }
    }
}