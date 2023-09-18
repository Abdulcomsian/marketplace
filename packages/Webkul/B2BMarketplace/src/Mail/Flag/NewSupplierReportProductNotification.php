<?php

namespace Webkul\B2BMarketplace\Mail\Flag;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


/**
 * Supplier report product Mail class
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewSupplierReportProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Supplier instance.
     *
     * @var Supplier
     */
    public $supplier;

    /**
     * The Product instance.
     *
     * @var Product
     */
    public $product;

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
    public function __construct($supplier, $product,$data)
    {
        $this->supplier = $supplier;

        $this->data = $data;

        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->supplier->email,$this->supplier->name)
            ->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->replyTo($this->data['email'], $this->data['name'])
            ->subject(trans('b2b_marketplace::app.mail.shop.supplier.report-product.subject'))
            ->view('b2b_marketplace::emails.flag.product-flag-supplier', ['name' => $this->supplier->name]);
    }
}
