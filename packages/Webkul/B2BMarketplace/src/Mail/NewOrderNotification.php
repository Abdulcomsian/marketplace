<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Order Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $supplierOrder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplierOrder)
    {
        $this->supplierOrder = $supplierOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->supplierOrder->seller->email, $this->supplierOrder->seller->getNameAttribute())
                ->subject(trans('b2b_marketplace::app.mail.sales.order.subject'))
                ->view('b2b_marketplace::emails.sales.new-order');
    }
}
