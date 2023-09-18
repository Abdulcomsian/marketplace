@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.signup-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="cp-spinner cp-round spinner" id="loader"> </div>
    <checkout></checkout>

@endsection

@push('scripts')
    <script type="text/x-template" id="checkout-template">
        <div id="checkout" class="checkout-process">
            <div class="col-main" style="margin-left: auto;margin-right: auto; padding: 15px; width: 40%; box-shadow: 2px -2px 5px 3px #C7C7C7;">
                <div class="login-form">
                    <ul class="checkout-steps" >
                        <li class="active" :class="[completedStep >= 0 ? 'active' : '', completedStep > 0 ? 'completed' : '']">
                            {{-- @click="navigateToStep(1)" --}}
                            <div class="decorator customer-icon" style="box-shadow: 1px 2px 3px 0px #c7c7c7; border:none;"></div>
                            <span>{{ __('b2b_marketplace::app.shop.supplier.signup-form.account-info') }}</span>
                        </li>

                        <div class="line mb-25"></div>

                        <li :class="[currentStep == 2 || completedStep > 1 ? 'active' : '', completedStep > 1 ? 'completed' : '']">
                            {{-- @click="navigateToStep(2)" --}}
                                <div class="decorator order-icon" style="box-shadow: 1px 2px 3px 0px #c7c7c7; border:none;"></div>
                                <span>{{ __('b2b_marketplace::app.shop.supplier.signup-form.personal-info') }}</span>
                        </li>

                        <div class="line mb-25"></div>
                    </ul>
                </div>

                <div class="auth-content" v-show="currentStep == 1" id="address-section">
                    <div class="login-form">
                        @include('b2b_marketplace::shop.customers.signup.onepage.account-info')

                        <button type="button" class="btn btn-lg btn-primary" @click="validateForm('address-form')" id="checkout-address-continue-button">
                            {{ __('shop::app.checkout.onepage.continue') }}
                        </button>
                    </div>
                </div>

                <div class="auth-content" v-show="currentStep == 2" id="address-section">

                    @include('b2b_marketplace::shop.customers.signup.onepage.personal-info')

                </div>
            </div>
        </div>
    </script>

    <script>

        var shippingHtml = '';

        Vue.component('checkout', {
            template: '#checkout-template',
            inject: ['$validator'],

            data:function() {
                return {
                    currentStep:1,
                    completedStep:0,
                    isShopUrlAvailable: null,

                    billing:{},

                    shipping: {
                        billinginfo: {

                        }
                    },
                }
            },

            methods: {
                navigateToStep : function(step) {
                    if(step <= this.completedStep) {
                        this.currentStep = step
                        this.completedStep = step -1;
                    }
                },

                validateForm: function(scope) {
                    var this_this = this;

                    this.$validator.validateAll(scope).then(function (result) {

                        if(result) {
                            if(scope == 'address-form') {

                                this_this.saveAddress();
                            }

                            if(scope == 'addressinfo-form') {

                                $('.cp-spinner').css({'display': 'block', 'z-index': '12', 'bottom': '600px'});

                                this_this.saveAddressinfo();
                            }
                        }
                    });
                },

                saveAddress: function() {
                    var this_this = this;

                    this.$http.post("{{ route('b2b_marketplaceshop.shop.suppliers.signup.account-information') }}", this.billing)
                        .then(function(response) {
                            if(response.data) {
                                this_this.completedStep = 1;
                                this_this.currentStep = 2;
                            }
                        })

                        .catch(function(error) {
                            this_this.handleErrorResponse(error.response, 'address-form')
                        })
                },

                saveAddressinfo: function() {
                    var this_this = this;
                    this_this.checkShopUrl();

                    this.$http.post("{{ route('b2b_marketplaceshop.shop.suppliers.signup.store') }}", this.billing)
                    .then(function(response) {
                        if(response.data) {
                            this_this.completedStep = 2;
                            window.location.href = "{{ route('b2b_marketplace.shop.supplier.session.index') }}";
                        }
                    })

                    .catch(function(error) {
                        this_this.handleErrorResponse(error.response, 'addressinfo-form')
                    })
                },

                checkShopUrl (shopUrl) {
                    this_this = this;

                    this.$http.post("{{ route('b2b_marketplace.shop.suppliers.checkurl') }}", {'url': shopUrl})
                    .then(function(response) {
                        if (response.data.available && response.data.available != null) {

                            this_this.isShopUrlAvailable = true;
                            document.querySelectorAll("form button.btn")[0].disabled = false;

                        } else if (response.data.available && response.data.available == null) {

                            this_this.isShopUrlAvailable = null;
                            document.querySelectorAll("form button.btn")[0].disabled = false;
                        } else {

                            this_this.isShopUrlAvailable = false;
                            document.querySelectorAll("form button.btn")[0].disabled = true;
                        }
                    })

                    .catch(function (error) {
                        document.querySelectorAll("form button.btn")[0].disabled = true;
                    })
                },


                handleErrorResponse: function(response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    } else if (response.status == 403) {
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        }
                    }
                },
            }
        })
    </script>
@endpush