<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Invoice Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewInvoiceNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Invoice instance.
     *
     * @var Invoice
     */
    public $supplierInvoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplierInvoice)
    {
        $this->supplierInvoice = $supplierInvoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->supplierInvoice->invoice->order->customer_email, $this->supplierInvoice->invoice->order->customer_full_name)
                ->subject(trans('b2b_marketplace::app.mail.sales.invoice.subject'))
                ->view('b2b_marketplace::emails.sales.new-invoice');
    }
}
