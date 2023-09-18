@extends('b2b_marketplace::supplier.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.view-title', ['order_id' => $supplierOrder->order_id]) }}
@stop

@section('content-wrapper')

    <div class="content full-page">

        <div class="page-header">

            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.orders.index') }}'"></i>

                    {{ __('admin::app.sales.orders.view-title', ['order_id' => $supplierOrder->order_id]) }}
                </h1>
            </div>

            <div class="page-action">
                @if (core()->getConfigData('b2b_marketplace.settings.general.can_cancel_order') && $supplierOrder->canCancel())
                     <a href="{{ route('b2b_marketplace.supplier.account.orders.cancel', $supplierOrder->order_id) }}" class="btn btn-lg btn-primary" v-alert:message="'{{ __('admin::app.sales.orders.cancel-confirm-msg') }}'">
                        {{ __('admin::app.sales.orders.cancel-btn-title') }}
                    </a>
                @endif
    
                @if (core()->getConfigData('b2b_marketplace.settings.general.can_create_invoice') && $supplierOrder->canInvoice())
                    <a href="{{ route('b2b_marketplace.supplier.sales.invoice.create', $supplierOrder->order_id) }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.orders.invoice-btn-title') }}
                    </a>
                @endif
    
                @if (core()->getConfigData('b2b_marketplace.settings.general.can_create_shipment') && $supplierOrder->canShip())
                    <a href="{{ route('b2b_marketplace.supplier.sales.shipments.create', $supplierOrder->order_id) }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.orders.shipment-btn-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">

            <tabs>

                <tab name="{{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.info') }}" :selected="true">
                    <div class="sale-container">

                        <accordian title="{{ __('admin::app.sales.orders.order-and-account') }}" :active="true">
                            <div slot="body">

                                <div class="sale">
                                    <div class="sale-section">
                                        <div class="secton-title">
                                            <span>{{ __('admin::app.sales.orders.order-info') }}</span>
                                        </div>

                                        <div class="section-content">
                                            <div class="row">
                                                <span class="title">
                                                    {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.placed-on') }}
                                                </span>

                                                <span class="value">
                                                    {{ $supplierOrder->order->created_at }}
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span class="title">
                                                    {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.status') }}
                                                </span>

                                                <span class="value">
                                                    {{ __('admin::app.notification.order-status-messages.'.strtolower($supplierOrder->status_label)) }}
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.sales.orders.channel') }}
                                                </span>

                                                <span class="value">
                                                    {{ $supplierOrder->order->channel_name }}
                                                </span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="sale-section">
                                        <div class="secton-title">
                                            <span>{{ __('admin::app.sales.orders.account-info') }}</span>
                                        </div>

                                        <div class="section-content">
                                            <div class="row">
                                                <span class="title">
                                                    {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.customer-name') }}
                                                </span>

                                                <span class="value">
                                                    {{ $supplierOrder->order->customer_full_name }}
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.sales.orders.email') }}
                                                </span>

                                                <span class="value">
                                                    {{ $supplierOrder->order->customer_email }}
                                                </span>
                                            </div>

                                            @if (
                                                ! is_null($supplierOrder->order->customer)
                                                && ! is_null($supplierOrder->order->customer->group)
                                            )
                                                <div class="row">
                                                    <span class="title">
                                                        {{ __('admin::app.customers.customers.customer_group') }}
                                                    </span>

                                                    <span class="value">
                                                        {{ $supplierOrder->order->customer->group->name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>                               

                            </div>
                        </accordian>

                        @if (
                            $supplierOrder->order->billing_address
                            || $supplierOrder->order->shipping_address
                        )
                            <accordian title="{{ __('admin::app.sales.orders.address') }}" :active="true">
                                <div slot="body">
                                    <div class="sale">
                                        @if($supplierOrder->order->billing_address)
                                            <div class="sale-section">
                                                <div class="secton-title">
                                                    <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                                </div>

                                                <div class="section-content">
                                                    @include ('admin::sales.address', ['address' => $supplierOrder->order->billing_address])
                                                </div>
                                            </div>
                                        @endif

                                        @if ($supplierOrder->order->shipping_address)
                                            <div class="sale-section">
                                                <div class="secton-title">
                                                    <span>{{ __('admin::app.sales.orders.shipping-address') }}</span>
                                                </div>

                                                <div class="section-content">
                                                    @include ('admin::sales.address', ['address' => $supplierOrder->order->shipping_address])
                                                </div>
                                            </div>
                                        @endif
                                    </div>                                   
                                </div>
                            </accordian>
                        @endif

                        <accordian title="{{ __('admin::app.sales.orders.payment-and-shipping') }}" :active="true">
                            <div slot="body">

                                <div class="sale">
                                    <div class="sale-section">
                                        <div class="secton-title">
                                            <span>{{ __('admin::app.sales.orders.payment-info') }}</span>
                                        </div>

                                        <div class="section-content">
                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.sales.orders.payment-method') }}
                                                </span>

                                                <span class="value">
                                                    {{ core()->getConfigData('sales.paymentmethods.' . $supplierOrder->order->payment->method . '.title') }}
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.sales.orders.currency') }}
                                                </span>

                                                <span class="value">
                                                    {{ $supplierOrder->order->order_currency_code }}
                                                </span>
                                            </div>

                                            @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($supplierOrder->order->payment->method); @endphp

                                            @if (! empty($additionalDetails))
                                                <div class="row">
                                                    <span class="title">
                                                        {{ $additionalDetails['title'] }}
                                                    </span>

                                                    <span class="value">
                                                        {{ $additionalDetails['value'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($supplierOrder->order->shipping_address)
                                        <div class="sale-section">
                                            <div class="secton-title">
                                                <span>{{ __('admin::app.sales.orders.shipping-info') }}</span>
                                            </div>

                                            <div class="section-content">
                                                <div class="row">
                                                    <span class="title">
                                                        {{ __('admin::app.sales.orders.shipping-method') }}
                                                    </span>

                                                    <span class="value">
                                                        {{ $supplierOrder->order->shipping_title }}
                                                    </span>
                                                </div>

                                                <div class="row">
                                                    <span class="title">
                                                        {{ __('admin::app.sales.orders.shipping-price') }}
                                                    </span>

                                                    <span class="value">
                                                        {{ core()->formatBasePrice($supplierOrder->order->base_shipping_amount) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>                               
                            </div>
                        </accordian>

                        <accordian title="{{ __('admin::app.sales.orders.products-ordered') }}" :active="true">
                            <div slot="body">
                                <div class="table">
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                                                    <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                                                    <th>{{ __('admin::app.sales.orders.price') }}</th>
                                                    <th>{{ __('admin::app.sales.orders.item-status') }}</th>
                                                    <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                                                    <th>{{ __('admin::app.sales.orders.tax-percent') }}</th>
                                                    <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                                    @if ($supplierOrder->order->base_discount_amount > 0)
                                                        <th>{{ __('admin::app.sales.orders.discount-amount') }}</th>
                                                    @endif
                                                    <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($supplierOrder->items as $supplierOrderItem)
                                                    <tr>
                                                        <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}">
                                                            {{ $supplierOrderItem->item->type == 'configurable' ? $supplierOrderItem->item->child->sku : $supplierOrderItem->item->sku }}
                                                        </td>
                                                        <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}">
                                                            {{ $supplierOrderItem->item->name }}

                                                            @if (isset($supplierOrderItem->additional['attributes']))
                                                                <div class="item-options">

                                                                    @foreach ($supplierOrderItem->additional['attributes'] as $attribute)
                                                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                                    @endforeach

                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td data-value="{{ __('shop::app.customer.account.order.view.price') }}">{{ core()->formatPrice($supplierOrderItem->item->price, $supplierOrder->order->order_currency_code) }}</td>
                                                        <td data-value="{{ __('shop::app.customer.account.order.view.item-status') }}">
                                                            <span class="qty-row">
                                                                {{ __('shop::app.customer.account.order.view.item-ordered', ['qty_ordered' => $supplierOrderItem->item->qty_ordered]) }}
                                                            </span>

                                                            <span class="qty-row">
                                                                {{ $supplierOrderItem->item->qty_invoiced ? __('shop::app.customer.account.order.view.item-invoice', ['qty_invoiced' => $supplierOrderItem->item->qty_invoiced]) : '' }}
                                                            </span>

                                                            <span class="qty-row">
                                                                {{ $supplierOrderItem->item->qty_refunded ? __('admin::app.sales.orders.item-refunded', ['qty_refunded' => $supplierOrderItem->item->qty_refunded]) : '' }}
                                                            </span>

                                                            <span class="qty-row">
                                                                {{ $supplierOrderItem->item->qty_shipped ? __('shop::app.customer.account.order.view.item-shipped', ['qty_shipped' => $supplierOrderItem->item->qty_shipped]) : '' }}
                                                            </span>

                                                            <span class="qty-row">
                                                                {{ $supplierOrderItem->item->qty_canceled ? __('shop::app.customer.account.order.view.item-canceled', ['qty_canceled' => $supplierOrderItem->item->qty_canceled]) : '' }}
                                                            </span>
                                                        </td>

                                                        <td data-value="{{ __('shop::app.customer.account.order.view.subtotal') }}">{{ core()->formatPrice($supplierOrderItem->item->total, $supplierOrder->order->order_currency_code) }}</td>

                                                        <td data-value="{{ __('shop::app.customer.account.order.view.discount') }}">{{ core()->formatPrice($supplierOrderItem->item->discount_amount, $supplierOrder->order->order_currency_code) }}</td>

                                                        <td data-value="{{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.admin-commission') }}">{{ core()->formatPrice($supplierOrderItem->commission, $supplierOrder->order->order_currency_code) }}</td>

                                                        <td data-value="{{ __('shop::app.customer.account.order.view.tax-percent') }}">{{ number_format($supplierOrderItem->item->tax_percent, 2) }}%</td>

                                                        <td data-value="{{ __('shop::app.customer.account.order.view.tax-amount') }}">{{ core()->formatPrice($supplierOrderItem->item->tax_amount, $supplierOrder->order->order_currency_code) }}</td>

                                                        <td data-value="{{ __('shop::app.customer.account.order.view.grand-total') }}">{{ core()->formatPrice($supplierOrderItem->item->total + $supplierOrderItem->item->tax_amount - $supplierOrder->discount_amount, $supplierOrder->order->order_currency_code) }}</td>
                                                    </tr>
                                                @endforeach
                                        </table>
                                    </div>
                                </div>

                                <div class="summary-comment-container">

                                    <table class="sale-summary">
                                        <tbody>
                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->sub_total, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->shipping_amount, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.discount') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->discount_amount, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="border">
                                                <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->tax_amount, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->grand_total, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            @if ($supplierOrder->grand_total_invoiced != 0)
                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-paid') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->grand_total_invoiced - $supplierOrder->discount_amount , $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>
                                            @else
                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-paid') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->grand_total_invoiced, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>
                                            @endif

                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-refunded') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->grand_total_refunded, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            {{-- <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-due') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->total_due, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr> --}}

                                            <tr class="bold">
                                                <td> {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.total-supplier-amount') }}
                                                </td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->supplier_total, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="bold">
                                                <td>{{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.total-admin-commission') }}
                                                </td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($supplierOrder->commission, $supplierOrder->order->order_currency_code) }}</td>
                                            </tr>
                                        <tbody>
                                    </table>
                                </div>
                            </div>
                        </accordian>

                    </div>
                </tab>

                @if ($supplierOrder->invoices->count())
                    <tab name="{{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.invoices') }}">

                        <div class="sale-container">
                            <div class="table" style="padding: 20px 0">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin::app.sales.invoices.id') }}</th>
                                            <th>{{ __('admin::app.sales.invoices.date') }}</th>
                                            <th>{{ __('admin::app.sales.invoices.order-id') }}</th>
                                            <th>{{ __('admin::app.sales.invoices.customer-name') }}</th>
                                            <th>{{ __('admin::app.sales.invoices.status') }}</th>
                                            <th>{{ __('admin::app.sales.invoices.amount') }}</th>
                                            <th>{{ __('shop::app.customer.account.order.view.discount') }}</th>
                                            <th>{{ __('admin::app.sales.invoices.action') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($supplierOrder->invoices as $invoice)

                                            <tr>
                                                <td>#{{ $invoice->id }}</td>
                                                <td>{{ $invoice->created_at }}</td>
                                                <td>#{{ $invoice->order->order_id }}</td>
                                                <td>{{ $supplierOrder->order->customer_first_name. ' '. $supplierOrder->order->customer_last_name }}</td>
                                                <td>{{ $invoice->state }}</td>
                                                <td>{{ core()->formatBasePrice($invoice->base_grand_total) }}</td>
                                                <td>{{ core()->formatBasePrice($invoice->order->discount_amount) }}</td>
                                                <td class="action">
                                                    <a href="{{ route('b2b_marketplace.sales.invoices.view', $invoice->invoice_id) }}">
                                                        <i class="icon eye-icon"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if (! $supplierOrder->invoices->count())
                                            <tr>
                                                <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                            <tr>
                                        @endif
                                </table>
                            </div>
                        </div>
                    </tab>
                @endif

                
                @if ($supplierOrder->shipments->count())
                    <tab name="{{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.shipments') }}">

                        <div class="table" style="padding: 20px 0">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('admin::app.sales.shipments.id') }}</th>
                                        <th>{{ __('admin::app.sales.shipments.date') }}</th>
                                        <th>{{ __('admin::app.sales.shipments.order-id') }}</th>
                                        <th>{{ __('admin::app.sales.shipments.order-date') }}</th>
                                        <th>{{ __('admin::app.sales.shipments.customer-name') }}</th>
                                        <th>{{ __('admin::app.sales.shipments.total-qty') }}</th>
                                        <th>{{ __('admin::app.sales.shipments.action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($supplierOrder->shipments as $shipment)
                                        <tr>
                                            <td>#{{ $shipment->shipment_id }}</td>
                                            <td>{{ $shipment->created_at }}</td>
                                            <td>#{{ $shipment->order->order_id }}</td>
                                            <td>{{ $shipment->order->created_at }}</td>
                                            <td>{{ $supplierOrder->order->customer_first_name. ' '. $supplierOrder->order->customer_last_name }}</td>
                                            <td>{{ $shipment->total_qty }}</td>
                                            <td class="action">
                                                <a href="{{ route('b2b_marketplace.sales.shipments.view', $shipment->shipment_id) }}">
                                                    <i class="icon eye-icon"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if (! $supplierOrder->shipments->count())
                                        <tr>
                                            <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                        <tr>
                                    @endif
                            </table>
                        </div>
                    </tab>
                @endif
                
                @if ($supplierOrder->refunds->count())
                    <tab name="{{ __('admin::app.sales.orders.refunds') }}">

                        <div class="table" style="padding: 20px 0">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('admin::app.sales.refunds.id') }}</th>
                                        <th>{{ __('admin::app.sales.refunds.date') }}</th>
                                        <th>{{ __('admin::app.sales.refunds.order-id') }}</th>
                                        <th>{{ __('admin::app.sales.refunds.customer-name') }}</th>
                                        <th>{{ __('admin::app.sales.refunds.status') }}</th>
                                        <th>{{ __('admin::app.sales.refunds.refunded') }}</th>
                                        <th>{{ __('admin::app.sales.refunds.action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($supplierOrder->order->refunds as $refund)
                                        <tr>
                                            <td>#{{ $refund->id }}</td>
                                            <td>{{ $refund->created_at }}</td>
                                            <td>#{{ $refund->order->increment_id }}</td>
                                            <td>{{ $refund->order->customer_full_name }}</td>
                                            <td>{{ __('admin::app.sales.refunds.refunded') }}</td>
                                            <td>{{ core()->formatBasePrice($refund->base_grand_total) }}</td>
                                            <td class="action">
                                                <a href="{{ route('admin.sales.refunds.view', $refund->id) }}">
                                                    <i class="icon eye-icon"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if (! $supplierOrder->order->refunds->count())
                                        <tr>
                                            <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                        <tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </tab>
                @endif
            </tabs>
        </div>

    </div>
@stop
