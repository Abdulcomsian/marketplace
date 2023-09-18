<?php

namespace Webkul\B2BMarketplace\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\B2BMarketplace\Mail\NewOrderNotification;
use Webkul\B2BMarketplace\Mail\NewInvoiceNotification;
use Webkul\B2BMarketplace\Mail\NewShipmentNotification;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository;

/**
 * Order event handler
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * OrderRepository object
     *
     * @var Product
    */
    protected $order;

    /**
     * CartItemRepository object
     *
     * @var mixed
     */
    protected $cartItem;

    /**
     * SupplierQuoteItemRepository object
     *
     * @var mixed
     */
    protected $supplierQuoteItemRepository;

    /**
     * CustomerQuoteItemRepository object
     *
     * @var mixed
     */
    protected $customerQuoteItemRepository;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository $order
     * @param  Webkul\Checkout\Repositories\CartItemRepository $cartItem
     * @return void
     */
    public function __construct(
        OrderRepository $order,
        CartItemRepository $cartItem,
        SupplierQuoteItemRepository $supplierQuoteItemRepository,
        CustomerQuoteItemRepository $customerQuoteItemRepository
    )
    {
        $this->order = $order;
        $this->cartItem = $cartItem;
        $this->supplierQuoteItemRepository = $supplierQuoteItemRepository;
        $this->customerQuoteItemRepository = $customerQuoteItemRepository;
    }

    /**
     * After sales order creation, add entry to marketplace order table
     *
     * @param mixed $order
     */
    public function afterPlaceOrder($order)
    {
        foreach ($order['items'] as $orderItem) {

            if (isset($orderItem->additional['quote_id'])) {
                $supplierQuote = $this->supplierQuoteItemRepository->findOneWhere(['id' => $orderItem->additional['quote_id']]);

                if (isset($supplierQuote) && $supplierQuote != null) {
                    $supplierQuote->update(['is_ordered' => 1]);

                    $customerQuote = $this->customerQuoteItemRepository->findOneWhere(['quote_id' => $supplierQuote->quote_id, 'product_id' => $supplierQuote->product_id]);

                    if (isset($customerQuote) && $customerQuote != null) {
                        $customerQuote->update(['quote_status' => 'Completed']);
                    }
                }
            }
        }

        $this->order->create(['order' => $order]);
    }

    /**
     * After sales order cancellation
     *
     * @param mixed $order
     */
    public function afterOrderCancel($order)
    {
      $this->order->cancel(['order' => $order]);
    }

        /**
     * @param mixed $order
     *
     * Send new order confirmation mail to the customer
     */
    public function sendNewOrderMail($order)
    {
        try {
            Mail::send(new NewOrderNotification($order));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $invoice
     *
     * Send new invoice mail to the customer
     */
    public function sendNewInvoiceMail($invoice)
    {
        try {
            Mail::send(new NewInvoiceNotification($invoice));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $shipment
     *
     * Send new shipment mail to the customer
     */
    public function sendNewShipmentMail($shipment)
    {
        try {
            Mail::send(new NewShipmentNotification($shipment));
        } catch (\Exception $e) {

        }
    }
}
