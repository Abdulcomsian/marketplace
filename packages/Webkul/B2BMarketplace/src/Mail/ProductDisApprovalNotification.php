<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Product Approval Mail class
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDisApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Product instance.
     *
     * @var Product
     */
    public $supplierProduct;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplierProduct)
    {
        $this->supplierProduct = $supplierProduct;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->supplierProduct->supplier->email, $this->supplierProduct->supplier->first_name . ' ' . $this->supplierProduct->supplier->last_name)
                ->subject(trans('b2b_marketplace::app.mail.product.subject-disapprove'))
                ->view('b2b_marketplace::emails.product.dis-approval');
    }
}
