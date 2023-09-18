<?php

namespace Webkul\B2BMarketplace\Listeners;

use Illuminate\Support\Facades\Schema;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\B2BMarketplace\Repositories\ProductRepository as B2BProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;

/**
 * supplier Product Flat Event handler
 *
 * @copyright 2020 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductFlat
{
    /**
     * AttributeRepository Repository Object
     *
     * @var object
     */
    protected $attributeRepository;

    /**
     * AttributeOptionRepository Repository Object
     *
     * @var object
     */
    protected $attributeOptionRepository;

    /**
     * ProductFlatRepository Repository Object
     *
     * @var object
     */
    protected $productFlatRepository;

    /**
     * ProductAttributeValueRepository Repository Object
     *
     * @var object
     */
    protected $productAttributeValueRepository;

    /**
     * Attribute Object
     *
     * @var object
     */
    protected $attribute;

    /**
     * B2B ProductRepository Object
     *
     * @var object
     */
    protected $b2BProductRepository;

    /**
     * ProductInventoryRepository object
     *
     * @var ProductInventoryRepository
     */
    protected $productInventoryRepository;

    /**
     * @var object
     */
    public $attributeTypeFields = [
        'text' => 'text',
        'textarea' => 'text',
        'price' => 'float',
        'boolean' => 'boolean',
        'select' => 'integer',
        'multiselect' => 'text',
        'datetime' => 'datetime',
        'date' => 'date',
        'file' => 'text',
        'image' => 'text',
        'checkbox' => 'text'
    ];

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository     $attributeOptionRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository           $productFlatRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository,
        ProductFlatRepository $productFlatRepository,
        ProductAttributeValueRepository $productAttributeValueRepository,
        B2BProductRepository $b2BProductRepository,
        ProductInventoryRepository $productInventoryRepository
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->b2BProductRepository = $b2BProductRepository;

        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * Creates product flat
     *
     * @param Product $product
     * @return void
     */
    public function afterProductCreatedUpdated($product)
    {
        $this->createFlat($product);

        if ($product->type == 'configurable') {
            foreach ($product->variants()->get() as $variant) {
                $this->createFlat($variant, $product);
            }
        }
    }

    /**
     * Creates product flat
     *
     * @param Product $product
     * @param Product $parentProduct
     * @return void
     */
    public function createFlat($product, $parentProduct = null)
    {
        static $familyAttributes = [];

        static $superAttributes = [];

        if (! array_key_exists($product->attribute_family->id, $familyAttributes))
            $familyAttributes[$product->attribute_family->id] = $product->attribute_family->custom_attributes;

        if ($parentProduct && ! array_key_exists($parentProduct->id, $superAttributes))
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();

        if (isset($product['channels'])) {
            foreach ($product['channels'] as $channel) {
                $channel = app('Webkul\Core\Repositories\ChannelRepository')->findOrFail($channel);
                $channels[] = $channel['code'];
            }
        } else if (isset($parentProduct['channels'])){
            foreach ($parentProduct['channels'] as $channel) {
                $channel = app('Webkul\Core\Repositories\ChannelRepository')->findOrFail($channel);
                $channels[] = $channel['code'];
            }
        } else {
            $channels[] = core()->getDefaultChannelCode();
        }

        foreach (core()->getAllChannels() as $channel) {
            if (in_array($channel->code, $channels)) {
                foreach ($channel->locales as $locale) {
                    $productFlat = $this->productFlatRepository->findOneWhere([
                        'product_id' => $product->id,
                        'channel' => $channel->code,
                        'locale' => $locale->code
                    ]);

                    if (! $productFlat) {
                        $productFlat = $this->productFlatRepository->create([
                            'product_id' => $product->id,
                            'channel' => $channel->code,
                            'locale' => $locale->code
                        ]);
                    }

                    foreach ($familyAttributes[$product->attribute_family->id] as $attribute) {
                        if ($parentProduct && ! in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], ['sku', 'name', 'price', 'weight', 'status'])))
                            continue;

                        if (in_array($attribute->code, ['tax_category_id']))
                            continue;

                        if (! Schema::hasColumn('product_flat', $attribute->code))
                            continue;

                        if ($attribute->value_per_channel) {
                            if ($attribute->value_per_locale) {
                                $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                            } else {
                                $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('attribute_id', $attribute->id)->first();
                            }
                        } else {
                            if ($attribute->value_per_locale) {
                                $productAttributeValue = $product->attribute_values()->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                            } else {
                                $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                            }
                        }

                        if ($product->type == 'configurable' && $attribute->code == 'price') {
                            try {
                                $productFlat->{$attribute->code} = app('Webkul\B2BMarketplace\Helpers\Price')->getVariantMinPrice($product);
                            } catch (\Exception $e) {}
                        } else {
                            try {
                                $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]];
                            } catch (\Exception $e) {}
                        }

                        if ($attribute->type == 'select') {
                            $attributeOption = $this->attributeOptionRepository->find($product->{$attribute->code});

                            if ($attributeOption) {
                                if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                    $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                                } else {
                                    $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                                }
                            }
                        } elseif ($attribute->type == 'multiselect') {
                            $attributeOptionIds = explode(',', $product->{$attribute->code});

                            if (count($attributeOptionIds)) {
                                $attributeOptions = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);

                                $optionLabels = [];

                                foreach ($attributeOptions as $attributeOption) {
                                    if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                        $optionLabels[] = $attributeOptionTranslation->label;
                                    } else {
                                        $optionLabels[] = $attributeOption->admin_name;
                                    }
                                }

                                $productFlat->{$attribute->code . '_label'} = implode(', ', $optionLabels);
                            }
                        }
                    }

                    $productFlat->sku        = $product->sku;

                    $productFlat->name       = $product->name;

                    $productFlat->created_at = $product->created_at;

                    $productFlat->updated_at = $product->updated_at;

                    if ($parentProduct) {
                        $parentProductFlat = $this->productFlatRepository->findOneWhere([
                                'product_id' => $parentProduct->id,
                                'channel' => $channel->code,
                                'locale' => $locale->code
                            ]);

                        if ($parentProductFlat) {
                            $productFlat->parent_id = $parentProductFlat->id;
                        }
                    }

                    $productFlat->save();
                }
            } else {
                $route = request()->route() ? request()->route()->getName() : "";

                if ($route == 'admin.catalog.products.update') {
                    $productFlat = $this->productFlatRepository->findOneWhere([
                        'product_id' => $product->id,
                        'channel' => $channel->code,
                    ]);

                    if ($productFlat) {
                        $this->productFlatRepository->delete($productFlat->id);
                    }
                }
            }
        }
    }

    /**
     * Update Supplier Product Inventory
     *
     * @param Product $product
     * @return void
     */
    public function BeforeProductCreate($product) {

        $data = request()->all();

        if(!empty($data['name']))
        {
            $product->name = $data['name'];

            $this->createFlat($product);
        }

        if (! isset($data['variants'])) {
            $supplierProduct = $this->b2BProductRepository->findOneWhere([
                'product_id' => $product->id,
                'is_owner' => 1
            ]);

            if (isset($supplierProduct)) {

                $this->productInventoryRepository->saveInventories(array_merge($data, [
                    'vendor_id' => $supplierProduct->supplier_id
                ]), $supplierProduct->product);
            }
        } else if(isset($data['variants'])) {

            foreach($data['variants'] as $productId => $variant) {

                $supplierProduct = $this->b2BProductRepository->findOneWhere([
                    'product_id' => $productId,
                    'is_owner' => 1
                ]);

                if (isset($supplierProduct)) {

                    $this->productInventoryRepository->saveInventories(array_merge($variant, [
                        'vendor_id' => $supplierProduct->supplier_id
                    ]), $supplierProduct->product);
                }
            }
        }

    }
}