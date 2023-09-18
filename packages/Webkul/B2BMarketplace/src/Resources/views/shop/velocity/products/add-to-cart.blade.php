{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}
{{-- if b2b marketplace is enable --}}
@if (core()->getConfigData('b2b_marketplace.settings.general.status'))
    @inject('InventoryHelper', 'Webkul\B2BMarketplace\Helpers\Helper')

    <div class="mx-0 no-padding">
        @if (isset($showCompare) && $showCompare)
            <compare-component
                @auth('customer')
                    customer="true"
                @endif

                @guest('customer')
                    customer="false"
                @endif

                slug="{{ $product->url_key }}"
                product-id="{{ $product->id }}"
                add-tooltip="{{ __('velocity::app.customer.compare.add-tooltip') }}"
            ></compare-component>
        @endif

        @if (! (isset($showWishlist) && !$showWishlist) && core()->getConfigData('general.content.shop.wishlist_option'))
            @include('shop::products.wishlist', [
                'addClass' => $addWishlistClass ?? ''
            ])
        @endif

        @if($product->type == 'simple' || $product->type == 'configurable')

            {{-- @if(core()->getConfigData('b2b_marketplace.settings.general.allow_rfq'))
                <div class="add-to-cart-btn pl0" style="margin-bottom: 5px; margin-right: 10px;">
                    @if($InventoryHelper->isSaleable($product))
                        <a href="{{route('b2b_marketplace.home.rfq', $product->product_id)}}" class="btn btn-add-to-cart small-padding" style="padding: 3px 7px!important;">

                            <i class="icon icon-cart" style="font-size: 22px !important;"></i>

                            <span>
                                @auth('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.request-title') }}
                                @endauth

                                @guest('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.login-title') }}
                                @endguest
                            </span>
                        </a>
                    @else
                        <a href="" onclick="return false;" class="rfq-btn btn btn-add-to-cart small-padding" style="padding: 3px 4px !important;">

                            <i class="icon icon-cart" style="font-size: 22px !important;"></i>

                            <span>
                                @auth('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.request-title') }}
                                @endauth

                                @guest('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.login-title') }}
                                @endguest
                            </span>
                        </a>
                    @endif
                </div>
            @endif --}}

            <div class="add-to-cart-btn pl0">

                @if (isset($form) && !$form)
                    <button
                        type="submit"
                        {{ ! $InventoryHelper->isSaleable($product) ? 'disabled' : '' }}
                        class="theme-btn {{ $addToCartBtnClass ?? '' }}">

                        @if (! (isset($showCartIcon) && !$showCartIcon))
                            <i class="material-icons text-down-3">shopping_cart</i>
                        @endif

                        {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
                    </button>
                @elseif(isset($addToCartForm) && !$addToCartForm)
                    <form
                        method="POST"
                        action="{{ route('cart.add', $product->product_id) }}">

                        @csrf

                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button
                            type="submit"
                            {{ ! $InventoryHelper->isSaleable($product) ? 'disabled' : '' }}
                            class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                            @if (! (isset($showCartIcon) && !$showCartIcon))
                                <i class="material-icons text-down-3">shopping_cart</i>
                            @endif

                            <span class="fs14 fw6 text-uppercase text-up-4">
                                {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}
                            </span>
                        </button>
                    </form>
                @else
                    <add-to-cart
                        form="true"
                        csrf-token='{{ csrf_token() }}'
                        product-flat-id="{{ $product->id }}"
                        product-id="{{ $product->product_id }}"
                        reload-page="{{ $reloadPage ?? false }}"
                        move-to-cart="{{ $moveToCart ?? false }}"
                        wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                        add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                        is-enable={{ ! $InventoryHelper->isSaleable($product) ? 'false' : 'true' }}
                        show-cart-icon={{ ! (isset($showCartIcon) && ! $showCartIcon) }}
                        btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}">
                    </add-to-cart>
                @endif

                @if(core()->getConfigData('b2b_marketplace.settings.general.allow_rfq'))
                    @if($InventoryHelper->isSaleable($product))
                        <a href="{{route('b2b_marketplace.home.rfq', $product->product_id)}}" class="btn btn-add-to-cart small-padding" style="padding: 3px 4px !important; margin-top: 5px;">

                            <i class="icon icon-cart" style="font-size: 22px !important; padding: 0px;padding-top: 20px;"></i>

                            <span>
                                @auth('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.request-title') }}
                                @endauth

                                @guest('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.login-title') }}
                                @endguest
                            </span>
                        </a>
                    @else
                        <a href="" onclick="return false;" class="rfq-btn btn btn-add-to-cart small-padding" style="padding: 3px 4px !important; margin-top: 5px;">

                            <i class="icon icon-cart" style="font-size: 22px !important;"></i>

                            <span>
                                @auth('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.request-title') }}
                                @endauth

                                @guest('customer')
                                    {{ __('b2b_marketplace::app.shop.products.rfq.login-title') }}
                                @endguest
                            </span>
                        </a>
                    @endif
                @endif
            </div>
        @else
            <div class="add-to-cart-btn pl0">
                @if (isset($form) && !$form)
                    <button
                        type="submit"
                        {{ ! $product->isSaleable() ? 'disabled' : '' }}
                        class="theme-btn {{ $addToCartBtnClass ?? '' }}">

                        @if (! (isset($showCartIcon) && !$showCartIcon))
                            <i class="material-icons text-down-3">shopping_cart</i>
                        @endif

                        {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
                    </button>
                @elseif(isset($addToCartForm) && !$addToCartForm)
                    <form
                        method="POST"
                        action="{{ route('cart.add', $product->product_id) }}">

                        @csrf

                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button
                            type="submit"
                            {{ ! $product->isSaleable() ? 'disabled' : '' }}
                            class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                            @if (! (isset($showCartIcon) && !$showCartIcon))
                                <i class="material-icons text-down-3">shopping_cart</i>
                            @endif

                            <span class="fs14 fw6 text-uppercase text-up-4">
                                {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}
                            </span>
                        </button>
                    </form>
                @else
                    <add-to-cart
                        form="true"
                        csrf-token='{{ csrf_token() }}'
                        product-flat-id="{{ $product->id }}"
                        product-id="{{ $product->product_id }}"
                        reload-page="{{ $reloadPage ?? false }}"
                        move-to-cart="{{ $moveToCart ?? false }}"
                        wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                        add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                        is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                        show-cart-icon={{ ! (isset($showCartIcon) && ! $showCartIcon) }}
                        btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}">
                    </add-to-cart>
                @endif
            </div>

        @endif
    </div>
@else

    <div class="mx-0 no-padding">
        @if (isset($showCompare) && $showCompare)
            <compare-component
                @auth('customer')
                    customer="true"
                @endif

                @guest('customer')
                    customer="false"
                @endif

                slug="{{ $product->url_key }}"
                product-id="{{ $product->id }}"
                add-tooltip="{{ __('velocity::app.customer.compare.add-tooltip') }}"
            ></compare-component>
        @endif

        @if (! (isset($showWishlist) && !$showWishlist) && core()->getConfigData('general.content.shop.wishlist_option'))
            @include('shop::products.wishlist', [
                'addClass' => $addWishlistClass ?? ''
            ])
        @endif

        <div class="add-to-cart-btn pl0">
            @if (isset($form) && !$form)
                <button
                    type="submit"
                    {{ ! $product->isSaleable() ? 'disabled' : '' }}
                    class="theme-btn {{ $addToCartBtnClass ?? '' }}">

                    @if (! (isset($showCartIcon) && !$showCartIcon))
                        <i class="material-icons text-down-3">shopping_cart</i>
                    @endif

                    {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
                </button>
            @elseif(isset($addToCartForm) && !$addToCartForm)
                <form
                    method="POST"
                    action="{{ route('cart.add', $product->product_id) }}">

                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button
                        type="submit"
                        {{ ! $product->isSaleable() ? 'disabled' : '' }}
                        class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                        @if (! (isset($showCartIcon) && !$showCartIcon))
                            <i class="material-icons text-down-3">shopping_cart</i>
                        @endif

                        <span class="fs14 fw6 text-uppercase text-up-4">
                            {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}
                        </span>
                    </button>
                </form>
            @else
                <add-to-cart
                    form="true"
                    csrf-token='{{ csrf_token() }}'
                    product-flat-id="{{ $product->id }}"
                    product-id="{{ $product->product_id }}"
                    reload-page="{{ $reloadPage ?? false }}"
                    move-to-cart="{{ $moveToCart ?? false }}"
                    wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                    add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                    is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                    show-cart-icon={{ ! (isset($showCartIcon) && ! $showCartIcon) }}
                    btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}">
                </add-to-cart>
            @endif
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}