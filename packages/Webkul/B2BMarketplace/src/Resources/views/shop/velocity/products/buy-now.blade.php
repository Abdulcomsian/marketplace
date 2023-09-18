{!! view_render_event('bagisto.shop.products.buy_now.before', ['product' => $product]) !!}

@if (core()->getConfigData('b2b_marketplace.settings.general.status'))
    @if($product->type == 'simple' || $product->type == 'configurable')

        @inject('InventoryHelper', 'Webkul\B2BMarketplace\Helpers\Helper')

        <button type="submit" class="theme-btn text-center buynow" {{ ! $InventoryHelper->isSaleable($product) ? 'disabled' : '' }}>
            {{ __('shop::app.products.buy-now') }}
        </button>
    @else
        <button type="submit" class="theme-btn text-center buynow" {{ ! $product->isSaleable(1) ? 'disabled' : '' }}>
            {{ __('shop::app.products.buy-now') }}
        </button>
    @endif
@else
    <button type="submit" class="theme-btn text-center buynow" {{ ! $product->isSaleable(1) ? 'disabled' : '' }}>
        {{ __('shop::app.products.buy-now') }}
    </button>
@endif

{!! view_render_event('bagisto.shop.products.buy_now.after', ['product' => $product]) !!}