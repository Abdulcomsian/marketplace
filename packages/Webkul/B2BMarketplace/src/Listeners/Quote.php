<?php

namespace Webkul\B2BMarketplace\Listeners;

use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuote;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\B2BMarketplace\Repositories\SupplierRepository as Supplier;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Product\Repositories\ProductImageRepository;

/**
 * Quote Product Event Handler
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Quote
{
    /**
     * Product Flat Repository object
     *
     * @var ProductFlat
    */
    protected $productFlat;

    /**
     * Product Repository object
     *
     * @var Product
    */
    protected $product;

    /**
     * SupplierQuote Repository object
     *
     * @var Product
    */
    protected $SupplierQuote;

    /**
     * SupplierProduct Repository object
     *
     * @var SupplierProduct
    */
    protected $supplierProduct;

    /**
     * Supplier Repository object
     *
     * @var Supplier
    */
    protected $supplier;

    /**
     * AttributeFamilyRepository object
     *
     * @var AttributeFamily
    */
    protected $attributeFamilyRepository;

    /**
     * InventorySourceRepository object
     *
     * @var InventorySource
    */
    protected $inventorySourceRepository;

    /**
     * ChannelRepository object
     *
     * @var Channel
    */
    protected $channelRepository;

    /**
     * ChannelRepository object
     *
     * @var Channel
    */
    protected $productImageRepository;

    /**
     * Create Quote Product event listener instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $product
     * @param  Webkul\Product\Repositories\ProductFlatRepository $productFlat
     * @param  Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository $supplierQuote
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository $supplierProduct
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplier
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamilyRepository
     * @return void
     */
    public function __construct(
        ProductFlat $productFlat,
        Product $product,
        SupplierQuote $supplierQuote,
        SupplierProduct $supplierProduct,
        Supplier $supplier,
        AttributeFamilyRepository $attributeFamilyRepository,
        InventorySourceRepository $inventorySourceRepository,
        ChannelRepository $channelRepository,
        ProductImageRepository $productImageRepository
    )
    {
        $this->productFlat = $productFlat;

        $this->product = $product;

        $this->supplierQuote = $supplierQuote;

        $this->supplierProduct = $supplierProduct;

        $this->supplier = $supplier;

        $this->attributeFamilyRepository = $attributeFamilyRepository;

        $this->inventorySourceRepository = $inventorySourceRepository;

        $this->channelRepository = $channelRepository;

        $this->productImageRepository = $productImageRepository;
    }

    /**
     * After Add to Cart Quote Create QuoteProduct
     *
     * @param mixed $invoice
     */
    public function createQuoteProduct()
    {
        $data = request()->all();

        $inventory_id = $this->inventorySourceRepository->findOneByField('code', 'default')->id;

        if (isset($data['quote_id'])) {

            $locale = request()->get('locale') ?: app()->getLocale();
            $channel = request()->get('channel') ?: core()->getDefaultChannelCode();

            $coreProduct = $this->productFlat->findOneWhere(['product_id' => $data['product']]);
            $quote = $this->supplierQuote->findOneWhere(['id' => $data['quote_id']]);
            $supplierId = $this->supplier->findOneWhere(['id' => $data['supplier_id']])->id;

            $sku = 'bulk-product-' . $quote->id;

            $supplierBulkProduct = $this->product->findOneWhere(['sku' => $sku]);

            if($supplierBulkProduct) {

                $inventory = $supplierBulkProduct->inventories->first();
                $inventory->qty += $quote['quantity'];
                $inventory->save();

                request()->merge([
                    'product_id' => $supplierBulkProduct->id,
                ]);
            } else {
                $productData['type'] = 'simple' ;
                $productData['attribute_family_id'] = $this->attributeFamilyRepository->findOneByField('code', 'default')->id;
                $productData['sku'] = $sku;

                $quoteProduct = $this->product->create($productData);

                $supplierProduct = $this->supplierProduct->createQuoteProduct([
                    'product_id' => $quoteProduct['id'],
                    'is_owner' => 1,
                    'supplier_id' => $supplierId,
                    'quote_product_id' => $data['quote_id']
                ]);

                if ($quoteProduct) {
                    request()->merge([
                        'product_id' => $quoteProduct->id,
                    ]);

                    $productFlat['name'] = $coreProduct->name;
                    $productFlat['url_key'] = $sku;
                    $productFlat['status'] = 1;
                    $productFlat['description'] = $coreProduct->description;
                    $productFlat['short_description'] = $coreProduct->short_description;
                    $productFlat['price'] = $quote['price_per_quantity'];
                    $productFlat['weight'] = $coreProduct->weight;
                    $productFlat['inventories'] = [
                        $inventory_id => $quote['quantity']
                    ];
                    $productFlat['locale'] = $locale;
                    $productFlat['channel'] = $channel;
                    $productFlat['channels'] = [
                        '0' => core()->getDefaultChannel()->id
                    ];
                    $productFlat['vendor_id'] = $supplierId;

                    $this->supplierProduct->updateSupplierProduct($productFlat, $quoteProduct->id);

                    $this->supplierProduct->update($productFlat, $supplierProduct->id);

                    if (count($coreProduct->images) > 0) {
                        $productImage = $coreProduct->images->first();

                        $image = [
                            'path'       => $productImage->path,
                            'product_id' => $quoteProduct->id
                        ];

                        $this->productImageRepository->create($image);
                    }
                }
            }
        }
    }
}