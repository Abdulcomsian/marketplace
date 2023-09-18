@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords }}"/>
@stop

@section('content-wrapper')

    <?php
        $baseProduct = $product->parent_id ? $product->parent : $product;

        $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository');
    ?>

    {!! view_render_event('bagisto.shop.supplier.products.offers.before', ['product' => $product]) !!}

    <div class="product-offer-container">

        <div class="product">
            <div class="product-information">

                <?php $productBaseImage = productimage()->getProductBaseImage($product); ?>

                <div class="product-logo-block">
                    <a href="{{ route('shop.productOrCategory.index', $baseProduct->url_key) }}" title="{{ $baseProduct->name }}">
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

        <div class="seller-product-list">
            <h2 class="heading">{{ __('b2b_marketplace::app.shop.products.more-suppliers') }}</h2>

            <div class="content">

                @foreach ($productRepository->getSupplierProducts($product) as $supplierProduct)
                    <form action="{{ route('cart.add', $baseProduct->id) }}" method="POST">
                        @csrf()
                        <input type="hidden" name="product_id" value="{{ $baseProduct->id }}">
                        <input type="hidden" name="supplier_info[product_id]" value="{{ $supplierProduct->id }}">
                        <input type="hidden" name="supplier_info[supplier_id]" value="{{ $supplierProduct->supplier->id }}">
                        <input type="hidden" name="supplier_info[is_owner]" value="0">

                        @if ($baseProduct->type == 'configurable')
                            <input type="hidden" name="selected_configurable_option" value="{{ $product->id }}">

                            @foreach ($attributes as $attributeId => $optionId)
                                <input type="hidden" name="super_attribute[{{$attributeId}}]" value="{{$optionId}}"/>
                            @endforeach
                        @endif

                        <div class="seller-product-item">

                            <div class="product-info-top table">

                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="profile-logo-block">
                                                    @if ($logo = $supplierProduct->supplier->addresses->logo_url)
                                                        <img src="{{ $logo }}" />
                                                    @else
                                                        <img src="{{ bagisto_asset('themes/b2b/assets/images/default-logo.svg') }}" />
                                                    @endif
                                                </div>

                                                <div class="profile-information-block">
                                                    <a href="{{ route('b2b_marketplace.supplier.show', $supplierProduct->supplier->url) }}" class="shop-title">
                                                        {{ $supplierProduct->supplier->company_name }}
                                                    </a>

                                                    <div class="review-information">

                                                        <?php $reviewRepository = app('Webkul\B2BMarketplace\Repositories\ReviewRepository') ?>

                                                        <span class="stars">
                                                            <span class="icon star-icon"></span>

                                                            {{
                                                                __('b2b_marketplace::app.shop.products.supplier-total-rating', [
                                                                        'avg_rating' => $reviewRepository->getAverageRating($supplierProduct->supplier),
                                                                        'total_rating' => $reviewRepository->getTotalRating($supplierProduct->supplier),
                                                                    ])
                                                            }}
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
                                                <div class="product-price">
                                                    @if ($product->getTypeInstance()->haveSpecialPrice($supplierProduct))
                                                        <div class="sticker sale">
                                                            {{ __('shop::app.products.sale') }}
                                                        </div>

                                                        <span class="regular-price">{{ core()->currency($supplierProduct->price) }}</span>

                                                        <span class="special-price">{{ core()->currency($product->getTypeInstance()->getSpecialPrice($supplierProduct)) }}</span>
                                                    @else
                                                        <span>{{ core()->currency($supplierProduct->price) }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                <div class="control-group">
                                                    <input type="text" name="quantity" value="1" class="control">
                                                </div>

                                                @if ($supplierProduct->haveSufficientQuantity(1))

                                                    <button type="submit" class="btn btn-black btn-lg">
                                                        {{ __('b2b_marketplace::app.shop.products.add-to-cart') }}
                                                    </button>
                                                @else

                                                    <div class="stock-status">
                                                        {{ __('b2b_marketplace::app.shop.products.out-of-stock') }}
                                                    </div>

                                                @endif

                                                @if (auth()->guard('customer')->check())
                                                    <a type="button" id="shopMessage"class="btn btn-lg btn-primary" @click="showModal('shopMessage')" style="margin-left: 5px;">
                                                        {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier')}}
                                                    </a>
                                                @else
                                                    <a  type="button" href="{{route('customer.session.index')}}" class="btn btn-lg btn-primary" style="margin-left: 5px;">
                                                        {{ __('b2b_marketplace::app.shop.supplier.profile.message-supplier')}}
                                                    </a>
                                                @endif
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

                                        <div class="product-images-block">
                                            <carousel :per-page="1" pagination-active-color="#979797" pagination-color="#E8E8E8">
                                                @foreach ($productImages as $productImage)

                                                    <slide>
                                                        <div class="product-image">
                                                            <img src="{{ $productImage['medium_image_url'] }}" />
                                                        </div>
                                                    </slide>

                                                @endforeach
                                            </carousel>
                                        </div>

                                        <div class="product-images-block" style="display: contents;">
                                            @php
                                                $productVideos = $baseSupplierProduct->assignVideos;

                                                foreach ($productVideos as $key => $video) {
                                                    $videoData[$key]['type'] = $video->type;
                                                    $videoData[$key]['large_image_url'] = $videoData[$key]['small_image_url']= $videoData[$key]['medium_image_url']= $videoData[$key]['original_image_url'] = $video->path;
                                                }
                                            @endphp

                                            @if(count($productVideos) > 0)

                                                <carousel :per-page="1" pagination-active-color="#979797" pagination-color="#E8E8E8">
                                                    @foreach ($videoData as $productVideo)

                                                        {{-- <slide>
                                                            <div class="product-image">
                                                                <img src="{{ $productVideo['medium_image_url'] }}" />
                                                            </div>
                                                        </slide> --}}

                                                        <slide>
                                                            <div class="product-image">
                                                                <video width='200' height='112'
                                                                controls>
                                                                    <source src="{{bagisto_asset('themes/b2b/assets/storage/' . $productVideo['medium_image_url'])}}" type="video/mp4">
                                                                    {{ __('admin::app.catalog.products.not-support-video') }}
                                                                </video>
                                                            </div>
                                                        </slide>

                                                    @endforeach
                                                </carousel>
                                            @endif
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

        <h3 slot='header'>{{$supplierProduct->supplier->company_name}}</h3>

        <div slot="body">
            @if($supplierProduct->supplier->is_verified)
                <div style="display:grid;">
                    <span class="verified-supplier" style="color: green;">
                        {{ __('b2b_marketplace::app.shop.supplier.profile.verified')}}
                    </span>

                    <span class="icon verification-icon active"></span>
                </div>
            @endif

            <message-form></message-form>
        </div>
    </modal>

    {!! view_render_event('bagisto.shop.suppliers.products.offers.after', ['product' => $product]) !!}

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $(".VueCarousel-dot").click(function(event){
                event.preventDefault();
            });
        });
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
                    supplier_id: '{{$supplierProduct->supplier->id}}',
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

    </script>
@endpush