<?php

namespace Webkul\B2BMarketplace\Listeners;

use Webkul\B2BMarketplace\Repositories\ShipmentRepository;

/**
 * Shipment event handler
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Shipment
{
    /**
     * ShipmentRepository object
     *
     * @var Product
    */
    protected $shipment;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\ShipmentRepository $shipment
     * @return void
     */
    public function __construct(
        ShipmentRepository $shipment
    )
    {
        $this->shipment = $shipment;
    }

    /**
     * creater Order Shippment
     *
     * @param mixed $shipment
     */
    public function afterShipment($shipment)
    {
        $this->shipment->create(['shipment' => $shipment]);
    }
}