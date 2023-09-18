@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ trim($product->meta_title) != '' ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description"
        content="{{ trim($product->meta_description) != '' ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}" />
    <meta name="keywords" content="{{ $product->meta_keywords }}" />
@stop

@push('css')
    <style>
        .availability button.active {
            background: #4d7ea8 !important;
            border: none;
            color: #fff;
            font-weight: 600;
            cursor: default;
            padding: 2px 11px;
        }
    </style>
@endpush

@section('content-wrapper')

    <?php
    $baseProduct = $product->parent_id ? $product->parent : $product;
    $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository');
    ?>

    {!! view_render_event('bagisto.shop.suppliers.products.offers.before', ['product' => $product]) !!}

    <div class="product-offer-container">

        <div class="product">
            <div class="product-information">

                <?php $productBaseImage = productimage()->getProductBaseImage($baseProduct); ?>

                <div class="product-logo-block">
                    <a href="{{ route('shop.productOrCategory.index', $baseProduct->url_key) }}"
                        title="{{ $baseProduct->name }}">
                        <img src="{{ $productBaseImage['medium_image_url'] }}" />
                    </a>
                </div>

                <div class="product-information-block">
                    <a href="{{ route('shop.productOrCategory.index', $baseProduct->url_key) }}" class="product-title">
                        {{ $baseProduct->name }}
                    </a>

                    <div class="price">
                        @include ('shop::products.price', ['product' => $product])
                    </div>

                    @include ('shop::products.view.stock', ['product' => $product])

                    <?php $attributes = []; ?>

                    @if ($baseProduct->type == 'configurable')

                        <div class="options">
                            <?php $options = []; ?>

                            @foreach ($baseProduct->super_attributes as $attribute)
                                @foreach ($attribute->options as $option)
                                    @if ($product->{$attribute->code} == $option->id)
                                        <?php $attributes[$attribute->id] = $option->id; ?>

                                        <?php array_push($options, $attribute->name . ' : ' . $option->label); ?>
                                    @endif
                                @endforeach
                            @endforeach

                            {{ implode(', ', $options) }}

                        </div>

                    @endif

                </div>
            </div>

            <div class="review-information">

                @include ('shop::products.review', ['product' => $baseProduct])

            </div>
        </div>

        <div class="seller-product-list padding-15">
            <h2 class="heading">{{ __('b2b_marketplace::app.shop.products.more-suppliers') }}</h2>

            <div class="content">

                @foreach ($productRepository->getSupplierProducts($product) as $supplierProduct)
                    <form action="{{ route('cart.add', $baseProduct->id) }}" method="POST">

                        @csrf()
                        <input type="hidden" name="product_id" value="{{ $supplierProduct->id }}">
                        <input type="hidden" name="supplier_info[product_id]" value="{{ $supplierProduct->id }}">
                        <input type="hidden" name="supplier_info[supplier_id]"
                            value="{{ $supplierProduct->supplier->id }}">
                        <input type="hidden" name="supplier_info[is_owner]" value="0">

                        @if ($baseProduct->type == 'configurable')
                            <input type="hidden" name="selected_configurable_option" value="{{ $product->id }}">

                            @foreach ($attributes as $attributeId => $optionId)
                                <input type="hidden" name="super_attribute[{{ $attributeId }}]"
                                    value="{{ $optionId }}" />
                            @endforeach
                        @endif

                        <div class="seller-product-item">

                            <div class="product-info-top" style="position: relative;">

                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="profile-logo-block">
                                                    @if ($logo = $supplierProduct->supplier->addresses->logo_url)
                                                        <img src="{{ $logo }}" />
                                                    @else
                                                        <img
                                                            src="{{ bagisto_asset('themes/b2b/assets/images/default-logo.svg') }}" />
                                                    @endif
                                                </div>

                                                <div class="profile-information-block">
                                                    <a href="{{ route('b2b_marketplace.supplier.show', $supplierProduct->supplier->url) }}"
                                                        class="shop-title">
                                                        {{ $supplierProduct->supplier->company_name }}
                                                    </a>

                                                    <div class="review-information">

                                                        <?php $reviewRepository = app('Webkul\B2BMarketplace\Repositories\ReviewRepository'); ?>

                                                        <span class="stars">

                                                            <star-ratings
                                                                ratings="{{ ceil($reviewRepository->getAverageRating($supplierProduct->supplier)) }}"
                                                                push-class="mr5"></star-ratings>

                                                            {{ __('b2b_marketplace::app.shop.products.supplier-total-rating', [
                                                                'avg_rating' => $reviewRepository->getAverageRating($supplierProduct->supplier),
                                                                'total_rating' => $reviewRepository->getTotalRating($supplierProduct->supplier),
                                                            ]) }}
                                                        </span>

                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @if ($supplierProduct->condition == 'new')
                                                    {{ __('b2b_marketplace::app.shop.products.new') }}
                                                @else
                                                    {{ __('b2b_marketplace::app.shop.products.used') }}
                                                @endif
                                            </td>

                                            <td>
                                                <div class="product-prices">
                                                    @if ($supplierProduct->is_owner && $product->getTypeInstance()->haveSpecialPrice($supplierProduct))
                                                        <div class="sticker sale">
                                                            {{ __('shop::app.products.sale') }}
                                                        </div>

                                                        <span
                                                            class="regular-price">{{ core()->currency($supplierProduct->price) }}</span>

                                                        <span
                                                            class="special-price">{{ core()->currency($product->getTypeInstance()->getSpecialPrice($supplierProduct)) }}</span>
                                                    @else
                                                        <span>{{ core()->currency($supplierProduct->price) }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td style="position: absolute;width: 380px;margin-top: 20px;">
                                                <div class="control-group" style="margin-bottom: 10px;">
                                                    <input type="text" name="quantity" value="1" class="control">
                                                </div>

                                                <div class="btn-container">
                                                    @if ($supplierProduct->haveSufficientQuantity(1))
                                                        <button type="submit" class="theme-btn"
                                                            style="margin: 0px 10px 10px 10px;">
                                                            {{ __('b2b_marketplace::app.shop.products.add-to-cart') }}
                                                        </button>
                                                    @else
                                                        <div class="stock-status">
                                                            {{ __('b2b_marketplace::app.shop.products.out-of-stock') }}
                                                        </div>
                                                    @endif

                                                    @if (auth()->guard('customer')->check())
                                                        <a type="button" id="shopMessage"class="theme-btn"
                                                            @click="showModal('shopMessage')">
                                                            {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
                                                        </a>
                                                    @else
                                                        <a type="button" href="{{ route('customer.session.index') }}"
                                                            class="theme-btn" style="float: right;">
                                                            {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>


                            <div class="product-info-bottom">
                                <?php $baseSupplierProduct = $supplierProduct->parent_id ? $supplierProduct->parent : $supplierProduct; ?>
                                <div class="product">
                                    <div class="product-information">

                                        <?php $productImages = productimage()->getGalleryImages($baseSupplierProduct); ?>

                                        <div class="row col-12">
                                            <ul class="thumb-list col-12 row ltr" type="none">
                                                @if (sizeof($productImages) > 4)
                                                    <li class="arrow left" @click="scroll('prev')">
                                                        <i class="rango-arrow-left fs24"></i>
                                                    </li>
                                                @endif
                                                <carousel-component slides-per-page="4" pagination-enabled="hide"
                                                    navigation-enabled="hide" add-class="product-gallary"
                                                    :slides-count="{{ sizeof($productImages) }}">
                                                    @foreach ($productImages as $index => $thumb)
                                                        <slide :slot="`slide-0`">
                                                            <img style="padding: 0px 67px 0px 10px;"
                                                                src="{{ $thumb['small_image_url'] }}" />
                                                        </slide>
                                                    @endforeach
                                                </carousel-component>

                                                @if (sizeof($productImages) > 4)
                                                    <li class="arrow right" @click="scroll('next')">
                                                        <i class="rango-arrow-right fs24"></i>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="product-info-bottom">
                                <?php $baseSupplierProduct = $supplierProduct->parent_id ? $supplierProduct->parent : $supplierProduct; ?>
                                <div class="product">
                                    <div class="product-information">

                                        @php
                                            $productVideos = $baseSupplierProduct->assignVideos;
                                            
                                            foreach ($productVideos as $key => $video) {
                                                $videoData[$key]['type'] = $video->type;
                                                $videoData[$key]['large_image_url'] = $videoData[$key]['small_image_url'] = $videoData[$key]['medium_image_url'] = $videoData[$key]['original_image_url'] = $video->path;
                                            }
                                        @endphp

                                        <div class="row col-12">
                                            <product-video></product-video>
                                        </div>

                                        <div class="product-information-block">

                                            {{ $baseSupplierProduct->description }}

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <modal id="shopMessage" :is-open="modalIds.shopMessage">

        <h3 slot='header'>{{ $supplierProduct->supplier->company_name }}</h3>

        <div slot="body">
            @if ($supplierProduct->supplier->is_verified)
                <div style="display:grid;">
                    <span class="verified-supplier" style="color: green;">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.verified') }}
                    </span>

                    <span class="icon verification-icon active"></span>
                </div>
            @endif

            <message-form></message-form>
        </div>
    </modal>


    {!! view_render_event('bagisto.shop.supplier.products.offers.after', ['product' => $product]) !!}

@endsection


<script type="text/x-template" id="product-video-template">
    <ul class="thumb-list col-12 row ltr" type="none">
        {{-- @if (sizeof($videoData) > 4) --}}
        @isset($videoData)
            <li class="arrow left" @click="scroll('prev')">
                <i class="rango-arrow-left fs24"></i>
            </li>
        @endisset
        {{-- @endif --}}



        <carousel-component
            slides-per-page="4"
            :id="galleryCarouselId"
            pagination-enabled="hide"
            navigation-enabled="hide"
            add-class="product-gallary"
            :slides-count="@isset($videoData){{ sizeof($videoData) }} @endisset">
            @isset($videoData)
            @foreach ($videoData as $index => $thumb)
                <slide :slot="`slide-0`">
                    <video v-if="{{ $thumb['type'] == 'video'}}" width='200' height='112'
                    style = "border: 1px solid #c4c;" controls>
                        <source src="{{bagisto_asset('themes/b2b/assets/storage/' . $thumb['small_image_url'])}}" type="video/mp4">
                        {{ __('admin::app.catalog.products.not-support-video') }}
                    </video>
                </slide>
            @endforeach
            @endisset
        </carousel-component>


        {{-- @if (sizeof($videoData) > 4) --}}
        @isset($videoData)
            <li class="arrow right" @click="scroll('next')">
                <i class="rango-arrow-right fs24"></i>
            </li>
         @endisset
         {{-- @endif --}}

    </ul>
</script>
@php
    if (!isset($videoData)) {
        $videoData = null;
    }
@endphp

@push('scripts')
    <script type="text/javascript">
        (() => {
            var galleryImages = @json($productImages);
            var galleryVideo = @json($videoData);

            Vue.component('product-gallery', {
                template: '#product-gallery-template',
                data: function() {
                    return {
                        images: galleryVideo,
                        thumbs: [],
                        galleryCarouselId: 'product-gallery-carousel',
                        currentLargeImageUrl: '',
                        currentOriginalImageUrl: '',
                        counter: {
                            up: 0,
                            down: 0,
                        }
                    }
                },

                watch: {
                    'images': function(newVal, oldVal) {
                        this.changeImage({
                            largeImageUrl: this.images[0]['large_image_url'],
                            originalImageUrl: this.images[0]['original_image_url'],
                        })

                        this.prepareThumbs()
                    }
                },

                created: function() {
                    this.changeImage({
                        largeImageUrl: this.images[0]['large_image_url'],
                        originalImageUrl: this.images[0]['original_image_url'],
                    })

                    this.prepareThumbs()
                },

                methods: {
                    prepareThumbs: function() {
                        this.thumbs = [];

                        this.images.forEach(image => {
                            this.thumbs.push(image);
                        });
                    },

                    changeImage: function({
                        largeImageUrl,
                        originalImageUrl
                    }) {
                        this.currentLargeImageUrl = largeImageUrl;

                        this.currentOriginalImageUrl = originalImageUrl;

                        this.$root.$emit('changeMagnifiedImage', {
                            smallImageUrl: this.currentOriginalImageUrl
                        });

                        let productImage = $('.vc-small-product-image');
                        if (productImage && productImage[0]) {
                            productImage = productImage[0];

                            productImage.src = this.currentOriginalImageUrl;
                        }
                    },

                    scroll: function(navigateTo) {
                        let navigation = $(
                            `#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`
                        );

                        if (navigation && (navigation = navigation[0])) {
                            navigation.click();
                        }
                    },
                }
            });

            Vue.component('product-video', {
                template: '#product-video-template',
                data: function() {
                    return {
                        images: galleryImages,
                        thumbs: [],
                        galleryCarouselId: 'product-gallery-carousel',
                        currentLargeImageUrl: '',
                        currentOriginalImageUrl: '',
                        counter: {
                            up: 0,
                            down: 0,
                        }
                    }
                },

                watch: {
                    'images': function(newVal, oldVal) {
                        this.changeImage({
                            largeImageUrl: this.images[0]['large_image_url'],
                            originalImageUrl: this.images[0]['original_image_url'],
                        })

                        this.prepareThumbs()
                    }
                },

                created: function() {
                    this.changeImage({
                        largeImageUrl: this.images[0]['large_image_url'],
                        originalImageUrl: this.images[0]['original_image_url'],
                    })

                    this.prepareThumbs()
                },

                methods: {
                    prepareThumbs: function() {
                        this.thumbs = [];

                        this.images.forEach(image => {
                            this.thumbs.push(image);
                        });
                    },

                    changeImage: function({
                        largeImageUrl,
                        originalImageUrl
                    }) {
                        this.currentLargeImageUrl = largeImageUrl;

                        this.currentOriginalImageUrl = originalImageUrl;

                        this.$root.$emit('changeMagnifiedImage', {
                            smallImageUrl: this.currentOriginalImageUrl
                        });

                        let productImage = $('.vc-small-product-image');
                        if (productImage && productImage[0]) {
                            productImage = productImage[0];

                            productImage.src = this.currentOriginalImageUrl;
                        }
                    },

                    scroll: function(navigateTo) {
                        let navigation = $(
                            `#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`
                        );

                        if (navigation && (navigation = navigation[0])) {
                            navigation.click();
                        }
                    },
                }
            });
        })()
    </script>
@endpush

@push('scripts')
    <script type="text/x-template" id="message-form-template">

        <form action="" method="POST" data-vv-scope="message-form" enctype="multipart/form-data" @submit.prevent="sendMessage('message-form')">
            @csrf

            <div class="b2b-quote-request-section">
                <div class="b2b-quote-request-section-content">

                    <div class="form-group" :class="[errors.has('message-form.message') ? 'has-error' : '']">
                        <label for="subject" class="label-text mendatory">
                            {{ __('b2b_marketplace::app.shop.supplier.profile.write-message')}}
                        </label>

                        <textarea title="Note For Customer"type="text" v-model="data.message" class="form-style" name="message" v-validate="'required'" data-vv-as="&quot;{{ __('b2b_marketplace::app.shop.supplier.profile.message')}}&quot;">
                        </textarea>
                        <span class="control-error" v-if="errors.has('message-form.message')">@{{ errors.first('message-form.message') }}</span>
                    </div>

                    {{-- <input type="hidden" name="customer_id" value="{{$customer}}">

                    <input type="hidden" name="supplier_id" value="{{$supplier->id}}"> --}}

                    <div class="buttone-group">
                        <button class="theme-btn" :disabled="disable_button">
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
                    supplier_id: '{{ $supplierProduct->supplier->id }}',
                },

                disable_button: false,
            }),

            created() {
                @if (auth()->guard('customer')->check())
                    this.data.customer_id = "{{ auth()->guard('customer')->user()->id }}";
                @else
                    this.data.customer_id = "{{ null }}";
                @endif
            },

            methods: {

                sendMessage(formScope) {
                    var this_this = this;

                    this_this.disable_button = true;

                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {

                            this.$http.post(
                                    "{{ route('b2b_marketplace.customers.account.supplier.messages.storeProductMsg') }}",
                                    this.data)
                                .then(response => {

                                    this_this.disable_button = false;

                                    this_this.$parent.closeModal();

                                    window.showAlert(`alert-success`, this.__(
                                        'shop.general.alert.success'), response.data.message);
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
