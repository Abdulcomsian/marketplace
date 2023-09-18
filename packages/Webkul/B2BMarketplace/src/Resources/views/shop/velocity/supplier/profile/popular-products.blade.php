<?php $products = app('Webkul\B2BMarketplace\Repositories\ProductRepository')->getPopularProducts($supplier->id) ?>

@if ($products->count())
    <section class="product-items section">

        <div class="section-heading">
            <h2>
                {{ __('b2b_marketplace::app.shop.products.popular-products') }}<br/>

                <span class="seperator"></span>
            </h2>

            <a href="{{ route('b2b_marketplace.products.index', $supplier->url) }}" class="theme-btn" style="float: right;"> {{ __('b2b_marketplace::app.shop.products.all-products') }}
            </a>
        </div>

        <div class="VueCarousel navigation-hide pagination-hide" id="fearured-products-carousel">
            <div class="VueCarousel-wrapper">
                <div class="VueCarousel-inner" style="transition: transform 0.5s ease 0s; flex-basis: 208px;">
                    <div tabindex="-1" role="tabpanel" class="VueCarousel-slide" style="display: flex;">
                        @foreach ($products as $productFlat)

                            @include ('velocity::products.list.card', ['product' => $productFlat->product])

                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class=" VueCarousel-inner product-grid-4" >
            <div tabindex="-1" role="tabpanel" class="VueCarousel-slide">
                @foreach ($products as $productFlat)

                    @include ('velocity::products.list.card', ['product' => $productFlat->product])

                @endforeach
            </div>
        </div> --}}

    </section>
@endif
