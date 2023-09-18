<?php

namespace Webkul\B2BMarketplace\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSupplierNotificationFromAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The customer instance.
     *
     * @var  \Webkul\Customer\Contracts\Customer
     */
    public $supplier;

    /**
     * The password instance.
     *
     * @var string
     */
    public $password;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\B2BMarketplace\Contracts\Supplier  $supplier
     * @param  string  $password
     * @return void
     */
    public function __construct(
        $supplier,
        $password
    )
    {
        $this->supplier = $supplier;

        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                    ->to($this->supplier->email)
                    ->subject(trans('b2b_marketplace::app.mail.supplier.new.subject'))
                    ->view('b2b_marketplace::emails.supplier.new-supplier')->with(['customer' => $this->supplier, 'password' => $this->password]);
    }
}