<?php

namespace Webkul\B2BMarketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Supplier OrderItem Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\OrderItem';
    }

    /**
     * @param mixed $supplierOrderItem
     * @return mixed
     */
    public function collectTotals($supplierOrderItem)
    {
        $commissionPercentage =  $supplierOrderItem->order->commission_percentage;

        $supplierOrderItem->commission_invoiced = $supplierOrderItem->base_commission_invoiced = 0;
        $supplierOrderItem->supplier_total_invoiced = $supplierOrderItem->base_supplier_total_invoiced = 0;

        foreach ($supplierOrderItem->item->invoice_items as $invoiceItem) {
            $supplierOrderItem->commission_invoiced += $commission = ($invoiceItem->total * $commissionPercentage) / 100;
            $supplierOrderItem->base_commission_invoiced += $baseCommission = ($invoiceItem->base_total * $commissionPercentage) / 100;

            $supplierOrderItem->supplier_total_invoiced += $invoiceItem->total + $invoiceItem->tax_amount - $commission;
            $supplierOrderItem->base_supplier_total_invoiced += $invoiceItem->base_total + $invoiceItem->base_tax_amount - $baseCommission;
        }

        $supplierOrderItem->save();

        return $supplierOrderItem;
    }
}