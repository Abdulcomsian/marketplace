<?php

namespace Webkul\B2BMarketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\B2BMarketplace\Repositories\InvoiceRepository;

/**
 * Invoice event handler
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Invoice
{
    /**
     * InvoiceRepository object
     *
     * @var Product
    */
    protected $invoice;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\InvoiceRepository $invoice
     * @return void
     */
    public function __construct(
        InvoiceRepository $invoice
    )
    {
        $this->invoice = $invoice;
    }

    /**
     * After sales invoice creation, creater marketplace invoice
     *
     * @param mixed $invoice
     */
    public function afterInvoice($invoice)
    {
        $this->invoice->create(['invoice' => $invoice]);
    }
}