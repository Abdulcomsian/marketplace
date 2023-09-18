<?php

    $supplierRepository = app('Webkul\B2BMarketplace\Repositories\SupplierRepository');

    $productRepository = app('Webkul\B2BMarketplace\Repositories\ProductRepository');

    $reviewRepository = app('Webkul\B2BMarketplace\Repositories\ReviewRepository');

    if (isset($item->additional['supplier_info']) && !$item->additional['supplier_info']['is_owner']) {
        $supplier = $supplierRepository->find($item->additional['supplier_info']['supplier_id']);
    } else {
        $supplier = $productRepository->getSupplierByProductId($item->product_id);
    }

?>

@if ($supplier && $supplier->is_approved)

    <?php $supplierProduct = $productRepository->getMarketplaceProductByProduct($item->product->id, $supplier->id); ?>

    @if (isset($supplierProduct) && $supplierProduct->is_approved)

        <div class="seller-info" style="margin-bottom: 10px;">

            {!!
                __('b2b_marketplace::app.shop.products.sold-by', [
                        'url' => "<a href=" . route('b2b_marketplace.supplier.show', $supplier->url) . ">" . $supplier->company_name . " [<span class='icon star-blue-icon' style='vertical-align: text-top'></span>" . $reviewRepository->getAverageRating($supplier) . "]</a>"
                    ])
            !!}

        </div>

    @endif

@endif