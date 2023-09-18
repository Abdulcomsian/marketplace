<?php $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository'); ?>

@if (request()->route()->getName() == 'shop.productOrCategory.index')

    @push('css')
        <style>
            .product-detail .product-offers {
                margin-bottom: 15px;
            }
        </style>
    @endpush

    @if ($product->type != 'configurable')

        @if ($count = $productRepository->getSupplierCount($product->product))
            <div class="product-offers">
                <a href="{{ route('b2b_marketplace.product.offers.index', $product->product_id) }}">
                    {{
                        __('b2b_marketplace::app.shop.products.supplier-count', [
                            'count' => $count
                        ])
                    }}
                </a>
            </div>
        @endif
    @else
        <div class="product-offers configurable" style="display: none">
            <a href="{{ route('b2b_marketplace.product.offers.index', '_id_') }}">
                {{
                    __('b2b_marketplace::app.shop.products.supplier-count', [
                        'count' => '_count_'
                    ])
                }}
            </a>
        </div>

        <?php
            $variants = [];

            foreach ($product->product->variants as $variant) {
                $variants[$variant->id] = $productRepository->getSupplierCount($variant);
            }

        ?>

        @push('scripts')

            <script>
                var variants = @json($variants);

                eventBus.$on('configurable-variant-selected-event', function(variantId) {
                    if (typeof variants[variantId] != "undefined" && variants[variantId]) {
                        $('.product-offers.configurable').show()

                        var text = $('.product-offers.configurable a').text();
                        var href = $('.product-offers.configurable a').attr('href');

                        $('.product-offers.configurable a').text(text.replace("_count_", variants[variantId]))
                        $('.product-offers.configurable a').attr('href', href.replace("_id_", variantId))

                    } else {
                        $('.product-offers.configurable').hide()
                    }
                });

            </script>

        @endpush
    @endif

@endif