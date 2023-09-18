@extends('b2b_marketplace::shop.layouts.master')

@section('page_title')
    {{'Products -'. $supplier->company_name}}
@endsection

@section('content-wrapper')

    @inject ('productRepository', 'Webkul\B2BMarketplace\Repositories\ProductRepository')

    <div class="main">

        {!! view_render_event('b2b_marketplace.shop.suppliers.products.index.before', ['supplier' => $supplier]) !!}

        <div class="profile-container" style="margin-bottom: 40px;">
            @include('b2b_marketplace::shop.supplier.profile.top-profile')
        </div>

        <section class="category-container seller-products">

            <div class="category-container">

                @include ('shop::products.list.layered-navigation', ['category' => null])

                <div class="category-block">

                    <?php $products = $productRepository->findAllBySupplier($supplier); ?>

                    @if ($products->count())

                        @include ('shop::products.list.toolbar')

                        @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

                        @if ($toolbarHelper->getCurrentMode() == 'grid')
                            <div class="product-grid-3">
                                @foreach ($products as $productFlat)

                                    @include ('shop::products.list.card', ['product' => $productFlat])

                                @endforeach
                            </div>
                        @else
                            <div class="product-list">
                                @foreach ($products as $productFlat)

                                    @include ('shop::products.list.card', ['product' => $productFlat])

                                @endforeach
                            </div>
                        @endif

                        {!! view_render_event('b2b_marketplace.shop.sellers.products.index.pagination.before') !!}

                        <div class="bottom-toolbar">
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                        {!! view_render_event('b2b_marketplace.shop.suppliers.products.index.pagination.after') !!}

                    @else
                        <div class="product-list empty">
                            <h2>{{ __('shop::app.products.whoops') }}</h2>

                            <p>
                                {{ __('shop::app.products.empty') }}
                            </p>
                        </div>

                    @endif

                </div>
            </div>

        </section>

        {!! view_render_event('b2b_marketplace.shop.suppliers.products.index.after', ['supplier' => $supplier]) !!}

    </div>

@endsection