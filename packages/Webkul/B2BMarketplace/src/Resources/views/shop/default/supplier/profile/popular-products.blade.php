<?php $products = app('Webkul\B2BMarketplace\Repositories\ProductRepository')->getPopularProducts($supplier->id) ?>

@if ($products->count())
    <section class="product-items section">

        <div class="section-heading">
            <h2>
                {{ __('b2b_marketplace::app.shop.products.popular-products') }}<br/>

                <span class="seperator"></span>
            </h2>

            <a href="{{ route('b2b_marketplace.products.index', $supplier->url) }}" class="btn btn-lg btn-primary">{{ __('b2b_marketplace::app.shop.products.all-products') }}</a>
        </div>

        <div class="product-grid-4">

            @foreach ($products as $productFlat)

                @include ('shop::products.list.card', ['product' => $productFlat->product])

            @endforeach

        </div>

    </section>
@endif