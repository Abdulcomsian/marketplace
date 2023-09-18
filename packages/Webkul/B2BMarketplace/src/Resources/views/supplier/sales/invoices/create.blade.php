@extends('b2b_marketplace::supplier.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.sales.invoices.create-title') }}
@endsection

@section('content-wrapper')

    <div class="content full-page">

        <form method="POST" action="{{ route('b2b_marketplace.supplier.sales.invoice.store', $supplierOrder->order_id) }}" @submit.prevent="onSubmit">
            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.sales.invoices.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('b2b_marketplace::app.shop.supplier.account.sales.invoices.create') }}
                    </button>
                </div>
            </div>

            {!! view_render_event('b2b_marketplace.suppliers.account.sales.invoices.create.before', ['supplierOrder' => $supplierOrder]) !!}

            <div class="sale-container" style="margin:2%;">
                <div class="sale-section">
                    <div class="section-content" style="border-bottom: 1px solid #e8e8e8 !important;">
                        <div class="row">
                            <span class="title">
                                {{ __('b2b_marketplace::app.shop.supplier.account.sales.invoices.order-id') }}
                            </span>

                            <span class="value">
                                <a href="{{ route('b2b_marketplace.supplier.sales.orders.view', $supplierOrder->order_id) }}">#{{ $supplierOrder->order_id }}</a>
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.placed-on') }}
                            </span>

                            <span class="value">
                                {{ core()->formatDate($supplierOrder->created_at, 'd M Y') }}
                            </span>
                        </div>

                        <div class="row">
                            <span class="title">
                                {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.status') }}
                            </span>

                            <span class="value">
                                {{ $supplierOrder->status_label }}
                            </span>
                        </div>

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
                                {{ __('b2b_marketplace::app.shop.supplier.account.sales.orders.email') }}
                            </span>

                            <span class="value">
                                {{ $supplierOrder->order->customer_email }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="sale-section">
                    <div class="secton-title">
                        <span>{{ __('shop::app.customer.account.order.view.products-ordered') }}</span>
                    </div>

                    <div class="section-content">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.sales.invoices.product-name') }}</th>
                                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.sales.invoices.qty-ordered') }}</th>
                                        <th>{{ __('b2b_marketplace::app.shop.supplier.account.sales.invoices.qty-to-invoice') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($supplierOrder->items as $supplierOrderItem)
                                        <tr>
                                            <td>
                                                {{ $supplierOrderItem->item->name }}

                                                @if (isset($sellerOrderItem->additional['attributes']))
                                                    <div class="item-options">

                                                        @foreach ($sellerOrderItem->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach

                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $supplierOrderItem->item->qty_ordered }}</td>
                                            <td>
                                                <div class="control-group" :class="[errors.has('invoice[items][{{ $supplierOrderItem->order_item_id }}]') ? 'has-error' : '']">
                                                    <input type="text" v-validate="'required|numeric|min:0'" class="control" id="invoice[items][{{ $supplierOrderItem->order_item_id }}]" name="invoice[items][{{ $supplierOrderItem->order_item_id }}]" value="{{ $supplierOrderItem->qty_to_invoice }}" data-vv-as="&quot;{{ __('admin::app.sales.invoices.qty-to-invoice') }}&quot;"/>

                                                    <span class="control-error" v-if="errors.has('invoice[items][{{ $supplierOrderItem->order_item_id }}]')">
                                                        @verbatim
                                                            {{ errors.first('invoice[items][<?php echo $supplierOrderItem->order_item_id ?>]') }}
                                                        @endverbatim
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <div class="b2b-sale-section">
                    <div class="section-content" style="border-bottom: 0">
                        <div class="order-box-container">
                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.shipping-address') }}
                                </div>

                                <div class="box-content">

                                    @include ('admin::sales.address', ['address' => $supplierOrder->order->billing_address])

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.billing-address') }}
                                </div>

                                <div class="box-content">

                                    @include ('admin::sales.address', ['address' => $supplierOrder->order->shipping_address])

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.shipping-method') }}
                                </div>

                                <div class="box-content">

                                    {{ $supplierOrder->order->shipping_title }}

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.payment-method') }}
                                </div>

                                <div class="box-content">
                                    {{ core()->getConfigData('sales.paymentmethods.' . $supplierOrder->order->payment->method . '.title') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {!! view_render_event('b2b_marketplace.suppliers.account.sales.invoices.create.after', ['supplierOrder' => $supplierOrder]) !!}

        </form>

    </div>

@endsection