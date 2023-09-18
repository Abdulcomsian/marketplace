@extends('b2b_marketplace::supplier.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.view-title', ['transaction_id' => $transaction->transaction_id]) }}
@stop

{{-- push the required css for responsiveness of table. --}}
@push('css')
    <style>
        .table {
            display: block !important;
            overflow-x: auto !important;
            width: 100% !important;
        }
    </style>
@endpush

@section('content-wrapper')

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/supplier/dashboard') }}';"></i>

                    {{ __('b2b_marketplace::app.supplier.account.transactions.title', ['transaction_id' => $transaction->transaction_id]) }}
                </h1>
            </div>
        </div>

        <div class="page-content">
            <div class="sale-container">

                <?php $supplierOrder = $transaction->order; ?>
                <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.view-title', ['transaction_id' => $transaction->transaction_id]) }}'" :active="true">
                    <div slot="body">

                        <div class="sale-section">

                            <div class="section-content">
                                <div class="row">
                                    <span class="title">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.order-id') }}
                                    </span>

                                    <span class="value">
                                        <a href="{{ route('b2b_marketplace.supplier.sales.orders.view', $supplierOrder->order_id) }}">#{{ $supplierOrder->order_id }}</a>
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="title">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.created-at') }}
                                    </span>

                                    <span class="value">
                                        {{ core()->formatDate($transaction->created_at, 'd M Y') }}
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="title">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.payment-method') }}
                                    </span>

                                    <span class="value">
                                        {{ $transaction->method }}
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="title">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.total') }}
                                    </span>

                                    <span class="value">
                                        {{ core()->formatBasePrice($transaction->base_total) }}
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="title">
                                        {{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.comment') }}
                                    </span>

                                    <span class="value">
                                        {{ $transaction->comment }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <?php $supplierOrder = $transaction->order; ?>

                <accordian :title="'{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.order-id', ['order_id' => $supplierOrder->order_id]) }}'" :active="true">
                    <div slot="body">

                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                        <th>{{ __('shop::app.customer.account.order.view.total') }}</th>
                                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.commission') }}</th>
                                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.supplier-total') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($supplierOrder->items as $supplierOrderItem)

                                    <tr>
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

                                        <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}">{{ $supplierOrderItem->item->qty_ordered }}</td>

                                        <td data-value="{{ __('shop::app.customer.account.order.view.total') }}">{{ core()->formatPrice($supplierOrderItem->item->total, $supplierOrder->order->order_currency_code) }}</td>

                                        <td data-value="{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.commission') }}">{{ core()->formatPrice($supplierOrderItem->commission, $supplierOrder->order->order_currency_code) }}</td>

                                        <td data-value="{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.supplier-total') }}">{{ core()->formatPrice($supplierOrderItem->supplier_total, $supplierOrder->order->order_currency_code) }}</td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>

                        <table class="sale-summary">
                            <tbody>
                                <tr>
                                    <td>{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.sub-total') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($supplierOrder->sub_total, $supplierOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($supplierOrder->base_shipping_amount, $supplierOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.tax') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($supplierOrder->tax_amount, $supplierOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr class="bold">
                                    <td>{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.commission') }}</td>
                                    <td>-</td>
                                    <td>-{{ core()->formatPrice($supplierOrder->commission, $supplierOrder->order->order_currency_code) }}</td>
                                </tr>

                                <tr class="bold">
                                    <td>{{ __('b2b_marketplace::app.shop.supplier.account.sales.transactions.supplier-total') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatPrice($supplierOrder->supplier_total, $supplierOrder->order->order_currency_code) }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </accordian>

            </div>
        </div>

    </div>
@stop