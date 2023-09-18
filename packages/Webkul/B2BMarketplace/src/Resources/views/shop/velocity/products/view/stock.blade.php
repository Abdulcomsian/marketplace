{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

@if (core()->getConfigData('b2b_marketplace.settings.general.status'))
    @inject('InventoryHelper', 'Webkul\B2BMarketplace\Helpers\Helper')

    <div class="col-12 availability">
        @if($product->type == 'simple' || $product->type == 'configurable')
            <label
                class="{{! $InventoryHelper->stockHaveSufficientQuantity($product) ? '' : 'active' }} disable-box-shadow">
                    @if ( $InventoryHelper->stockHaveSufficientQuantity($product) === true )
                        {{ __('shop::app.products.in-stock') }}
                    @elseif ( $InventoryHelper->stockHaveSufficientQuantity($product) > 0 )
                        {{ __('shop::app.products.available-for-order') }}
                    @else
                        {{ __('shop::app.products.out-of-stock') }}
                    @endif
            </label>
        @else
            <label
                class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
                    @if ( $product->haveSufficientQuantity(1) === true )
                        {{ __('shop::app.products.in-stock') }}
                    @elseif ( $product->haveSufficientQuantity(1) > 0 )
                        {{ __('shop::app.products.available-for-order') }}
                    @else
                        {{ __('shop::app.products.out-of-stock') }}
                    @endif
            </label>
        @endif
    </div>
@else
    <div class="col-12 availability">
        <label
            class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
                @if ( $product->haveSufficientQuantity(1) === true )
                    {{ __('shop::app.products.in-stock') }}
                @elseif ( $product->haveSufficientQuantity(1) > 0 )
                    {{ __('shop::app.products.available-for-order') }}
                @else
                    {{ __('shop::app.products.out-of-stock') }}
                @endif
        </label>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}