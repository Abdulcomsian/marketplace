@extends('b2b_marketplace::supplier.layouts.rfq-tabs')

@section('page_title')
    {{ __('b2b_marketplace::app.supplier.account.lead.title') }}
@endsection

@section('content')
<div class="main-page-wrapper">

    <buying-lead></buying-lead>
</div>
@endsection

@push('scripts')
<script type="text/x-template" id="buying-lead-template">

    <form method="POST" action="" enctype="multipart/form-data" data-vv-scope="lead-form">
        @csrf()
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/supplier/dashboard') }}';"></i>

                        {{ __('b2b_marketplace::app.supplier.account.lead.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="button" class="btn btn-lg btn-primary" @click="validateForm('lead-form')">
                        {{ __('b2b_marketplace::app.supplier.account.lead.send-quote') }}
                    </button>
                </div>
            </div>
        </div>

        <?php $supplier = auth()->guard('supplier')->user();?>

        <div class="mp-main-page-content">
            <div class="mp-columns-content">
                <div class="mp-column-mp-main">
                    <div class="mp-rfq-container">
                        <div class="mp-rfq-data-container">
                            <div class="mp-rfq-data-header">
                                <div class="b2b-quote-customer-info-block">

                                    <div class="mp-qs-summary-icon you" style="
                                    display: inline-block;
                                    width: 40px;
                                    height: 40px;
                                    border: 1px solid #ccc;
                                    border-radius: 33px;
                                    overflow: hidden;
                                    margin: 4px 1px 0px 1px;">
                                        <img class="icon b2bcustomer-icon active" style="margin: -1px;">
                                    </div>
                                    <div class="b2b-quote-subtitle">
                                        {{ __('b2b_marketplace::app.supplier.account.lead.send-quote-to') }}
                                        <span class="b2b-rfq-buyer-name">
                                            {{$supplier->first_name . ' '. $supplier->last_name}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="b2b-quote-item-viewinfo">
                                <div class="b2b-quote-items-container">
                                    <div class="mp-rfq-item-container">
                                        <div class="mp-rfq-item-row-primary">
                                            <div class="mp-qs-item-name">
                                                {{$rfqItem->product_name}}
                                            </div>
                                        </div>

                                        <div class="mp-rfq-item-row-primary">
                                            <div class="mp-qs-item-name">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.description') }}
                                            </div>
                                            <span>{{$rfqItem->description}}</span>
                                        </div>

                                        <div class="mp-rfq-item-row-primary">
                                            <div class="mp-rfq-item-primary-row">
                                                <div class="mp-rfq-item-col">
                                                    <span class="mp-rfq-item-label">
                                                        {{ __('b2b_marketplace::app.supplier.account.lead.quantity') }}
                                                    </span>

                                                    <span class="mp-rfq-item-content">
                                                        {{$rfqItem->quantity}}
                                                        {{ __('b2b_marketplace::app.supplier.account.lead.unit') }}
                                                    </span>
                                                </div>

                                                <div class="mp-rfq-item-col">
                                                    <span class="mp-rfq-item-label">
                                                        {{ __('b2b_marketplace::app.supplier.account.lead.expected-price') }}
                                                    </span>

                                                    <span class="mp-rfq-item-content">
                                                        {{ core()->currency($rfqItem->price_per_quantity)}}
                                                        {{ __('b2b_marketplace::app.supplier.account.lead.per-unit') }}
                                                    </span>
                                                </div>

                                                <div class="mp-rfq-item-col">
                                                    <span class="mp-rfq-item-label">
                                                        {{ __('b2b_marketplace::app.supplier.account.lead.expected-total') }}
                                                    </span>

                                                    <span class="mp-rfq-item-content">
                                                        {{ core()->currency($rfqItem->price_per_quantity * $rfqItem->quantity)}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mp-rfq-item-row-primary">
                                            <div class="mp-rfq-item-col">
                                                <span class="mp-rfq-item-label">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.requires-samples') }}
                                                </span>

                                                <span class="mp-rfq-item-content">{{$rfqItem->is_sample ? 'Yes' : 'No'}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="b2b-quote-request-block">
                                <div class="b2b-quote-request-section">

                                    <div class="b2b-quote-request-section-title">
                                        {{ __('b2b_marketplace::app.supplier.account.lead.quote-info') }}
                                    </div>

                                    <div class="b2b-quote-request-section-content">

                                        <div class="control-group" :class="[errors.has('lead-form.quantity') ? 'has-error' : '']">
                                            <label for="text" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.quote-quantity') }}
                                                <i class="export-icon"></i>
                                            </label>

                                            <input type="text" v-validate="'required|numeric|min_value:1'" class="control" name="quantity" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.quote-quantity') }}&quot;" v-model="leadData.quantity">
                                            <span class="control-error" v-if="errors.has('lead-form.quantity')">@{{ errors.first('lead-form.quantity') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('lead-form.price_per_quantity') ? 'has-error' : '']">
                                            <label for="text" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.quote-per-quantity') }}<i class="export-icon"></i>
                                            </label>

                                            <input type="text" v-validate="'required|decimal|min_value:0.001'" class="control" name="price_per_quantity" v-model="leadData.price_per_quantity" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.quote-per-quantity') }}&quot;">
                                            <span class="control-error" v-if="errors.has('lead-form.price_per_quantity')">@{{ errors.first('lead-form.price_per_quantity') }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="b2b-quote-request-section">

                                    <div class="b2b-quote-request-section-title">
                                        {{ __('b2b_marketplace::app.supplier.account.lead.sample-info') }}
                                    </div>

                                    <div class="b2b-quote-request-section-content">

                                        <div class="control-group" :class="[errors.has('lead-form.is_sample') ? 'has-error' : '']">
                                            <label for="status" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.samples') }}
                                                <i class="export-icon"></i>
                                            </label>

                                            <select class="control" v-validate="'required'" id="is_sample" name="is_sample" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.samples') }}&quot;" v-model="leadData.is_sample">
                                                <option value="1">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.yes') }}
                                                </option>

                                                <option value="0">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.no') }}
                                                </option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('lead-form.is_sample')">@{{ errors.first('lead-form.is_sample') }}</span>
                                        </div>


                                        <div v-if="leadData.is_sample == '1'" class="control-group" :class="[errors.has('lead-form.sample_unit') ? 'has-error' : '']">
                                            <label for="text" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.sample-unit') }}
                                                <i class="export-icon"></i>
                                            </label>

                                            <input type="text" v-validate="'required|numeric|min_value:1'" class="control" name="sample_unit" v-model="leadData.sample_unit" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.sample-unit') }}&quot;">

                                            <span class="control-error" v-if="errors.has('lead-form.sample_unit')">@{{ errors.first('lead-form.sample_unit') }}</span>
                                        </div>

                                        <div v-if="leadData.is_sample == '1'" class="control-group" :class="[errors.has('lead-form.is_sample_price') ? 'has-error' : '']">
                                            <label for="status" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.sample-charge') }}
                                                <i class="export-icon"></i>
                                            </label>
                                            <select class="control" v-validate="'required'" id="is_sample_price" name="is_sample_price" data-vv-as="&quot; {{ __('b2b_marketplace::app.supplier.account.lead.sample-charge') }}&quot;"  v-model="leadData.is_sample_price">
                                                <option value="1">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.applicable') }}
                                                </option>

                                                <option value="0">
                                                    {{ __('b2b_marketplace::app.supplier.account.lead.not-applicable') }}
                                                </option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('lead-form.is_sample_price')">@{{ errors.first('lead-form.is_sample_price') }}</span>
                                        </div>

                                        <div v-if="leadData.is_sample == '1' && leadData.is_sample_price == '1'" class="control-group" :class="[errors.has('lead-form.sample_price') ? 'has-error' : '']">
                                            <label for="text" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.charge-per-unit') }}
                                                <i class="export-icon"></i>
                                            </label>

                                            <input type="text" v-validate="'required|decimal|min_value:0.001'" class="control" name="sample_price" v-model="leadData.sample_price" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.charge-per-unit') }}&quot;">

                                            <span class="control-error" v-if="errors.has('lead-form.sample_price')">@{{ errors.first('lead-form.sample_price') }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="b2b-quote-request-section">

                                    <div class="b2b-quote-request-section-title">
                                        {{ __('b2b_marketplace::app.supplier.account.lead.shipping-info') }}
                                    </div>

                                    <div class="b2b-quote-request-section-content">

                                        <div class="control-group" :class="[errors.has('lead-form.shipping_time') ? 'has-error' : '']">
                                            <label for="text" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.shipping-time') }}
                                                <i class="export-icon"></i>
                                            </label>

                                            <input type="text" v-validate="'required|numeric|min:0'" class="control" name="shipping_time" v-model="leadData.shipping_time" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.shipping-time') }}&quot;">
                                            <span class="control-error" v-if="errors.has('lead-form.shipping_time')">@{{ errors.first('lead-form.shipping_time') }}</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="b2b-quote-request-section">
                                    <div class="b2b-quote-request-section-content">

                                        <div class="control-group" :class="[errors.has('lead-form.note') ? 'has-error' : '']">
                                            <label for="text" class="required">
                                                {{ __('b2b_marketplace::app.supplier.account.lead.note') }}
                                                <i class="export-icon"></i>
                                            </label>

                                            <textarea rows="5" cols="120" title="{{__('b2b_marketplace::app.supplier.account.lead.note')}}" type="text" v-validate="'required'" class="control" name="note" v-model="leadData.note" data-vv-as="&quot;{{ __('b2b_marketplace::app.supplier.account.lead.note') }}&quot;"></textarea>

                                            <span class="control-error" v-if="errors.has('lead-form.note')">@{{ errors.first('lead-form.note') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

</script>

<script>

    Vue.component('buying-lead', {
        template: "#buying-lead-template",

        data: function() {
            return {
                leadData: {
                    'is_sample': 1,
                    'is_sample_price': 1,
                    'sample_unit': '',
                    'sample_price': '',
                    'product_id': '{{$rfqItem->product_id}}',
                    'product_name': '{{$rfqItem->product_name}}',
                },
            }
        },

        methods: {
            validateForm(scope) {

                var this_this = this;
                this.$validator.validateAll(scope).then(function (result) {
                    if (result) {
                        if (scope == 'lead-form') {
                            if(this_this.leadData.is_sample == 0) {

                                this_this.leadData.is_sample_price = 0;
                                this_this.leadData.sample_unit  = '';
                                this_this.leadData.sample_price = '';

                            } else if(this_this.leadData.is_sample_price == 0){

                                this_this.leadData.sample_price = '';
                            }

                            this_this.sendQuote();
                        }
                    }
                });
            },

            sendQuote: function() {
                var this_this = this;

                this.$http.post("{{ route('b2b_marketplace.supplier.leads.send-quote.store', [$id, $rfqItem->quote_id] ) }}", this_this.leadData
                ) .then(response => {

                    if (response.data.success) {

                        window.flashMessages = [{
                            'type': 'alert-success',
                            'message': response.data.message
                        }];

                        this.$root.addFlashMessages();

                        window.location.href = "{{ route('b2b_marketplace.supplier.request-quote.answered.index') }}";
                    } else {
                        location.reload();
                    }

                }).catch(function (error) {
                    location.reload();
                });

            },
        }

    });

</script>

@endpush