@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject('InventoryHelper', 'Webkul\B2BMarketplace\Helpers\Helper')

@php
    $showCompare = core()->getConfigData('general.content.shop.compare_option') == '1' ? true : false;
    
    $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == '1' ? true : false;
@endphp

@if (core()->getConfigData('b2b_marketplace.settings.general.status'))

    @if ($product->type == 'simple' || $product->type == 'configurable')

        @if (core()->getConfigData('b2b_marketplace.settings.general.allow_rfq'))

            <div class="default-wrap" style="margin-bottom: 20px;">
                @if ($InventoryHelper->isSaleable($product))
                    <a href="{{ route('b2b_marketplace.home.rfq', $product->product_id) }}"
                        class="rfq-btn btn btn-lg btn-primary addtocart">

                        <i class="icon icon-cart"></i>

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
                    <a href="" onclick="return false;" class="rfq-btn btn btn-lg btn-primary addtocart">

                        <i class="icon icon-cart"></i>

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
        @endif

        <div class="{{ $toolbarHelper->isModeActive('grid') ? 'default-wrap' : 'default-wrap' }}">
            <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-lg btn-primary addtocart"
                    {{ $InventoryHelper->isSaleable($product) ? '' : 'disabled' }}>{{ $product->type == 'booking' ? __('shop::app.products.book-now') : __('shop::app.products.add-to-cart') }}</button>
            </form>

            @if ($showWishlist)
                @include('shop::products.wishlist')
            @endif

            @if ($showCompare)
                @include('shop::products.compare', [
                    'productId' => $product->id,
                ])
            @endif
        </div>
    @else
        <div class="{{ $toolbarHelper->isModeActive('grid') ? 'cart-wish-wrap' : 'default-wrap' }}">
            <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-lg btn-primary addtocart"
                    {{ $product->isSaleable() ? '' : 'disabled' }}>{{ $product->type == 'booking' ? __('shop::app.products.book-now') : __('shop::app.products.add-to-cart') }}</button>
            </form>

            @if ($showWishlist)
                @include('shop::products.wishlist')
            @endif

            @if ($showCompare)
                @include('shop::products.compare', [
                    'productId' => $product->id,
                ])
            @endif
        </div>
    @endif
@else
    <div class="{{ $toolbarHelper->isModeActive('grid') ? 'cart-wish-wrap' : 'default-wrap' }}">
        <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <input type="hidden" name="quantity" value="1">
            <button class="btn btn-lg btn-primary addtocart"
                {{ $product->isSaleable() ? '' : 'disabled' }}>{{ $product->type == 'booking' ? __('shop::app.products.book-now') : __('shop::app.products.add-to-cart') }}</button>
        </form>

        @if ($showWishlist)
            @include('shop::products.wishlist')
        @endif

        @if ($showCompare)
            @include('shop::products.compare', [
                'productId' => $product->id,
            ])
        @endif
    </div>
@endif
