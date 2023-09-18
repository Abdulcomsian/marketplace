<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

/**
 * Supplier Transaction Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Transaction';
    }

    /**
     * Pay seller
     *
     * @param integer $data
     * @return boolean
     */
    public function paySupplier($data)
    {
        $orderRepository = app('Webkul\B2BMarketplace\Repositories\OrderRepository');

        $supplierOrder = $orderRepository->findOneWhere([
            'order_id' => $data['order_id'],
            'supplier_id' => $data['supplier_id']
        ]);

        if (! $supplierOrder) {
            session()->flash('error', trans('b2b_marketplace::app.admin.orders.order-not-exist'));

            return;
        }

        $totalPaid = $this->scopeQuery(function($query) use($supplierOrder) {
            return $query->where('b2b_marketplace_transactions.supplier_id', $supplierOrder->supplier_id)
                ->where('b2b_marketplace_transactions.b2b_marketplace_order_id', $supplierOrder->id);
        })->sum('base_total');

        $amount = $supplierOrder->base_supplier_total_invoiced - $totalPaid;

        if (! $amount) {
            session()->flash('error', trans('b2b_marketplace::app.admin.orders.no-amount-to-paid'));

            return;
        }

        Event::dispatch('b2b_marketplace.sales.transaction.create.before', $data);

        $transaction = $this->create([
            'type' => isset($data['type']) ? $data['type'] : 'manual',
            'method' => isset($data['method']) ? $data['method'] : 'manual',
            'transaction_id' => $data['order_id'] . '-' . Str::random(10),
            'comment' => $data['comment'],
            'base_total' => $amount,
            'b2b_marketplace_order_id' => $supplierOrder->id,
            'supplier_id' => $supplierOrder->supplier_id
        ]);

        if (($amount + $totalPaid) == $supplierOrder->base_supplier_total) {

            $orderRepository->update(['supplier_payout_status' => 'paid'], $supplierOrder->id);
        }

        Event::dispatch('b2b_marketplace.sales.transaction.create.after', $transaction);

        return $transaction;
    }
}
