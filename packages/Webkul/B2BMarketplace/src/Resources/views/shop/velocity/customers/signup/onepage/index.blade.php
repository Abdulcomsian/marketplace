@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ __('b2b_marketplace::app.shop.supplier.signup-form.page-title') }}
@endsection




@section('content-wrapper')
    <div class="auth-content form-container">

        <div class="container">
            {{-- <div class="cp-spinner cp-round spinnervelocity" id="loader"> </div> --}}

            <div class="loader"></div>
            <checkout></checkout>
        </div>
    </div>


@endsection

@push('scripts')
    <script type="text/x-template" id="checkout-template">
        {{-- <div id="checkout" class="checkout-process"> --}}

                <div class="col-lg-10 col-md-12 offset-lg-1">

                    <div class="heading">
                        <h2 class="fs24 fw6">
                            {{ __('Supplier Registration')}}
                        </h2>

                        <a href="{{ route('b2b_marketplace.shop.supplier.session.index') }}" class="btn-new-customer">
                            <button type="button" class="theme-btn light">
                                {{ __('b2b_marketplace::app.shop.supplier.login-form.signin-title')}}
                            </button>
                        </a>
                    </div>

                    <div class="body col-12">
                        <div class="form-header">
                            <h3 class="fw6">
                                {{ __('b2b_marketplace::app.shop.supplier.login-form.registered-user')}}
                            </h3>

                            <p class="fs16">
                                {{ __('b2b_marketplace::app.shop.supplier.login-form.form-login-text')}}
                            </p>
                        </div>

                        {{-- <div class="col-main"> --}}

                            <div class="login-form">
                                <ul class="checkout-steps">

                                    <li class="active" :class="[completedStep >= 0 ? 'active' : '', completedStep > 0 ? 'completed' : '']" style="height: 48px;
                                        display: flex;">

                                        <div class="decorator customer-icon"></div>
                                        <span>{{ __('b2b_marketplace::app.shop.supplier.signup-form.account-info') }}</span>
                                    </li>

                                    {{-- <div class="line mb-25"></div> --}}

                                    <li :class="[currentStep == 2 || completedStep > 1 ? 'active' : '', completedStep > 1 ? 'completed' : '']" style="height: 48px;
                                        display: flex;">

                                            <div class="decorator order-icon"></div>
                                            <span>{{ __('b2b_marketplace::app.shop.supplier.signup-form.personal-info') }}</span>
                                    </li>

                                    {{-- <div class="line mb-25"></div> --}}
                                </ul>
                            </div>

                            <div class="auth-content form-container" v-show="currentStep == 1" id="address-section">
                                {{-- <div class="login-form"> --}}
                                    @include('b2b_marketplace::shop.customers.signup.onepage.account-info')

                                    <button type="button" class="theme-btn" @click="validateForm('address-form')" id="checkout-address-continue-button">
                                        {{ __('shop::app.checkout.onepage.continue') }}
                                    </button>
                                {{-- </div> --}}
                            </div>

                            <div class="auth-content form-container" v-show="currentStep == 2" id="address-section">

                                @include('b2b_marketplace::shop.customers.signup.onepage.personal-info')

                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
            {{-- </div>
        </div> --}}
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
                    disable_btn: false,

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

                                $('.loader').css({'display': 'block', 'z-index': '12', 'bottom': '600px'});
                                this_this.disable_btn = true;

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

                    this.$http.post("{{ route('b2b_marketplaceshop.shop.suppliers.signup.store') }}", this.billing)
                    .then(function(response) {
                        if(response.data) {

                            this_this.disable_btn = false;
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
                            document.getElementById('checkout-place-order-button').disabled = false;
                            document.querySelectorAll("form button.btn")[0].disabled = false;

                        } else if (response.data.available && response.data.available == null) {

                            this_this.isShopUrlAvailable = null;
                            document.getElementById('checkout-place-order-button').disabled = false;
                            document.querySelectorAll("form button.btn")[0].disabled = false;
                        } else {

                            this_this.isShopUrlAvailable = false;

                            document.getElementById('checkout-place-order-button').disabled = true;
                            document.querySelectorAll("form button.btn")[0].disabled = true;
                        }
                    })

                    .catch(function (error) {
                        document.getElementById('checkout-place-order-button').disabled = true;
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