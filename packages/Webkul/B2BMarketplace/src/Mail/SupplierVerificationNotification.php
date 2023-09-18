<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Supplier verification Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierVerificationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $supplier;

    public function __construct($supplier) {
        $this->supplier = $supplier;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->supplier->email)
            ->from(core()->getSenderEmailDetails()['email'])
            ->subject(trans('b2b_marketplace::app.mail.supplier.verification.subject'))
            ->view('b2b_marketplace::emails.supplier.verification-email')->with('data', ['email' => $this->supplier->email, 'token' => $this->supplier->token]);
    }
}