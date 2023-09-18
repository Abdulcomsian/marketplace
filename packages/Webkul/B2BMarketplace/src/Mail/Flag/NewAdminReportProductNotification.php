<?php

namespace Webkul\B2BMarketplace\Mail\Flag;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Admin  report product Mail class
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewAdminReportProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The supplier instance.
     *
     * @var Supplier
     */
    public $supplier;

    /**
     * admin instance
     *
     * @var Admin
     */
    public $admin;

    /**
     * admin instance
     *
     * @var Product
     */
    public $product;

    /**
     * data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new report instance.
     *
     * @param Supplier $supplier
     * @param Admin    $admin
     * @param Product  $product
     * @return void
     */
    public function __construct($supplier, $admin, $product, $data)
    {
        $this->supplier = $supplier;

        $this->admin = $admin;

        $this->product = $product;

        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->supplier ? trans('b2b_marketplace::app.mail.shop.supplier.report-product-toadmin.subject') : trans('b2b_marketplace::app.mail.shop.supplier.report-product.subject');

        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->admin->email,$this->admin->name)
            ->replyTo($this->data['email'], $this->data['name'])
            ->subject($subject)
            ->view('b2b_marketplace::emails.flag.product-flag-admin', ['name' => $this->admin->name]);
    }
}
