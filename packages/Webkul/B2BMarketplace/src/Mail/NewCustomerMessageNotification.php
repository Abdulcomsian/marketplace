<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * New Order Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewCustomerMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $message;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $thread;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $thread)
    {
        $this->message = $message;

        $this->thread = $thread;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
        ->to($this->thread->supplier->email, $this->thread->supplier->first_name . ' ' . $this->thread->supplier->last_name)
            ->subject(trans('b2b_marketplace::app.mail.message.subject'))
            ->view('b2b_marketplace::emails.message.customer-msg',[
                'supplierName' => $this->thread->supplier->first_name . ' ' . $this->thread->supplier->last_name,
                'customerName' => $this->thread->customer->first_name . ' ' . $this->thread->customer->last_name
            ]);
    }
}
