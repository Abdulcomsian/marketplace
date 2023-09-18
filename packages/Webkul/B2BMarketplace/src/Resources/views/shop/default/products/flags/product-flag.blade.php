@push('css')
    <link rel="stylesheet" href="{{ asset('themes/velocity/assets/css/bootstrap.min.css') }}">
    <style>
        .row {
            justify-content: center;
        }

        body {
            line-height: 1.0;
        }

        .btn:hover {
            background: #000;
            color: #fff;
        }
    </style>
    <style>
        .report-section {
            font-size: 14px;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .report-section .material-icons {
            font-size: 16px;
            position: absolute;
            left: 0;
            top: 2px
        }

        .report-section .supplier-logo {
            text-align: center
        }

        .report-section .supplier-logo img {
            display: inline-block;
            width: 130px;
            padding: 5px;
            -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, .15);
            box-shadow: 0 2px 5px rgba(0, 0, 0, .15);
            position: relative;
            font-size: 0;
            overflow: hidden;
            vertical-align: middle
        }

        .report-section .supplier-flag-info {
            padding-left: 22px;
        }

        .report-section .supplier-flag-info .supplier-store-name a {
            color: #363636;
            font-size: 25px
        }

        .report-section .supplier-flag-info .supplier-location a {
            display: inline-block;
            position: relative;
            font-weight: 600;
            color: #555;
            margin: 2px 0;
            text-transform: capitalize
        }

        .report-section .supplier-flag-info .contact-supplier {
            padding: 2px 0
        }

        .report-section .supplier-flag-info .contact-supplier a {
            display: inline-block;
            background: #28557b;
            color: #fff;
            padding: 3px 15px;
            border-radius: 2px;
            font-weight: 600;
            text-align: center
        }

        .report-section .supplier-flag-info .report-flag {
            display: inline-block;
            position: relative
        }

        .report-section .modal-container {
            z-index: 1000
        }

        .report-supplier-products .supplier-product-col-2 {
            padding-left: 0;
            padding-right: 0
        }

        .report-supplier-products .supplier-product-col-2 .supplier-flag-product {
            -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, .16), 0 3px 6px rgba(0, 0, 0, .23);
            box-shadow: 0 3px 6px rgba(0, 0, 0, .16), 0 3px 6px rgba(0, 0, 0, .23);
            width: 84px;
            height: 84px;
            margin: 5px;
            text-align: center;
            border: 1px solid #ccc
        }

        .report-supplier-products .supplier-product-col-2 .supplier-flag-product img {
            width: 100%;
        }

        .report-supplier-products .supplier-product-col-2 .supplier-flag-product a span {
            font-size: inherit;
            display: inline-block;
            position: relative;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%)
        }
    </style>
@endpush
@php
    $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository');
    if ($product->product->id) {
        $supplier = $productRepository->getSupplierByProductId($product->product->id);
        if ($supplier) {
            $productFlags = app('Webkul\B2BMarketplace\Repositories\ProductFlagReasonRepository')->findWhere(['status' => 1]);
            if ($supplier) {
                $supplierProducts = $productRepository->findAllBySupplier($supplier);
            }
            $supplierProduct = $productRepository->getMarketplaceProductByProduct($product->product_id, $supplier->id);
        }
    }
    
@endphp

@if (isset($supplierProduct) && $supplierProduct->is_approved && $supplierProduct->is_owner)

    <section class="mt-5 mb-5 report-section">
        <div class="row">
            @if ($supplier->company_name)
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="supplier-logo">
                                <a href="{{ route('b2b_marketplace.supplier.show', $supplier->addresses->url) }}">
                                    @if ($supplier->addresses->logo_url)
                                        <img src="{{ asset($supplier->addresses->logo_url) }}" alt=""
                                            style="max-width: 70px;
                                        height: 68px;">
                                    @else
                                        <img src="{{ bagisto_asset('themes/b2b/assets/images/default-logo.svg') }}"
                                            style="max-width: 70px;
                                        height: 68px;" />
                                    @endif
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                            <div class="supplier-flag-info">
                                <div class="supplier-store-name">
                                    <a
                                        href="{{ route('b2b_marketplace.supplier.show', $supplier->addresses->url) }}">{{ $supplier->company_name }}</a>
                                </div>

                                @if ($supplier->addresses->state != null)
                                    <div class="supplier-location">
                                        <a target="_blank"
                                            href="https://www.google.com/maps/place/{{ $supplier->addresses->city . ', ' . $supplier->addresses->state . ', ' . core()->country_name($supplier->addresses->country) }}"
                                            class="shop-address">
                                            <i class="material-icons"></i>
                                            {{ $supplier->addresses->city . ', ' . $supplier->addresses->state . ' (' . core()->country_name($supplier->addresses->country) . ')' }}
                                        </a>
                                    </div>
                                @endif

                                <div class="contact-supplier">
                                    <a href="#"
                                        @click="showModal('contactForm')">{{ __('b2b_marketplace::app.shop.supplier.profile.contact-supplier') }}
                                    </a>
                                </div>

                                @if (core()->getConfigData('b2b_marketplace.settings.product_flag.status'))
                                    <div class="report-flag">
                                        <a href="javascript:void(0);" @click="showModal('reportFlag')">
                                            <i class="material-icons"></i>
                                            {{ core()->getConfigData('b2b_marketplace.settings.product_flag.text') }}
                                        </a>
                                        <modal id="reportFlag" :is-open="modalIds.reportFlag">
                                            <h3 slot="header">
                                                {{ __('b2b_marketplace::app.shop.flag.title') }}
                                            </h3>

                                            <div slot="body">
                                                <product-flag-form></product-flag-form>
                                            </div>
                                        </modal>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-6 col-md-6 col-sm-12 col-12 report-supplier-products">
                <div class="row">
                    @foreach ($supplierProducts->take(5) as $supplierProduct)
                        @if ($supplierProduct->product->url_key)
                            <div class="supplier-product-col-2">
                                <div class="supplier-flag-product">
                                    <a title="{{ $supplierProduct->product->name }}"
                                        href="{{ route('shop.productOrCategory.index', $supplierProduct->product->url_key) }}">
                                        <img class="img-fluid"
                                            src="{{ productimage()->getProductBaseImage($supplierProduct->product)['small_image_url'] }}"
                                            alt="">
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div class="supplier-product-col-2">
                        <div class="supplier-flag-product">
                            <a href="{{ route('b2b_marketplace.products.index', $supplier->url) }}">
                                <span> View all {{ $supplierProducts->count() }} products </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <modal id="contactForm" :is-open="modalIds.contactForm">
        <h3 style="margin-left: 80px;" slot="header">
            {{ __('b2b_marketplace::app.shop.supplier.profile.contact-supplier') }}</h3>

        <i class="icon remove-icon "></i>

        <div slot="body">
            <contact-supplier-form></contact-supplier-form>
        </div>
    </modal>

    @push('scripts')
        <script type="text/x-template" id="flag-form-template">
            <form method="POST" action="" data-vv-scope="report-form" enctype="multipart/form-data">
                @csrf()

                <div class="control-group" :class="[errors.has('report-form.name') ? 'has-error' : '']">
                    <label for="name" class="required label-style">{{ __('b2b_marketplace::app.shop.flag.name') }}</label>
                    <input v-validate="'required'" v-model="data.name" type="text" class="control" id="name" name="name" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.name') }}&quot;" value="{{ old('name') }}"/>
                    <span class="control-error" v-if="errors.has('report-form.name')">@{{ errors.first('report-form.name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('report-form.email') ? 'has-error' : '']">
                    <label for="type" class="required label-style">{{ __('b2b_marketplace::app.shop.flag.email') }}</label>
                <input type="email" v-validate="'required'" v-model="data.email" class="control" id="email" name="email" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.email') }}&quot;" value="{{ old('email') }}" />
                    <span class="control-error" v-if="errors.has('report-form.email')">@{{ errors.first('report-form.email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('report-form.selected_reason') ? 'has-error' : '']">
                    <label for="selected_reason" class="label-style">{{ __('b2b_marketplace::app.shop.flag.reason') }}</label>

                    <select name="selected_reason" id="selected_reason" v-model="data.selected_reason" class="control" v-validate="'required'">
                        @foreach ($productFlags as $flag)
                            <option value="{{$flag->reason}}">{{$flag->reason}}</option>
                        @endforeach
                        <option value="other">Other</option>
                    </select>

                    <span class="control-error" v-if="errors.has('report-form.selected_reason')">@{{ errors.first('report-form.selected_reason') }}</span>

                    @if (core()->getConfigData('b2b_marketplace.settings.product_flag.other_reason'))
                        <div class="control-group" :class="[errors.has('report-form.reason') ? 'has-error' : '']">
                        <textarea class="control mt-3" v-validate="'required'" v-model="data.reason" id="other-reason" v-if="data.selected_reason == 'other'" placeholder="{{core()->getConfigData('b2b_marketplace.settings.product_flag.other_placeholder')}}" name="reason" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.reason') }}&quot;">
                        </textarea>
                        <span class="control-error" v-if="errors.has('report-form.reason')">@{{ errors.first('report-form.reason') }}</span>
                    @endif
                </div>

                <div class="mt-5">
                    @auth('customer')
                        <button type="button" class="btn btn-lg btn-primary" @click="reportProduct('report-form')">
                            {{ __('b2b_marketplace::app.shop.flag.submit') }}
                        </button>
                    @endauth

                    @guest('customer')
                        @if (core()->getConfigData('b2b_marketplace.settings.product_flag.guest_can'))
                            <button type="button" class="btn btn-lg btn-primary" @click="reportProduct('report-form')">
                                {{ __('b2b_marketplace::app.shop.flag.submit') }}
                            </button>
                        @else
                            <a href="{{route('customer.session.index')}}" class="btn btn-lg btn-primary"> {{ __('b2b_marketplace::app.shop.flag.login') }}</a>
                        @endif
                    @endguest
                </div>

            </form>
        </script>

        <script type="text/x-template" id="contact-form-template">

            <form action="" class="account-table-content" method="POST" data-vv-scope="contact-form" @submit.prevent="contactSupplier('contact-form')">

                @csrf

                <div class="form-container">

                    <div class="control-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
                        <label for="name" class="required mandatory">{{ __('b2b_marketplace::app.shop.supplier.profile.name') }}</label>
                        <input type="text" v-model="contact.name" class="control" name="name" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.name') }}&quot;">
                        <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
                        <label for="email" class="required mandatory">{{ __('b2b_marketplace::app.shop.supplier.profile.email') }}</label>
                        <input type="text" v-model="contact.email" class="control" name="email" v-validate="'required|email'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.email') }}&quot;">
                        <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
                        <label for="subject" class="required mandatory">{{ __('b2b_marketplace::app.shop.supplier.profile.subject') }}</label>
                        <input type="text" v-model="contact.subject" class="control" name="subject" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.subject') }}&quot;">
                        <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
                        <label for="query" class="required mandatory">{{ __('b2b_marketplace::app.shop.supplier.profile.query') }}</label>
                        <textarea class="control" v-model="contact.query" name="query" v-validate="'required'"  data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.query') }}&quot;">
                        </textarea>
                        <span class="control-error" v-if="errors.has('contact-form.query')">@{{ errors.first('contact-form.query') }}</span>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary" :disabled="disable_button">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.submit') }}
                    </button>
                </div>
            </form>

        </script>

        <script>
            Vue.component('contact-supplier-form', {

                data: () => ({
                    contact: {
                        'name': '',
                        'email': '',
                        'subject': '',
                        'query': ''
                    },

                    disable_button: false,
                }),

                template: '#contact-form-template',

                created() {

                    @auth('customer')
                        @if (auth('customer')->user())
                            this.contact.email = "{{ auth('customer')->user()->email }}";
                            this.contact.name =
                                "{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}";
                        @endif
                    @endauth

                },

                methods: {
                    contactSupplier(formScope) {
                        var this_this = this;

                        this_this.disable_button = true;

                        this.$validator.validateAll(formScope).then((result) => {
                            if (result) {

                                this.$http.post(
                                        "{{ route('b2b_marketplace.supplier.contact', $supplier->url) }}", this
                                        .contact)
                                    .then(function(response) {
                                        this_this.disable_button = false;

                                        this_this.$parent.closeModal();

                                        window.flashMessages = [{
                                            'type': 'alert-success',
                                            'message': response.data.message
                                        }];

                                        this_this.$root.addFlashMessages();
                                    })

                                    .catch(function(error) {
                                        this_this.disable_button = false;

                                        this_this.handleErrorResponse(error.response, 'contact-form')
                                    })
                            } else {
                                this_this.disable_button = false;
                            }
                        });
                    },

                    handleErrorResponse(response, scope) {

                        if (response.status == 422) {
                            serverErrors = response.data.errors;
                            this.$root.addServerErrors(scope)
                        }
                    }
                }
            });

            Vue.component('product-flag-form', {
                template: '#flag-form-template',
                data: () => ({
                    data: {
                        selected_reason: '',
                        product_id: "{{ $product->product->id }}",
                        supplier_id: "{{ $supplier->id }}",
                        reason: '',
                    }
                }),

                methods: {

                    reportProduct(formScope) {
                        var this_this = this;

                        this_this.disable_button = true;

                        this.$validator.validateAll(formScope).then((result) => {
                            if (result) {

                                this.$http.post("{{ route('b2b_marketplace.flag.product.store') }}", this.data)
                                    .then(response => {

                                        this_this.$parent.closeModal();

                                        if (response.data.success) {

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

                                    .catch(function(error) {
                                        this_this.disable_button = false;

                                        this_this.handleErrorResponse(error.response, 'message-form')
                                    })
                            } else {
                                this_this.disable_button = false;
                            }
                        });
                    },

                    handleErrorResponse(response, scope) {
                        if (response.status == 422) {
                            serverErrors = response.data.errors;
                            this.$root.addServerErrors(scope)
                        }
                    }
                }

            });
        </script>
    @endpush
@endif
