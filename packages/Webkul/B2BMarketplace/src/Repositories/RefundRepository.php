<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;

/**
 * Supplier Refund Reposotory
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RefundRepository extends Repository
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
     * RefundItemRepository object
     *
     * @var Object
     */
    protected $refundItemRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository      $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository     $productRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderItemRepository   $orderItemRepository
     * @param  Webkul\B2BMarketplace\Repositories\InvoiceItemRepository $invoiceItemRepository
     * @param  Webkul\B2BMarketplace\Repositories\RefundItemRepository  $refundItemRepository
     * @param  Illuminate\Container\Container                        $app
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        InvoiceItemRepository $invoiceItemRepository,
        RefundItemRepository $refundItemRepository,
        App $app
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->productRepository = $productRepository;

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->invoiceItemRepository = $invoiceItemRepository;

        $this->refundItemRepository = $refundItemRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Refund';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $refund = $data['refund'];

        Event::dispatch('b2b_marketplace.sales.refund.save.before', $data);

        $supplierRefunds = [];

        foreach ($refund->items()->get() as $item) {
            if (isset($item->additional['supplier_info']) && !$item->additional['supplier_info']['is_owner']) {
                $supplier = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);
                $suppliers[] = $this->supplierRepository->find($item->additional['supplier_info']['supplier_id']);
            } else {
                $supplier = $this->productRepository->getSupplierByProductId($item->product_id);
                $suppliers[] = $this->productRepository->getSupplierByProductId($item->product_id);
            }

            if (! $supplier)
                continue;

            $supplierOrder = $this->orderRepository->findOneWhere([
                'order_id' => $refund->order->id,
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

            $supplierRefund = $this->findOneWhere([
                'refund_id' => $refund->id,
                'b2b_marketplace_order_id' => $supplierOrder->id,
            ]);

            if (! $supplierRefund) {
                $supplierRefunds[] = $supplierRefund = parent::create([
                    'total_qty' => $item->qty,
                    'state' => 'refunded',
                    'refund_id' => $refund->id,
                    'b2b_marketplace_order_id' => $supplierOrder->id,
                    'adjustment_refund' => core()->convertPrice($data['refund']['adjustment_refund'], $supplierOrder->order_currency_code),
                    'base_adjustment_refund' => $data['refund']['adjustment_refund'],
                    'adjustment_fee' => core()->convertPrice($data['refund']['adjustment_fee'], $supplierOrder->order_currency_code),
                    'base_adjustment_fee' => $data['refund']['adjustment_fee'],
                    'shipping_amount' => core()->convertPrice($data['refund']['shipping_amount'], $supplierOrder->order_currency_code),
                    'base_shipping_amount' => $data['refund']['shipping_amount']
                ]);
            } else {
                $supplierRefund->total_qty += $item->qty;

                $supplierRefund->save();
            }

            $supplierRefundItem = $this->refundItemRepository->create([
                    'b2b_marketplace_refund_id' => $supplierRefund->id,
                    'refund_item_id' => $item->id,
            ]);

            $this->orderItemRepository->collectTotals($supplierOrderItem);
        }

        foreach ($supplierRefunds as $supplierRefund) {
            $this->collectTotals($supplierRefund);
        }

        foreach ($suppliers as $supplier) {
            if ($supplier) {
                foreach ($this->orderRepository->findWhere(['order_id' => $refund->order->id, 'supplier_id' => $supplier->id]) as $order) {

                    $this->orderRepository->collectTotals($order);

                    $this->orderRepository->updateOrderStatus($order);

                    $this->orderRepository->update(['supplier_payout_status' => 'refunded'], $order->id);
                }
            }
        }

        foreach ($supplierRefunds as $supplierRefund) {
            Event::dispatch('b2b_marketplace.sales.refund.save.after', $supplierRefund);
        }
    }

    /**
     * @param mixed $supplierRefund
     * @return mixed
     */
    public function collectTotals($supplierRefund)
    {
        $supplierRefund->sub_total = $supplierRefund->base_sub_total = 0;
        $supplierRefund->tax_amount = $supplierRefund->base_tax_amount = 0;
        $supplierRefund->grand_total = $supplierRefund->base_grand_total = 0;
        $supplierRefund->discount_amount = $supplierRefund->base_discount_amount = 0;

        foreach ($supplierRefund->items as $supplierRefundItem) {
            $supplierRefund->sub_total += $supplierRefundItem->item->total;
            $supplierRefund->base_sub_total += $supplierRefundItem->item->base_total;

            $supplierRefund->tax_amount += $supplierRefundItem->item->tax_amount;
            $supplierRefund->base_tax_amount += $supplierRefundItem->item->base_tax_amount;

            $supplierRefund->discount_amount += $supplierRefundItem->item->discount_amount;
            $supplierRefund->base_discount_amount += $supplierRefundItem->item->base_discount_amount;
        }

        $supplierRefund->grand_total = $supplierRefund->sub_total + $supplierRefund->tax_amount + $supplierRefund->shipping_amount + $supplierRefund->adjustment_refund - $supplierRefund->adjustment_fee - $supplierRefund->discount_amount;

        $supplierRefund->base_grand_total = $supplierRefund->base_sub_total + $supplierRefund->base_tax_amount + $supplierRefund->base_shipping_amount + $supplierRefund->base_adjustment_refund - $supplierRefund->base_adjustment_fee - $supplierRefund->base_discount_amount;

        $supplierRefund->save();

        return $supplierRefund;
    }
}