{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}
@inject('InventoryHelper', 'Webkul\B2BMarketplace\Helpers\Helper')

@php
    $width = (core()->getConfigData('catalog.products.storefront.buy_now_button_display') == 1) ? '49' : '95';
@endphp

@if (core()->getConfigData('b2b_marketplace.settings.general.status'))
    @if($product->type == 'simple' || $product->type == 'configurable')

        @if(core()->getConfigData('b2b_marketplace.settings.general.allow_rfq'))
            @push('scripts')

                <style type="text/css">

                    .add-to-buttons {
                        display: block !important;
                    }


                </style>
            @endpush

            <div class="default-wrap" style="margin-bottom: 20px;">

                @if($InventoryHelper->isSaleable($product))
                    <a href="{{route('b2b_marketplace.home.rfq', $product->product_id)}}" class="rfq-btn btn btn-lg btn-primary addtocart" style="width: <?php echo $width.'%';?>; text-align: center; padding: 10px 20px;">

                        <i class="icon icon-cart"></i>

                        <span>@auth('customer')
                                {{ __('b2b_marketplace::app.shop.products.rfq.request-title') }}
                            @endauth

                            @guest('customer')
                                {{ __('b2b_marketplace::app.shop.products.rfq.login-title') }}
                            @endguest
                        </span>
                    </a>
                @else
                    <a href="" onclick="return false;" class="rfq-btn btn btn-lg btn-primary addtocart" style="width: <?php echo $width.'%';?>; text-align: center; padding: 10px 20px;">

                        <i class="icon icon-cart"></i>

                        <span>@auth('customer')
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

        <button type="submit" class="btn btn-lg btn-primary addtocart" {{ ! $InventoryHelper->isSaleable($product) ? 'disabled' : '' }}
        style="width: <?php echo $width.'%';?>;">
            {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
        </button>
    @else
        <button type="submit" class="btn btn-lg btn-primary addtocart" {{ ! $product->isSaleable() ? 'disabled' : '' }}
        style="width: <?php echo $width.'%';?>;">
            {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
        </button>
    @endif
@else
    <button type="submit" class="btn btn-lg btn-primary addtocart" {{ ! $product->isSaleable() ? 'disabled' : '' }}
    style="width: <?php echo $width.'%';?>;">
        {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
    </button>
@endif


{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}