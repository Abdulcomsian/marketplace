<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Contact Supplier Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ContactSupplierNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The supplier instance.
     *
     * @var Supplier
     */
    public $supplier;

    /**
     * Contains form data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param Supplier $supplier
     * @param array  $data
     * @return void
     */
    public function __construct($supplier, $data)
    {
        $this->supplier = $supplier;

        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
        ->to($this->supplier->email, $this->supplier->first_name . ' ' . $this->supplier->last_name)
                ->replyTo($this->data['email'], $this->data['name'])
                ->subject(trans('b2b_marketplace::app.shop.supplier.mails.contact-supplier.subject', ['subject' => $this->data['subject']]))
                ->view('b2b_marketplace::emails.contact-supplier', [
                    'supplierName' => $this->supplier->first_name . ' ' . $this->supplier->last_name,
                    'query' => $this->data['query']
                ]);
    }
}
