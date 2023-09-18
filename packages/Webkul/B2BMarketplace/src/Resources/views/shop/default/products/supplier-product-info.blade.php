@push('css')
    <style>
        .product-detail .supplier-info {
            margin-bottom: 15px;
        }

        .supplier-info .star-blue-icon {
            vertical-align: text-top;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('themes/b2b/assets/css/b2b-marketplace.css') }}">
@endpush

<?php
    $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository');
    $reviewRepository = app('Webkul\B2BMarketplace\Repositories\ReviewRepository');
    $supplier = $productRepository->getSupplierByProductId($product->product_id);
    $isApproved = $productRepository->getApprovedProduct($product->product_id);
    $supplierId = $supplier ? $supplier->id : null;
    $productFlags = app('Webkul\B2BMarketplace\Repositories\ProductFlagReasonRepository')->findWhere(['status' => 1]);
?>

@if ($supplier && $supplier->is_approved && $isApproved != false)

    <div class="supplier-info">
        <a target="new" href="{{route('b2b_marketplace.supplier.show', $supplier->url)}}" style="color:#0041ff">
            {{ __('b2b_marketplace::app.shop.supplier.profile.visit-website') }}
            <i class='icon star-blue-icon'></i>
            {{$reviewRepository->getAverageRating($supplier)}}
        </a>
    </div>

    <div class="supplier-section" style="margin: 0 0 10px;">

        <a id="shopMessage"class="btn btn-lg btn-primary" href="#" @click="showModal('shopMessage')">
            {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
        </a>

        <modal id="shopMessage" :is-open="modalIds.shopMessage" style="z-index:9999 !important;">

            <h3 slot='header'>{{$supplier->company_name}}</h3>

            <div slot="body">
                @if($supplier->is_verified)
                    <div style="display:grid; padding:3px ; margin-top: -42px;">
                        <span class="verified-supplier" style="color: green;">
                            {{ __('b2b_marketplace::app.shop.supplier.profile.verified') }}
                        </span>

                        <span class="icon verification-icon active"></span>
                    </div>
                @endif

                <message-form></message-form>
            </div>
        </modal>
    </div>
@else
    @if ( (core()->getConfigData('b2b_marketplace.settings.product_flag.status')))
        <div class="report-flag">
            <a href="javascript:void(0);"  @click="showModal('reportAdminFlag')">
                <i class="material-icons"></i>
                {{core()->getConfigData('b2b_marketplace.settings.product_flag.text')}}
            </a>
            <modal id="reportAdminFlag" :is-open="modalIds.reportAdminFlag">
                <h3 slot="header">
                    {{ __('b2b_marketplace::app.shop.flag.title') }}
                </h3>

                <div slot="body">
                    <adminproduct-flag-form></adminproduct-flag-form>
                </div>
            </modal>
        </div>
    @endif
@endif

@push('scripts')

    <script type="text/x-template" id="adminflag-form-template">
        <form method="POST" action="" data-vv-scope="adminreport-form" enctype="multipart/form-data">
            @csrf()

            <div class="control-group" :class="[errors.has('adminreport-form.name') ? 'has-error' : '']">
                <label for="name" class="required label-style">{{ __('b2b_marketplace::app.shop.flag.name') }}</label>
                <input v-validate="'required'" v-model="data.name" type="text" class="form-style" id="name" name="name" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.name') }}&quot;" value="{{ old('name') }}"/>
                <span class="control-error" v-if="errors.has('adminreport-form.name')">@{{ errors.first('adminreport-form.name') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('adminreport-form.email') ? 'has-error' : '']">
                <label for="type" class="required label-style">{{ __('b2b_marketplace::app.shop.flag.email') }}</label>
            <input type="email" v-validate="'required'" v-model="data.email" class="form-style" id="email" name="email" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.email') }}&quot;" value="{{ old('email') }}" />
                <span class="control-error" v-if="errors.has('adminreport-form.email')">@{{ errors.first('adminreport-form.email') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('adminreport-form.selected_reason') ? 'has-error' : '']">
                <label for="selected_reason" class="label-style">{{ __('b2b_marketplace::app.shop.flag.reason') }}</label>

                <select name="selected_reason" id="selected_reason" v-model="data.selected_reason" class="form-style" v-validate="'required'">
                    @foreach ($productFlags as $flag)
                        <option value="{{$flag->reason}}">{{$flag->reason}}</option>
                    @endforeach
                    <option value="other">Other</option>
                </select>

                <span class="control-error" v-if="errors.has('adminreport-form.selected_reason')">@{{ errors.first('adminreport-form.selected_reason') }}</span>

                @if (core()->getConfigData('b2b_marketplace.settings.product_flag.other_reason'))
                    <div class="control-group" :class="[errors.has('adminreport-form.reason') ? 'has-error' : '']">
                    <textarea class="form-style mt-3" v-validate="'required'" v-model="data.reason" id="other-reason" v-if="data.selected_reason == 'other'" placeholder="{{core()->getConfigData('b2b_marketplace.settings.product_flag.other_placeholder')}}" name="reason" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.reason') }}&quot;">
                    </textarea>
                    <span class="control-error" v-if="errors.has('adminreport-form.reason')">@{{ errors.first('adminreport-form.reason') }}</span>
                @endif
            </div>

            <div class="mt-5">
                @auth('customer')
                    <button type="button" class="theme-btn" @click="reportProduct('adminreport-form')">
                        {{ __('b2b_marketplace::app.shop.flag.submit') }}
                    </button>
                @endauth

                @guest('customer')
                    @if (core()->getConfigData('b2b_marketplace.settings.product_flag.guest_can'))
                        <button type="button" class="theme-btn" @click="reportProduct('adminreport-form')">
                            {{ __('b2b_marketplace::app.shop.flag.submit') }}
                        </button>
                    @else
                        <a href="{{route('customer.session.index')}}" class="theme-btn"> {{ __('b2b_marketplace::app.shop.flag.login') }}</a>
                    @endif
                @endguest
            </div>

        </form>
    </script>

    <script type="text/x-template" id="message-form-template">

        <form action="" method="POST" data-vv-scope="message-form" enctype="multipart/form-data" @submit.prevent="sendMessage('message-form')">
            @csrf

            <div class="b2b-quote-request-section">
                <div class="b2b-quote-request-section-content">

                    <div class="control-group" :class="[errors.has('message-form.message') ? 'has-error' : '']">
                        <label for="subject" class="required">
                            {{ __('b2b_marketplace::app.shop.supplier.profile.write-message')}}
                        </label>

                        <textarea title="Note For Customer"type="text" v-model="data.message" class="control" name="message" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.message')}}&quot;">
                        </textarea>
                        <span class="control-error" v-if="errors.has('message-form.message')">@{{ errors.first('message-form.message') }}</span>
                    </div>

                    <div class="buttone-group">
                        <button class="btn btn-lg btn-primary" :disabled="disable_button">
                            {{ __('b2b_marketplace::app.shop.supplier.profile.send')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </script>

    <script>

        Vue.component('message-form', {

            template: '#message-form-template',
            data: () => ({
                data: {
                    message: '',
                    customer_id: '',
                    supplier_id: '{{$supplierId}}',
                },

                disable_button: false,
            }),

            created () {
                @if (auth()->guard('customer')->check())
                    this.data.customer_id = "{{auth()->guard('customer')->user()->id}}";
                @else
                    this.data.customer_id = "{{null}}";
                @endif
            },

            methods: {

                sendMessage (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post ("{{ route('b2b_marketplace.customers.account.supplier.messages.storeProductMsg') }}", this.data)
                            .then (response => {

                                this_this.disable_button = false;

                                this_this.$parent.closeModal();

                                window.flashMessages = [{
                                    'type': 'alert-success',
                                    'message': response.data.message
                                }];

                                this_this.$root.addFlashMessages();
                            })

                            .catch (function (error) {
                                this_this.disable_button = false;

                                this_this.handleErrorResponse(error.response, 'message-form')
                            })
                        } else {
                            this_this.disable_button = false;
                        }
                    });
                },

                handleErrorResponse (response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    }
                }
            }

        });

        Vue.component('adminproduct-flag-form', {
            template: '#adminflag-form-template',
            data: () => ({
                data: {
                    selected_reason: '',
                    product_id: "{{$product->product->id}}",
                    supplier_id: 0,
                    reason: '',
                }
            }),

            methods: {

                reportProduct (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post ("{{ route('b2b_marketplace.flag.product.store') }}", this.data)
                            .then (response => {

                                this_this.$parent.closeModal();

                                if(response.data.success) {

                                    window.flashMessages = [{
                                        'type': 'alert-success',
                                        'message': response.data.message
                                    }];

                                    this_this.$root.addFlashMessages();

                                } else {

                                    window.flashMessages = [{
                                        'type': 'alert-error',
                                        'message': response.data.message
                                    }];

                                    this_this.$root.addFlashMessages();
                                }

                                this_this.disable_button = false;
                            })
                            .catch (function (error) {
                                this_this.disable_button = false;

                                this_this.handleErrorResponse(error.response, 'report-form')
                            })
                        } else {
                            this_this.disable_button = false;
                        }
                    });
                },

                handleErrorResponse (response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    }
                }
            }

        });

    </script>
@endpush