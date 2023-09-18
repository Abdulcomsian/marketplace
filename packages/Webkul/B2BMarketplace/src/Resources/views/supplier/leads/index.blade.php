@extends('b2b_marketplace::supplier.layouts.content')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.account.sales.leads.title') }}
@endsection

@section('content')

<div class="main-page-wrapper">

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('b2b_marketplace::app.shop.supplier.account.sales.leads.title') }}</h1>
            </div>
        </div>
    </div>
    <send-message></send-message>
</div>

<script type="text/x-template" id="send-message-template">
    <div class="b2b-buying-lead-container">
        @if (count($requestedQuote) > 0)
            @foreach($requestedQuote as $key=>$quote)
                @foreach($quote['customer_quote'] as $quoteItems)

                    @if($quoteItems['is_requested_quote'] && ! isset($quoteItems['supplier_id']))
                        <div class="b2b-buying-lead-list">
                            <div class="b2b-buying-lead-list-item">
                                <div class="b2b-buying-leads-list-item-row">
                                <span class="b2b-buying-lead-id">#{{$quote['id']}}</span>

                                    <span class="b2b-buying-lead-date">
                                        <span class="b2b-buying-lead-label">
                                            {{ __('b2b_marketplace::app.supplier.account.lead.posted-on') }}
                                        </span>
                                        <span class="b2b-buying-lead-content">{{$quote['postedOn']}}</span>
                                    </span>
                                </div>

                                <div class="b2b-buying-leads-list-item-row">
                                    <span class="b2b-buying-lead-product-name">
                                        {{$quote['productName']->name}}
                                    </span>
                                </div>

                                <div class="b2b-buying-leads-list-item-row">
                                    <span class="b2b-buying-lead-product-description">
                                        {{$quoteItems['description']}}
                                    </span>
                                </div>

                                <div class="b2b-buying-leads-list-item-row">
                                    <div class="b2b-buying-leads-list-item-col">
                                        <span class="b2b-buying-leads-list-item-col-label">
                                            {{ __('b2b_marketplace::app.supplier.account.lead.quantity') }}
                                        </span>

                                        <span class="b2b-buying-leads-list-item-col-content">{{$quoteItems['quantity']}}</span>
                                    </div>

                                    <div class="b2b-buying-leads-list-item-col">
                                        <span class="b2b-buying-leads-list-item-col-label">
                                            {{ __('b2b_marketplace::app.supplier.account.lead.price') }}
                                        </span>
                                        <span class="b2b-buying-leads-list-item-col-content">
                                            {{ core()->currency($quoteItems['price_per_quantity']) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="b2b-buying-leads-list-item-row">
                                    @foreach(json_decode($quoteItems['categories']) as $key => $category)
                                        <?php
                                            $categoryName = [];
                                            $categories = app('Webkul\Category\Repositories\CategoryRepository')->get();
                                            foreach ($categories as $translation) {

                                                $categoryData= $translation['translations']->where('category_id', $category)->first();

                                                if ($categoryData != null) {
                                                    $categoryName[$key] = $categoryData;

                                                }
                                            }

                                        ?>
                                        @if (isset($categoryName[$key]))
                                            <div class="b2b-buying-lead-item-categories">
                                                <div class="b2b-buying-lead-item-category">{{$categoryName[$key]->name}}</div>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>

                                <div class="b2b-buying-leads-list-item-actions">
                                    <div class="b2b-buying-lead-item-customer-info">
                                        <div class="b2b-buying-lead-item-customer-name-section">
                                            <span class="b2b-buying-lead-item-customer-info-label">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.posted-by') }}
                                            </span>

                                            <span class="b2b-buying-lead-item-customer-info-content">{{$quote['postedBy']}}</span>
                                        </div>

                                        <div class="b2b-buying-lead-item-customer-location-section">
                                            <span class="b2b-buying-lead-item-customer-info-label">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.location') }}
                                            </span>

                                            <span class="b2b-buying-lead-item-customer-info-content">{{$quote['address']}}</span>
                                        </div>
                                    </div>
                                    <div class="b2b-buying-lead-item-button-set">

                                        <a id="supplierMessages" class="btn btn-lg btn-primary" @click="openMessage({{$quote['customer_id']}})">{{ __('b2b_marketplace::app.supplier.account.lead.message') }}</a>

                                        <a href="{{route('b2b_marketplace.supplier.leads.send-quote.create', [
                                                $quote['customer_id'],
                                                $quoteItems['product_id']
                                            ])}}" class="btn btn-lg btn-primary" style="background: #00BF44 !important">

                                            {{ __('b2b_marketplace::app.supplier.account.lead.send-quote') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <modal id="supplierMessage" :is-open="$root.$root.modalIds.supplierMessage">
                            <h3 slot="header">{{ __('b2b_marketplace::app.supplier.account.lead.message') }}</h3>

                            <div slot="body">
                                <form method="POST" action="" enctype="multipart/form-data" @submit.prevent="sendMessage({{$quote['customer_id']}})">
                                    @csrf

                                    <div class="b2b-quote-request-section">
                                        <div class="b2b-quote-request-section-content">
                                            <div class="control-group" :class="[errors.has('message') ? 'has-error' : '']">
                                                <label for="text" class="required">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.write-msg') }}
                                                    <i class="export-icon"></i>
                                                </label>

                                                <textarea title="Note For Customer" type="text" v-validate="'required'" class="control" name="message" v-model="message" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.message') }}&quot;" ></textarea>
                                                <span class="control-error" v-if="errors.has('message')">@{{ errors.first('message') }}</span>
                                            </div>

                                            <input type="hidden" name="customer_id" :value="customer_id" v-if="customer_id != 0">

                                            <div class="buttone-group">
                                                <button class="btn btn-lg btn-primary" type="submit" :disabled="disable_button">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.send') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </modal>
                    @endif
                @endforeach
            @endforeach

        @else
            <div class="supplier-verification" style="
                display: inline-block;
                width: 100%;
                box-sizing: border-box;
                padding: 10px;
                background: #FFFBD4;
                margin-bottom: 15px;
                border-radius: 2px;
                border: 1px solid #E9DF80;
                color: #333;
                ">
                <span>
                    {{ __('b2b_marketplace::app.supplier.account.lead.no-lead') }}
                </span>
            </div>
        @endif
    </div>
</script>
@endsection

@push('scripts')
    <script>
        Vue.component('send-message', {

            template: '#send-message-template',

            data: () => ({
                customer_id: 0,
                message: '',
                disable_button: false
            }),

            methods: {
                openMessage(customerId) {
                    this_this = this;
                    this_this.customer_id = customerId;

                    this.$root.$root.showModal('supplierMessage');
                },

                sendMessage(customer) {

                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll().then((result) => {
                        if (result) {

                            this.$http.post ("{{ route('b2b_marketplace.supplier.leads.messsage.store') }}", {customer_id: this_this.customer_id, message: this_this.message })
                                .then (response => {
                                    if (response.data.success) {
                                        window.flashMessages = [{
                                            'type': 'alert-success',
                                            'message': response.data.message
                                        }];

                                        this.$root.addFlashMessages();

                                        window.location.reload();
                                    }
                                })
                                .catch (function (error) {
                                    this_this.disable_button = false;
                                })
                        } else {
                            this_this.disable_button = false;
                        }
                    });
                }
            }
        });

    </script>

@endpush