<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Shipment Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewShipmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Shipment instance.
     *
     * @var Shipment
     */
    public $supplierShipment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplierShipment)
    {
        $this->supplierShipment = $supplierShipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->supplierShipment->order->supplier->customer->email, $this->supplierShipment->order->supplier->customer->name)
                ->subject(trans('b2b_marketplace::app.mail.sales.shipment.subject'))
                ->view('b2b_marketplace::emails.sales.new-shipment');
    }
}
