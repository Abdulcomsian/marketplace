<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Supplier Approval Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The supplier instance.
     *
     * @var Supplier
     */
    public $supplier;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->supplier->email, $this->supplier->first_name, $this->supplier->last_name)
                ->subject(trans('b2b_marketplace::app.mail.supplier.approval.subject'))
                ->view('b2b_marketplace::emails.supplier.approval');
    }
}
