@extends('b2b_marketplace::supplier.layouts.rfq-tabs')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.account.rfq.title') }}
@endsection

@push('css')
    <style>
        .mp-qs-summary-quotation-row {
            overflow: auto;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link"
                        onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('View Quote Request') }}
                </h1>
            </div>

            <div class="horizontal-rule"></div>
        </div>

        <div class="account-table-content">
            <accordian :title="'#{{ __('b2b_marketplace::app.supplier.account.rfq.quote-request') }}: {{ $quote->id }}'"
                :active="true">
                <div slot="body">

                    <div class="mp-main-page-content" style="padding: 0rem;">
                        <div class="mp-columns-content">
                            <div class="mp-column-mp-main">
                                <div class="mp-rfq-container">
                                    <div class="mp-rfq-data-container">

                                        <div class="b2b-quote-item-viewinfo">
                                            <div class="b2b-quote-items-container">

                                                <div class="mp-rfq-item-container">
                                                    <div class="mp-rfq-item-row-primary">
                                                        <div class="mp-qs-item-name">{{ $customerQuote->products->first()->name }}
                                                        </div>
                                                    </div>

                                                    @if ($customerQuote['status'] == 'New')
                                                        <div class="b2b-quote-request-section-content">
                                                            <a href="{{ route('b2b_marketplace.supplier.request-quote.new.send-quote.create', [
                                                                $customerQuote->customer_id,
                                                                $customerQuote->product_id,
                                                            ]) }}"
                                                                title="Send Quote" class="quote-button">
                                                                <span>
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.send-quote') }}
                                                                </span>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    <div class="mp-rfq-item-row-primary" style="width:80% !important;">
                                                        <div class="mp-qs-item-name">
                                                            {{ __('b2b_marketplace::app.supplier.account.rfq.description') }}
                                                        </div>
                                                        <span>{{ $customerQuote->description }}</span>
                                                    </div>

                                                    <div class="mp-rfq-item-row-primary">
                                                        <div class="mp-rfq-item-primary-row">
                                                            <div class="mp-rfq-item-col">
                                                                <span class="mp-rfq-item-label">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.quantity') }}
                                                                </span>

                                                                <span class="mp-rfq-item-content">
                                                                    {{ $customerQuote->quantity }}
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.units') }}
                                                                </span>
                                                            </div>

                                                            <div class="mp-rfq-item-col">
                                                                <span class="mp-rfq-item-label">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.expected-price') }}
                                                                </span>

                                                                <span class="mp-rfq-item-content">
                                                                    {{ core()->currency($customerQuote->price_per_quantity) }}
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.per-unit') }}
                                                                </span>
                                                            </div>

                                                            <div class="mp-rfq-item-col">
                                                                <span class="mp-rfq-item-label">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.total') }}
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
                                                                {{ __('b2b_marketplace::app.supplier.account.rfq.require-sample') }}
                                                            </span>
                                                            <span
                                                                class="mp-rfq-item-content">{{ $customerQuote->is_sample ? 'Yes' : 'No' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="request-quote">
                                                    <div class="summary">
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.quote-summary') }}
                                                    </div>
                                                </div>

                                                {{--  --}}
                                                <div class="mp-qs-item-container">
                                                    <div class="mp-qs-summary-container">
                                                        <div class="mp-qs-summary-icon you">
                                                            <img class="icon b2bcustomer-icon active" style="margin: -1px;">
                                                        </div>

                                                        <div class="mp-qs-summary-details">

                                                            <div class="mp-qs-summary-buyer-name">
                                                                {{ $customerName }}
                                                                <span class="mp-qs-summary-buyer-name-label">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.buyer') }}
                                                                </span>
                                                            </div>

                                                            <div class="mp-qs-summary-time">
                                                                {{ $customerQuote->created_at }}</div>

                                                            <div class="mp-qs-summary-quote-details">

                                                                <div class="mp-qs-summary-quote-title">
                                                                    {{ $quote->quote_title }}
                                                                </div>
                                                                <div class="mp-qs-summary-quote-desc">
                                                                    {{ $quote->quote_brief }}</div>
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
                </div>
            </accordian>

            @if (isset($supplierFirstQuote) && $supplierFirstQuote != null)
                <accordian :title="'#{{ $supplierFirstQuote->id }}  {{ !empty($supplierFirstQuote->products) ? $supplierFirstQuote->products->first()->name : ''}}'"
                    :active="true">
                    <div slot="body">
                        @if ($supplierLastQuote->status)

                            <div class="page-content">
                                <div class="mp-quote-request-thread">
                                    <div class="mp-qs-thread">

                                        <div class="mp-qs-thread-body">

                                            <div class="mp-qs-item-summary">
                                                <div class="mp-qs-item-summary-col">
                                                    <div class="mp-qs-item-summary-col-label">
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.requested-qty') }}
                                                    </div>

                                                    <div class="mp-qs-item-summary-col-content">
                                                        {{ $customerQuote->quantity }}
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.units') }}</div>
                                                </div>

                                                <div class="mp-qs-item-summary-col">
                                                    <div class="mp-qs-item-summary-col-label">
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.expected-price') }}
                                                    </div>

                                                    <div class="mp-qs-item-summary-col-content">
                                                        {{ core()->currency($customerQuote->price_per_quantity) }}
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.per-unit') }}
                                                    </div>
                                                </div>

                                                <div class="mp-qs-item-summary-col">
                                                    <div class="mp-qs-item-summary-col-label">
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.require-sample') }}
                                                    </div>
                                                    <div class="mp-qs-item-summary-col-content">
                                                        {{ $customerQuote->is_sample ? 'Yes' : 'No' }}
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- supplier messsage --}}
                                            @foreach ($supplierQuotes as $supplierQuote)
                                                <div class="mp-qs-thread-content">
                                                    <div class="mp-qs-summary-icon you">
                                                        <img class="icon b2bcustomer-icon active" style="margin: -1px;">
                                                    </div>

                                                    <div class="mp-qs-summary-details">
                                                        <div class="mp-qs-summary-buyer-name">{{ $supplierName }}
                                                            <span class="mp-qs-summary-buyer-name-label">You</span>
                                                        </div>

                                                        <div class="mp-qs-summary-time">{{ $supplierQuote->created_at }}
                                                        </div>

                                                        <div class="mp-qs-summary-quote-details">

                                                            <div class="mp-qs-summary-quotation-block">
                                                                <div class="mp-qs-summary-quotation-head">
                                                                    <span>
                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.quotation') }}
                                                                        {{ $supplierQuote->id }}
                                                                    </span>
                                                                </div>

                                                                <div class="mp-qs-summary-quotation-body table">
                                                                    <div class="table-responsive">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>{{ __('b2b_marketplace::app.supplier.account.rfq.quote-quantity') }}
                                                                                    </th>
                                                                                    <th>{{ __('b2b_marketplace::app.supplier.account.rfq.quote-price') }}
                                                                                    </th>
                                                                                    <th>{{ __('b2b_marketplace::app.supplier.account.rfq.samples') }}
                                                                                    </th>
                                                                                    <th>{{ __('b2b_marketplace::app.supplier.account.rfq.shipping') }}
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ $supplierQuote->quantity }}
                                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.units') }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ core()->currency($supplierQuote->price_per_quantity) }}
                                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.per-unit') }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <span>{{ $supplierQuote->is_sample ? 'Yes' : 'No' }}</span>
                                                                                             <span>
                                                                                            {{ $supplierQuote->is_sample ? $supplierQuote->sample_unit : '' }}</span>
                                                                                        <span>{{ $supplierQuote->is_sample ? __('b2b_marketplace::app.supplier.account.rfq.units') : '' }}</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span>{{ $supplierQuote->shipping_time }}</span>
                                                                                        <span>
                                                                                            {{ __('b2b_marketplace::app.supplier.account.rfq.days') }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                 
                                                                    @if ($supplierQuote->is_sample && $supplierQuote->is_sample_price)
                                                                        <div class="mp-qs-summary-quotation-row">
                                                                            <div class="mp-qs-summary-quotation-col">
                                                                                <div
                                                                                    class="mp-qs-summary-quotation-col-label">
                                                                                    <span>
                                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.charge') }}
                                                                                    </span>
                                                                                </div>

                                                                                <div
                                                                                    class="mp-qs-summary-quotation-col-content">
                                                                                    <span>
                                                                                        {{ core()->currency($supplierQuote->sample_price) }}
                                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.per-unit') }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div class="mp-qs-summary-quotation-row">
                                                                        <div class="quote-total-label-col">
                                                                            <span>
                                                                                {{ __('b2b_marketplace::app.supplier.account.rfq.total-price') }}
                                                                            </span>
                                                                        </div>

                                                                        <div class="quote-total-amount-col">
                                                                            {{ core()->currency($supplierQuote->price_per_quantity * $supplierQuote->quantity) }}
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mp-qs-summary-quote-desc-msg">
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

                                                        <div class="mp-qs-summary-icon you">
                                                            <img class="icon b2bcustomer-icon active" style="margin: -1px;">
                                                        </div>

                                                        <div class="mp-qs-summary-details">
                                                            @if ($message->customer_id == null)
                                                                <div class="mp-qs-summary-buyer-name">{{ $supplierName }}
                                                                    <span class="mp-qs-summary-buyer-name-label">
                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.you') }}
                                                                    </span>
                                                                </div>

                                                                <div class="mp-qs-summary-time">{{ $message->created_at }}
                                                                </div>
                                                                <div class="mp-qs-summary-quote-details">
                                                                    <div class="mp-qs-summary-quote-desc-msg">
                                                                        {{ $message->message }}</div>
                                                                </div>
                                                            @else
                                                                <div class="mp-qs-summary-buyer-name">{{ $customerName }}
                                                                    <span class="mp-qs-summary-buyer-name-label">
                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.customer') }}
                                                                    </span>
                                                                </div>

                                                                <div class="mp-qs-summary-time">{{ $message->created_at }}
                                                                </div>
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

                                                        <div class="mp-qs-summary-icon you">
                                                            <img class="icon b2bcustomer-icon active"
                                                                style="margin: -1px;">
                                                        </div>

                                                        <div class="mp-qs-summary-details">
                                                            <div class="mp-qs-summary-buyer-name">{{ $customerName }}
                                                                <span class="mp-qs-summary-buyer-name-label">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.customer') }}
                                                                </span>
                                                            </div>

                                                            <div class="mp-qs-summary-time">
                                                                {{ $customerQuote->created_at }}</div>

                                                            <div class="mp-qs-summary-quote-details">
                                                                <div class="mp-qs-summary-quote-desc">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.approved') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- button section --}}
                                            <div class="mp-qs-thread-content">

                                                <div class="mp-qs-summary-icon you">
                                                    <img class="icon b2bcustomer-icon active" style="margin: -1px;">
                                                </div>

                                                <div class="mp-qs-summary-details" style="border: none;">
                                                    <div class="mp-qs-summary-buyer-name">{{ $supplierName }}
                                                        <span class="mp-qs-summary-buyer-name-label">
                                                            {{ __('b2b_marketplace::app.supplier.account.rfq.you') }}
                                                        </span>
                                                    </div>

                                                    @if (!$supplierLastQuote->is_ordered)
                                                        <div class="mp-qs-summary-quote-details">
                                                            <div class="buttone-group">
                                                                <a id="quoteMessage"
                                                                    class="btn btn-lg btn-primary seperate"
                                                                    @click="showModal('quoteMessage')">
                                                                    <span>
                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.message') }}
                                                                    </span>
                                                                </a>

                                                                <a href="{{ route('b2b_marketplace.supplier.leads.send-quote.create', [
                                                                    $quote['customer_id'],
                                                                    $customerQuote['product_id'],
                                                                ]) }}"
                                                                    class="btn btn-lg btn-primary seperate">
                                                                    {{ __('b2b_marketplace::app.supplier.account.rfq.quote-again') }}
                                                                </a>

                                                                @if ($supplierQuote['status'] != 'Rejected')
                                                                    <a href="{{ route('b2b_marketplace.supplier.rfq.reject', [$supplierLastQuote['id'], $customerQuote['id']]) }}"
                                                                        class="btn btn-lg btn-primary seperate">
                                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.reject-request') }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="mp-qs-summary-quote-details">
                                                            <div class="mp-qs-summary-quote-desc">
                                                                {{ __('Ordered') }}
                                                            </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <modal id="quoteMessage" :is-open="modalIds.quoteMessage">
                                <h3 slot="header">
                                    {{ __('b2b_marketplace::app.supplier.account.rfq.message') }}
                                </h3>

                                <div slot="body">
                                    <form method="POST"
                                        action="{{ route('b2b_marketplace.supplier.request-quote.message', [
                                            $supplierFirstQuote->id,
                                            $supplierFirstQuote->supplier_id,
                                            $customerQuote->id,
                                        ]) }}"
                                        enctype="multipart/form-data" @submit.prevent="onSubmit">
                                        @csrf

                                        <div class="b2b-quote-request-section">
                                            <div class="b2b-quote-request-section-content">
                                                <div class="control-group"
                                                    :class="[errors.has('message') ? 'has-error' : '']">
                                                    <label for="text" class="required">
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.enter-message') }}
                                                        <i class="export-icon"></i>
                                                    </label>
                                                    <textarea title="Note For Customer" type="text" v-validate="'required'" class="control" name="message"
                                                        value="{{ old('message') }}" data-vv-as="&quot;{{ __('message') }}&quot;"></textarea>
                                                    <span class="control-error"
                                                        v-if="errors.has('message')">@{{ errors.first('message') }}</span>
                                                </div>

                                                <div class="buttone-group">
                                                    <button class="btn btn-lg btn-primary">
                                                        {{ __('b2b_marketplace::app.supplier.account.rfq.send-message') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </modal>
                        @endif
                    </div>
                </accordian>
            @endif
        </div>
    </div>

@endsection
