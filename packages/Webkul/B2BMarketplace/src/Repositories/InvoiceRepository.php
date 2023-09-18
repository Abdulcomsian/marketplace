<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;

/**
 * Supplier Invoice Reposotory
 *
 * @copyright 209 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InvoiceRepository extends Repository
{
    /**
     * SupplierRepository object
     *
     * @var Object
     */
    protected $supplierRepository;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * InvoiceItemRepository object
     *
     * @var Object
     */
    protected $invoiceItemRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository      $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository     $productRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderItemRepository   $orderItemRepository
     * @param  Webkul\B2BMarketplace\Repositories\InvoiceItemRepository $invoiceItemRepository
     * @param  Illuminate\Container\Container                        $app
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        InvoiceItemRepository $invoiceItemRepository,
        App $app
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->productRepository = $productRepository;

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->invoiceItemRepository = $invoiceItemRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Invoice';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $invoice = $data['invoice'];

        Event::dispatch('marketplace.sales.invoice.save.before', $data);

        $supplierInvoices = [];

        foreach ($invoice->items()->get() as $item) {

            if (isset($item->additional['supplier_info'])) {

                $supplier = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);
                $suppliers[] = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);
            } else {
                $supplier = $this->productRepository->getSupplierByProductId($item->product_id);
                $suppliers[] = $this->productRepository->getSupplierByProductId($item->product_id);
            }

            if (! $supplier)
                continue;

            $supplierOrder = $this->orderRepository->findOneWhere([
                'order_id' => $invoice->order->id,
                'supplier_id' => $supplier->id,
            ]);

            if (! $supplierOrder)
                continue;

            $supplierOrderItem = $this->orderItemRepository->findOneWhere([
                'b2b_marketplace_order_id' => $supplierOrder->id,
                'order_item_id' => $item->order_item->id,
            ]);

            if (! $supplierOrderItem)
                continue;

            $supplierInvoice = $this->findOneWhere([
                'invoice_id' => $invoice->id,
                'b2b_marketplace_order_id' => $supplierOrder->id,
            ]);

            if (! $supplierInvoice) {

                $supplierInvoices[] = $supplierInvoice = parent::create([
                    'total_qty' => $item->qty,
                    'state' => 'paid',
                    'invoice_id' => $invoice->id,
                    'b2b_marketplace_order_id' => $supplierOrder->id,
                ]);
            } else {

                $supplierInvoice->total_qty += $item->qty;
                $supplierInvoice->save();
            }

            $supplierInvoiceItem = $this->invoiceItemRepository->create([
                'b2b_marketplace_invoice_id' => $supplierInvoice->id,
                'invoice_item_id' => $item->id,
            ]);

            $this->orderItemRepository->collectTotals($supplierOrderItem);
        }

        foreach ($supplierInvoices as $supplierInvoice) {
            $this->collectTotals($supplierInvoice);
        }

        foreach ($suppliers as $supplier) {
            if ($supplier) {
                foreach ($this->orderRepository->findWhere(['order_id' => $invoice->order->id, 'supplier_id' => $supplier->id]) as $order) {

                    $this->orderRepository->collectTotals($order);
                    $this->orderRepository->updateOrderStatus($order);
                }
            }
        }

        foreach ($supplierInvoices as $supplierInvoice) {
            Event::dispatch('b2b_marketplace.sales.invoice.save.after', $supplierInvoice);
        }
    }

    /**
     * @param mixed $supplierInvoice
     * @return mixed
     */
    public function collectTotals($supplierInvoice)
    {
        $supplierInvoice->sub_total = $supplierInvoice->base_sub_total = 0;
        $supplierInvoice->tax_amount = $supplierInvoice->base_tax_amount = 0;
        $supplierInvoice->shipping_amount = $supplierInvoice->base_shipping_amount = 0;
        $supplierInvoice->grand_total = $supplierInvoice->base_grand_total = 0;

        foreach ($supplierInvoice->items as $supplierInvoiceItem) {
            $supplierInvoice->sub_total += $supplierInvoiceItem->item->total;
            $supplierInvoice->base_sub_total += $supplierInvoiceItem->item->base_total;

            $supplierInvoice->tax_amount += $supplierInvoiceItem->item->tax_amount;
            $supplierInvoice->base_tax_amount += $supplierInvoiceItem->item->base_tax_amount;
        }

        $supplierInvoice->shipping_amount = $supplierInvoice->order->shipping_amount;
        $supplierInvoice->base_shipping_amount = $supplierInvoice->order->base_shipping_amount;

        if ($supplierInvoice->order->shipping_amount) {
            foreach ($supplierInvoice->order->invoices as $prevInvoice) {
                if ((float) $prevInvoice->shipping_amount) {
                    $supplierInvoice->shipping_amount = 0;
                    $supplierInvoice->base_shipping_amount = 0;
                }
            }
        }

        $supplierInvoice->grand_total = $supplierInvoice->sub_total + $supplierInvoice->tax_amount + $supplierInvoice->shipping_amount;
        $supplierInvoice->base_grand_total = $supplierInvoice->base_sub_total + $supplierInvoice->base_tax_amount + $supplierInvoice->base_shipping_amount;

        $supplierInvoice->save();

        return $supplierInvoice;
    }
}