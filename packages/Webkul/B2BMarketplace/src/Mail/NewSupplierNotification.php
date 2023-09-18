<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Supplier registration mail to Admin
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewSupplierNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The supplier instance.
     *
     * @var Supplier
     */
    public $supplier;

    /**
     * The admin instance.
     *
     * @var Admin
     */
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplier, $admin)
    {
        $this->supplier = $supplier;

        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->admin->email)
                ->subject(trans('b2b_marketplace::app.mail.supplier.regisration.subject'))
                ->view('b2b_marketplace::emails.supplier.register');
    }
}