<?php $supplierAddress = $supplier->addresses()->get()->first();?>

<div class="profile-left-block">

    <div class="content">
        <div class="supplier-container">
            <a class="supplier-logo-container">

                <div class="profile-logo-block">
                    @if ($logo = $supplierAddress->logo_url)

                        <img src="{{ $logo }}" />
                    @else
                        <img src="{{ bagisto_asset('themes/b2b/assets/images/default-logo.svg') }}" />
                    @endif
                </div>
            </a>

            <div class="supplier-name" style="margin-bottom: 5px;">
                <span class="supplier-title" style="color:black; font-size: 24px;">{{ $supplierAddress->company_name }}</span>
            </div>

            <div style="margin-bottom: 5px;">
                <span class="supplier-header-txt">{{ $supplierAddress->company_tag_line }}</span>
            </div>

            <div class="supplier-header-txt">
                @if ($supplierAddress->country)
                    <a target="_blank" href="https://www.google.com/maps/place/{{ $supplierAddress->city . ', '. $supplierAddress->state . ', ' . core()->country_name($supplierAddress->country) }}" class="shop-address" style="color: #5f5f5f;">{{ $supplierAddress->city . ', '. $supplierAddress->state . ' (' . core()->country_name($supplierAddress->country) . ')' }}</a>
                @endif

            </div>

            <div class="supplier-info-row" style="margin-top: 5px;">

                <a href="#contact" @click="showModal('contactForm')">
                    {{ __('b2b_marketplace::app.shop.supplier.profile.contact-supplier') }}
                </a>
            </div>

            <div class="supplier-rating" style="padding: 5px 0;">

                <span class="product-reviews">
                    <div class="rating-summary">
                        <div class="rating-result">
                            <span style="width:94%">

                                <div class="row">

                                    <?php $reviewRepository = app('Webkul\B2BMarketplace\Repositories\ReviewRepository') ?>

                                    <?php $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository') ?>

                                    <div class="review-info">
                                        <span class="number">
                                            {{ $reviewRepository->getAverageRating($supplier) }}
                                        </span>

                                        <span class="stars">
                                            @for ($i = 1; $i <= $reviewRepository->getAverageRating($supplier); $i++)

                                                <span class="icon star-icon"></span>

                                            @endfor
                                        </span>

                                        <div class="total-reviews">
                                            <a href="{{ route('b2b_marketplace.reviews.index', $supplier->url) }}">
                                                {{
                                                    __('b2b_marketplace::app.shop.supplier.profile.total-rating', [
                                                            'total_rating' => $reviewRepository->getTotalRating($supplier),
                                                            'total_reviews' => $reviewRepository->getTotalReviews($supplier),
                                                        ])
                                                }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </span>
                        </div>
                    </div>
                </span>

            </div>

            @if($supplier->is_verified)
                <div style="padding-top: 6px;">
                    <span class="supplier-verified">
                        <i class="icon verification-icon active" style="position: absolute;
                        margin-top: -5px;"></i>
                        <span style="margin: 1px 33px 31px; color: green;">
                            {{ __('b2b_marketplace::app.shop.supplier.profile.verified')}}
                        </span>
                    </span>
                </div>
            @endif
        </div>

        <div class="supplier-container-right" style="float: right;">

            <div class="supplier-msg-container">

                @if (auth()->guard('customer')->check())
                    <a id="shopMessage"class="btn btn-lg btn-primary" @click="showModal('shopMessage')"
                        style="float: right;">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier')}}
                    </a>
                @else
                    <a href="{{route('customer.session.index')}}" class="btn btn-lg btn-primary" style="float: right; margin-bottom:5px;">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier')}}
                    </a>
                @endif
            </div>

            @php
                $supplierFlags = app('Webkul\B2BMarketplace\Repositories\SupplierFlagReasonRepository')->findWhere(['status'=>1]);
            @endphp

            <div class="supplier-info">

                <div class="report-flag">
                    @if (core()->getConfigData('b2b_marketplace.settings.supplier_flag.status') )
                        <a href="#" @click="showModal('reportForm')">
                            <i class="material-icons"></i>
                            {{ core()->getConfigData('marketplace.settings.supplier_flag.text') ?: 'Report Supplier' }}

                        </a>
                    @endif

                    <modal id="reportForm" :is-open="modalIds.reportForm">
                        <h3 slot="header">
                            {{ __('b2b_marketplace::app.shop.flag.title') }}
                        </h3>

                        <div slot="body">
                            <flag-form></flag-form>
                        </div>
                    </modal>

                </div>

                <div class="supplier-header-txt" style="">
                    {{ __('b2b_marketplace::app.shop.supplier.profile.supplier-info')}}
                </div>

                <div style="display: block;">
                    <div class="social-links">
                        @if ($supplierAddress->facebook)
                        <a href="https://www.facebook.com/{{$supplierAddress->facebook}}" target="_blank">
                            <i class="icon social-icon mp-facebook-icon"></i>
                        </a>
                        @endif

                        @if ($supplierAddress->twitter)
                            <a href="https://www.twitter.com/{{$supplierAddress->twitter}}" target="_blank">
                                <i class="icon social-icon mp-twitter-icon"></i>
                            </a>
                        @endif

                        @if ($supplierAddress->instagram)
                            <a href="https://www.instagram.com/{{$supplierAddress->instagram}}" target="_blank">
                                <i class="icon social-icon mp-instagram-icon"></i>
                            </a>
                        @endif

                        @if ($supplierAddress->pinterest)
                            <a href="https://www.pinterest.com/{{$supplierAddress->pinterest}}" target="_blank">
                                <i class="icon social-icon mp-pinterest-icon"></i>
                            </a>
                        @endif

                        @if ($supplierAddress->skype)
                            <a href="https://www.skype.com/{{$supplierAddress->skype}}" target="_blank">
                                <i class="icon social-icon mp-skype-icon"></i>
                            </a>
                        @endif

                        @if ($supplierAddress->linked_in)
                            <a href="https://www.linkedin.com/{{$supplierAddress->linked_in}}" target="_blank">
                                <i class="icon social-icon mp-linked-in-icon"></i>
                            </a>
                        @endif

                        @if ($supplierAddress->youtube)
                            <a href="https://www.youtube.com/{{$supplierAddress->youtube}}" target="_blank">
                                <i class="icon social-icon mp-youtube-icon"></i>
                            </a>
                        @endif
                    </div>

                    <div class="supplier-info-container">
                        <a class="supplier-minilogo">
                            <img class="icon b2bcustomer-icon active" style="margin: -1px -1px;" >
                        </a>

                        <div class="wk-supplier-info-title-container" style="display: inline-block;
                        padding-left: 10px;">
                            <div class="wk-supplier-info-row" style="width: 100%;
                            display: inline-block;">{{$supplier->first_name . ' ' . $supplier->last_name }}</div>
                            <div class="wk-supplier-collection-header-txt">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.email')}}
                                {{$supplier->email}}</div>
                        </div>

                        <div class="supplier-member-since">
                            <div class="supplier-info-row">
                                {{ __('b2b_marketplace::app.shop.supplier.profile.member-since')}}
                            </div>

                            <div style="color: #5f5f5f; line-height: initial;">
                                {{core()->formatDate($supplier->created_at, 'Y')}}
                            </div>

                            {{-- <div class="supplier-info-row">

                                <a href="#" @click="showModal('contactForm')">
                                    {{ __('b2b_marketplace::app.shop.supplier.profile.contact-supplier') }}
                                </a>
                            </div> --}}

                            <div class="supplier-info-row">
                                <span>Response Time:</span>
                            <span>{{ $supplierAddress->response_time }} Hour</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<modal id="shopMessage" :is-open="modalIds.shopMessage">

    <h3 slot='header'>{{$supplier->company_name}}</h3>
    <div slot="body">

        @if($supplier->is_verified)
            <div style="display:grid;">
                <span class="verified-supplier" style="color: green;">
                    {{ __('b2b_marketplace::app.shop.supplier.profile.verified')}}
                </span>

                <span class="icon verification-icon active"></span>
            </div>
        @else
        @endif

        <?php
            if (auth()->guard('customer')->check()){
                $customer = auth()->guard('customer')->user()->id;
            } else {
                $customer = null;
            }
        ?>

        <message-form></message-form>

    </div>
</modal>

<modal id="contactForm" :is-open="modalIds.contactForm">
    <h3 slot="header">{{ __('b2b_marketplace::app.shop.supplier.profile.contact-supplier') }}</h3>

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
                <input type="email" v-validate="'required'" v-model="data.email" class="control" id="email" name="email" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.flag.email') }}&quot;" value="{{ old('email') }}"/>

                <span class="control-error" v-if="errors.has('report-form.email')">@{{ errors.first('report-form.email') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('report-form.selected_reason') ? 'has-error' : '']">
                <label for="selected_reason" class="label-style">{{ __('b2b_marketplace::app.shop.flag.reason') }}</label>

                <select name="selected_reason" id="selected_reason" v-model="data.selected_reason" class="control" v-validate="'required'">
                    @foreach ($supplierFlags as $flag)
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
                    <button type="button" class="btn btn-lg btn-primary" @click="reportSupplier('report-form')" :disabled="disable_button">
                        {{ __('b2b_marketplace::app.shop.flag.submit') }}
                    </button>
                @endauth

                @guest('customer')
                    @if (core()->getConfigData('b2b_marketplace.settings.supplier_flag.guest_can'))
                        <button type="button" class="btn btn-lg btn-primary" @click="reportSupplier('report-form')" :disabled="disable_button">
                            {{ __('b2b_marketplace::app.shop.flag.submit') }}
                        </button>
                    @else
                        <a href="{{route('customer.session.index')}}" class="btn btn-lg btn-primary"> {{ __('b2b_marketplace::app.shop.flag.login') }}</a>
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
                        <button class="btn btn-lg btn-primary" type="submit" :disabled="disable_button">
                            {{ __('b2b_marketplace::app.shop.supplier.profile.send')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </script>

    <script type="text/x-template" id="contact-form-template">
        <form action="" method="POST" data-vv-scope="contact-form" @submit.prevent="contactsupplier('contact-form')">

            @csrf

            <div class="form-container">

                <div class="control-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
                    <label for="name" class="required">{{ __('b2b_marketplace::app.shop.supplier.profile.name') }}</label>
                    <input type="text" v-model="contact.name" class="control" name="name" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.name') }}&quot;">
                    <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('b2b_marketplace::app.shop.supplier.profile.email') }}</label>
                    <input type="text" v-model="contact.email" class="control" name="email" v-validate="'required|email'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.email') }}&quot;">
                    <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
                    <label for="subject" class="required">{{ __('b2b_marketplace::app.shop.supplier.profile.subject') }}</label>
                    <input type="text" v-model="contact.subject" class="control" name="subject" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.subject') }}&quot;">
                    <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
                    <label for="query" class="required">{{ __('b2b_marketplace::app.shop.supplier.profile.query') }}</label>
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

            created () {

                @auth('customer')
                    @if(auth('customer')->user())
                        this.contact.email = "{{ auth('customer')->user()->email }}";
                        this.contact.name = "{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}";
                    @endif
                @endauth

            },

            methods: {
                contactsupplier (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post("{{ route('b2b_marketplace.supplier.contact', $supplier->url) }}", this.contact)
                                .then (function(response) {
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

                                    this_this.handleErrorResponse(error.response, 'contact-form')
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

        Vue.component('message-form', {

            template: '#message-form-template',
            data: () => ({
                data: {
                    message: '',
                    customer_id: '',
                    supplier_id: '{{$supplier->id}}',
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

        Vue.component('flag-form', {
            template: '#flag-form-template',
            data: () => ({
                data: {
                    selected_reason: '',
                    supplier_id: "{{$supplier->id}}",
                    reason: ''
                },
                disable_button: false
            }),

            methods: {

                reportSupplier (formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post ("{{ route('b2b_marketplace.flag.supplier.store') }}", this.data)
                            .then (response => {

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