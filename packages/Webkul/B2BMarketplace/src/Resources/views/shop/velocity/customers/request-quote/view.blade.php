@extends('b2b_marketplace::shop.layouts.account')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-details') }}
@endsection

<?php

$quoteInCart = false;

if (Cart::getCart()) {
    foreach (Cart::getCart()['items'] as $items) {
        if (isset($items->additional['quote_id']) && $items->additional['quote_id'] == $supplierLastQuote->id) {
            $quoteInCart = true;
        }
    }
}
?>

@push('scripts')
    <script>
        function resize() {
            console.log($(window).width());
            if ($(window).width() < 974) {
                $('.account-head').addClass('mt-5');
            }
        }

        $(window).on('resize load', function() {
            resize()
        });
    </script>
@endpush
@section('page-detail-wrapper')

    @inject('InventoryHelper', 'Webkul\B2BMarketplace\Helpers\Helper')

    <div class="account-layout right m10">
        <div class="account-head mb-10">

            <div class="horizontal-rule"></div>

            <div class="account-items-list">
                <div class="account-table-content">

                    <div class="mp-main-page-content">
                        <div class="mp-columns-content">
                            <div class="mp-column-mp-main">
                                <div class="mp-rfq-container">
                                    <div class="mp-rfq-data-container">

                                        <div clas="rfq-quote"
                                            style="font-size: 35px;
                                        font-weight: 400;">
                                            <i class="icon msg-back back-link"
                                                onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/customer/account/profile') }}';"></i>

                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-request') }}#{{ $quote->id }}
                                        </div>


                                        <div class="b2b-quote-item-viewinfo">
                                            <div class="b2b-quote-items-container">

                                                <div class="mp-rfq-item-container">
                                                    <div class="mp-rfq-item-row-primary">
                                                        <div class="mp-qs-item-name">{{ $customerQuote->product_name }}
                                                        </div>
                                                    </div>

                                                    <?php $products = app('Webkul\B2BMarketplace\Repositories\ProductRepository');
                                                    $is_config = $products->getProductType($supplierLastQuote->product_id); ?>

                                                    @if (isset($customerQuote->is_approve) &&
                                                        $supplierLastQuote->status != 'Rejected' &&
                                                        $supplierLastQuote->status == 'Approved' &&
                                                        !$supplierLastQuote->is_ordered)
                                                        @if ($customerQuote->is_approve)
                                                            <form method="post"
                                                                action="{{ route('b2b_marketplace.cart.add', $supplierLastQuote->product_id) }}">
                                                                @csrf()

                                                                <input type="hidden"
                                                                    value="{{ $supplierLastQuote->product_id }}"
                                                                    name="product">
                                                                <input type="hidden"
                                                                    value="{{ $supplierLastQuote->quantity }}"
                                                                    name="quantity">
                                                                <input type="hidden" value="{{ $supplierLastQuote->id }}"
                                                                    name="quote_id">
                                                                <input type="hidden"
                                                                    value="{{ $supplierLastQuote->supplier_id }}"
                                                                    name="supplier_id">
                                                                <input type="hidden" value="{{ $is_config }}"
                                                                    name="is_configurable">


                                                                <div class="b2b-quote-request-section-content"
                                                                    style="position: absolute; right: 5%;">
                                                                    <div class="buttone-group">
                                                                        <button class="theme-btn"
                                                                            {{ $quoteInCart ? 'disabled' : '' }}>
                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.add-to-cart') }}
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                        @endif
                                                    @endif

                                                    <div class="mp-rfq-item-row-primary" style="width:80% !important;">
                                                        <div class="mp-qs-item-name">
                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.description') }}
                                                        </div>
                                                        <span>{{ $customerQuote->description }}</span>
                                                    </div>

                                                    <div class="mp-rfq-item-row-primary">
                                                        <div class="mp-rfq-item-primary-row">
                                                            <div class="mp-rfq-item-col">
                                                                <span class="mp-rfq-item-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quantity') }}
                                                                </span>
                                                                <span
                                                                    class="mp-rfq-item-content">{{ $customerQuote->quantity }}
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.unit') }}</span>
                                                            </div>

                                                            <div class="mp-rfq-item-col">
                                                                <span class="mp-rfq-item-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.expected-price') }}</span>
                                                                <span class="mp-rfq-item-content">
                                                                    {{ core()->currency($customerQuote->price_per_quantity) }}
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.per-unit') }}
                                                                </span>
                                                            </div>

                                                            <div class="mp-rfq-item-col">
                                                                <span class="mp-rfq-item-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.expected-total') }}
                                                                </span>
                                                                <span class="mp-rfq-item-content">
                                                                    {{ core()->currency($customerQuote->price_per_quantity * $customerQuote->quantity) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mp-rfq-item-row-primary">
                                                        <div class="mp-rfq-item-col">
                                                            <span class="mp-rfq-item-label">
                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.requires-samples') }}
                                                            </span>
                                                            <span
                                                                class="mp-rfq-item-content">{{ $customerQuote->is_sample ? 'Yes' : 'No' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="request-quote">
                                                    <div class="quote-summery">
                                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quote-summary') }}
                                                    </div>
                                                </div>

                                                <div class="item-container">
                                                    <div class="summary-container"
                                                        style="display: inline-block; width: 100%;">

                                                        <div class="summary-icon-you">
                                                            <img class="icon b2bcustomer-icon active" style="margin: -1px;">
                                                        </div>

                                                        <div class="summary-details">
                                                            <div class="summary-buyer-name"
                                                                style="color: #0041ff; font-weight: 600; font-size: 16px;">
                                                                {{ $customerName }}
                                                                <span class="buyer-name-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.you') }}
                                                                </span>
                                                            </div>

                                                            <div class="summary-time">{{ $customerQuote->created_at }}
                                                            </div>

                                                            <div class="summary-quote-details">

                                                                <div class="summary-quote-title">{{ $quote->quote_title }}
                                                                </div>
                                                                <div class="mp-qs-summary-quote-desc"
                                                                    style="margin-top: 10px;">{{ $quote->quote_brief }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="account-items-list">
                            @if (isset($supplierFirstQuote))

                                <accordian
                                    :title="'#{{ $supplierFirstQuote->id }}  {{ $supplierFirstQuote->product_name }}'"
                                    :active="true">
                                    <div slot="header">
                                        #{{ $supplierFirstQuote->id }} {{ $supplierFirstQuote->product_name }}
                                        <i class="icon expand-icon right"></i>
                                    </div>
                                    <div slot="body">

                                        <div class="page-content">
                                            <div class="mp-quote-request-thread">
                                                <div class="mp-qs-thread">
                                                    <div class="mp-qs-thread-body">
                                                        <div class="mp-qs-item-summary">
                                                            <div class="mp-qs-item-summary-col">
                                                                <div class="mp-qs-item-summary-col-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.request-quantity') }}
                                                                </div>

                                                                <div class="mp-qs-item-summary-col-content">
                                                                    {{ $customerQuote->quantity }}
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.unit') }}
                                                                </div>
                                                            </div>

                                                            <div class="mp-qs-item-summary-col">
                                                                <div class="mp-qs-item-summary-col-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.expected-price') }}
                                                                </div>

                                                                <div class="mp-qs-item-summary-col-content">
                                                                    {{ core()->currency($customerQuote->price_per_quantity) }}
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.per-unit') }}
                                                                </div>
                                                            </div>

                                                            <div class="mp-qs-item-summary-col">
                                                                <div class="mp-qs-item-summary-col-label">
                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.requires-samples') }}
                                                                </div>
                                                                <div class="mp-qs-item-summary-col-content">
                                                                    {{ $customerQuote->is_sample ? 'Yes' : 'No' }}</div>
                                                            </div>
                                                        </div>

                                                        {{-- supplier messsage --}}
                                                        @foreach ($supplierQuotes as $supplierQuote)
                                                            <div class="mp-qs-thread-content">
                                                                <div class="mp-qs-summary-icon">
                                                                    <img class="icon b2bcustomer-icon active"
                                                                        style="margin: -1px;">
                                                                </div>
                                                                <div class="mp-qs-summary-details">
                                                                    <div class="mp-qs-summary-buyer-name">
                                                                        {{ $supplierName }}
                                                                        <span class="mp-qs-summary-buyer-name-label">
                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.supplier') }}</span>
                                                                    </div>

                                                                    <div class="mp-qs-summary-time">
                                                                        {{ $supplierQuote->created_at }}</div>

                                                                    <div class="mp-qs-summary-quote-details">
                                                                        <div class="mp-qs-summary-quotation-block">
                                                                            <div class="mp-qs-summary-quotation-head">
                                                                                <span>
                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quotation') }}
                                                                                    #{{ $supplierQuote->id }}
                                                                                </span>
                                                                            </div>

                                                                            <div class="mp-qs-summary-quotation-body">
                                                                                <div class="mp-qs-summary-quotation-row">
                                                                                    <div
                                                                                        class="mp-qs-summary-quotation-col">
                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-label">
                                                                                            <span>
                                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quoted-quantity') }}
                                                                                            </span>
                                                                                        </div>

                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-content">
                                                                                            {{ $supplierQuote->quantity }}
                                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.unit') }}
                                                                                        </div>
                                                                                    </div>

                                                                                    <div
                                                                                        class="mp-qs-summary-quotation-col">
                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-label">
                                                                                            <span>
                                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.quoted-price') }}
                                                                                            </span>
                                                                                        </div>

                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-content">
                                                                                            <span>
                                                                                                {{ core()->currency($supplierQuote->price_per_quantity) }}
                                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.per-unit') }}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div
                                                                                        class="mp-qs-summary-quotation-col">
                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-label">
                                                                                            <span>
                                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.samples') }}</span>
                                                                                        </div>

                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-content">
                                                                                            <span>{{ $supplierQuote->is_sample ? 'Yes' : 'No' }}</span>
                                                                                            <span>
                                                                                                {{ $supplierQuote->is_sample ? $supplierQuote->sample_unit : '' }}</span>
                                                                                            <span>{{ $supplierQuote->is_sample ? __('b2b_marketplace::app.shop.supplier.account.rfq.units') : '' }}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div
                                                                                        class="mp-qs-summary-quotation-col">
                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-label">
                                                                                            <span>
                                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.shipping') }}</span>
                                                                                        </div>

                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col-content">
                                                                                            <span>{{ $supplierQuote->shipping_time }}</span>
                                                                                            <span>
                                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.day') }}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                @if ($supplierQuote->is_sample && $supplierQuote->is_sample_price)
                                                                                    <div
                                                                                        class="mp-qs-summary-quotation-row">
                                                                                        <div
                                                                                            class="mp-qs-summary-quotation-col">
                                                                                            <div
                                                                                                class="mp-qs-summary-quotation-col-label">
                                                                                                <span>
                                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.sample-charge') }}
                                                                                                </span>
                                                                                            </div>

                                                                                            <div
                                                                                                class="mp-qs-summary-quotation-col-content">
                                                                                                <span>
                                                                                                    {{ core()->currency($supplierQuote->sample_price) }}
                                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.per-unit') }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif

                                                                                <div class="mp-qs-summary-quotation-row">
                                                                                    <div class="quote-total-label-col">
                                                                                        <span>
                                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.total-quote-price') }}
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="quote-total-amount-col">
                                                                                        {{ core()->currency($supplierQuote->price_per_quantity * $supplierQuote->quantity) }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Displaying Quote Status -->

                                                                        <div class="mp-qs-summary-quote-desc">
                                                                            {{ $supplierQuote->note }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <?php
                                                        $quoteMessages = $quoteMessages->findWhere(['customer_quote_item_id' => $customerQuote->id, 'supplier_quote_item_id' => $supplierFirstQuote->id]);
                                                        ?>
                                                        {{-- QuoteMessages --}}
                                                        @foreach ($quoteMessages as $message)
                                                            <div class="mp-qs-thread-content customer">
                                                                <div class="mp-qs-thread-content customer">
                                                                    <div class="mp-qs-summary-icon">
                                                                        <img class="icon b2bcustomer-icon active"
                                                                            style="margin: -1px;">
                                                                    </div>
                                                                    <div class="mp-qs-summary-details">
                                                                        @if ($message->customer_id == null)
                                                                            <div class="mp-qs-summary-buyer-name">
                                                                                {{ $supplierName }}
                                                                                <span
                                                                                    class="mp-qs-summary-buyer-name-label">

                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.supplier') }}
                                                                                </span>
                                                                            </div>

                                                                            <div class="mp-qs-summary-time">
                                                                                {{ $message->created_at }}</div>
                                                                            <div class="mp-qs-summary-quote-details">
                                                                                <div class="mp-qs-summary-quote-desc">
                                                                                    {{ $message->message }}</div>
                                                                            </div>
                                                                        @else
                                                                            <div class="mp-qs-summary-buyer-name">
                                                                                {{ $customerName }}
                                                                                <span
                                                                                    class="mp-qs-summary-buyer-name-label">
                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.you') }}
                                                                                </span>
                                                                            </div>

                                                                            <div class="mp-qs-summary-time">
                                                                                {{ $message->created_at }}</div>
                                                                            <div class="mp-qs-summary-quote-details">
                                                                                <div class="mp-qs-summary-quote-desc">
                                                                                    {{ $message->message }}</div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        @if ($customerQuote->is_approve)
                                                            <div class="mp-qs-thread-content customer">
                                                                <div class="mp-qs-thread-content customer">

                                                                    <div class="mp-qs-summary-icon">
                                                                        <img class="icon b2bcustomer-icon active"
                                                                            style="margin: -1px;">
                                                                    </div>

                                                                    <div class="mp-qs-summary-details">
                                                                        <div class="mp-qs-summary-buyer-name">
                                                                            {{ $customerName }}
                                                                            <span class="mp-qs-summary-buyer-name-label">
                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.you') }}
                                                                            </span>
                                                                        </div>

                                                                        <div class="mp-qs-summary-time">
                                                                            {{ $customerQuote->created_at }}</div>

                                                                        <div class="mp-qs-summary-quote-details">
                                                                            <div class="summary-quote-desc"
                                                                                style="">
                                                                                {{ __('b2b_marketplace::app.shop.supplier.account.rfq.approved') }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        {{-- button section --}}
                                                        <div class="mp-qs-thread-content">
                                                            <div class="mp-qs-summary-icon">
                                                                <img class="icon b2bcustomer-icon active"
                                                                    style="margin: -1px;">
                                                            </div>
                                                            <div class="mp-qs-summary-details" style="border: none;">
                                                                <div class="mp-qs-summary-buyer-name">{{ $customerName }}
                                                                    <span class="mp-qs-summary-buyer-name-label">
                                                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.you') }}
                                                                    </span>
                                                                </div>
                                                                @if (!$supplierLastQuote->is_ordered)
                                                                    <div class="mp-qs-summary-quote-details">
                                                                        <div class="buttone-group">
                                                                            <a id="quoteMessage"
                                                                                class="theme-btn seperate"
                                                                                @click="showModal('quoteMessage')">
                                                                                <span>
                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.message') }}
                                                                                </span>
                                                                            </a>
                                                                            @if (!$supplierLastQuote->is_approve && $supplierQuote['status'] != 'Rejected')
                                                                                <form method="POST"
                                                                                    action="{{ route('b2b_marketplace.customers.account.supplier.quote.approve', [$supplierLastQuote->id, $customerQuote->id, $customerQuote->customer_id]) }}">
                                                                                    @csrf
                                                                                    <button class="theme-btn seperate">
                                                                                        <span>
                                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.approve-last-quote') }}
                                                                                        </span>
                                                                                    </button>

                                                                                </form>
                                                                            @endif

                                                                            @if ($supplierQuote['status'] != 'Rejected')
                                                                                <a href="{{ route('b2b_marketplace.customers.rfq.reject', [$supplierLastQuote['id'], $customerQuote['id']]) }}"
                                                                                    class="theme-btn seperate">
                                                                                    {{ __('b2b_marketplace::app.shop.supplier.account.rfq.reject-quote') }}
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="mp-qs-summary-quote-details">
                                                                        <div class="summary-quote-desc" style="">
                                                                            {{ __('Ordered') }}</div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <modal id="quoteMessage" :is-open="modalIds.quoteMessage">
                                                    <h3 slot="header">
                                                        {{ __('b2b_marketplace::app.shop.supplier.account.rfq.message') }}
                                                    </h3>

                                                    <div slot="body">
                                                        <form method="POST"
                                                            action="{{ route('b2b_marketplace.customer.request-quote.message', [$supplierFirstQuote->id, $customerQuote->id, $customerQuote->customer_id]) }}"
                                                            enctype="multipart/form-data" @submit.prevent="onSubmit">
                                                            @csrf

                                                            <div class="b2b-quote-request-section">
                                                                <div class="b2b-quote-request-section-content">
                                                                    <div class="control-group"
                                                                        :class="[errors.has('message') ? 'has-error' : '']">
                                                                        <label for="text" class="required">
                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.enter-message') }}
                                                                            <i class="export-icon"></i>
                                                                        </label>
                                                                        <textarea title="Note For Customer" type="text" v-validate="'required'" class="control" name="message"
                                                                            value="{{ old('message') }}" data-vv-as="&quot;{{ __('Message') }}&quot;"></textarea>
                                                                        <span class="control-error"
                                                                            v-if="errors.has('message')">@{{ errors.first('message') }}</span>
                                                                    </div>

                                                                    <div class="buttone-group">
                                                                        <button class="theme-btn">
                                                                            {{ __('b2b_marketplace::app.shop.supplier.account.rfq.send-message') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </modal>
                                            </div>
                                        </div>
                                    </div>
                                </accordian>

                            @endIf
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
