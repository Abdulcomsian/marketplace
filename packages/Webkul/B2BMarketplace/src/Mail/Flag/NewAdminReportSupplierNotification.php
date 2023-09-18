<?php

namespace Webkul\B2BMarketplace\Mail\Flag;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


/**
 * Admin report supplier Mail class
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewAdminReportSupplierNotification extends Mailable
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
     * Admin instance
     *
     * @var Admin
     */
    public $admin;

    /**
     * Create a new message instance.
     *
     * @param  $supplier
     * @param array  $data
     * @return void
     */
    public function __construct($supplier, $admin, $data)
    {
        $this->supplier = $supplier;

        $this->data = $data;

        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->admin->email,$this->admin->name)
            ->replyTo($this->data['email'], $this->data['name'])
            ->subject(trans('b2b_marketplace::app.mail.shop.supplier.report-supplier.subject'))
            ->view('b2b_marketplace::emails.flag.supplier-flag');
    }
}
