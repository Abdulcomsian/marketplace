<?php

namespace Webkul\B2BMarketplace\Repositories;

use DB;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductVideoRepository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\B2BMarketplace\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\B2BMarketplace\Repositories\ProductVideoRepository as B2BVideoRepository;
use Webkul\B2BMarketplace\Repositories\ProductImageRepository as B2BProductImageRepository;
use Storage;

/**
 * Supplier Products Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var object
     */
    protected $attribute;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * ProductInventoryRepository object
     *
     * @var object
     */
    protected $productInventoryRepository;

    /**
     * Supplier ProductRepository object
     *
     * @var object
     */
    protected $SupplierRepository;

    /**
     * ProductAttributeValueRepository object
     *
     * @var object
     */
    protected $attributeValue;

    /**
     * ProductRepository object
     *
     * @var object
     */
    protected $product;

    /**
     * ProductFlatRepository object
     *
     * @var object
     */
    protected $productInventory;

    /**
     * ProductImageRepository object
     *
     * @var object
     */
    protected $productImage;

    /**
     * B2BProductImageRepository object
     *
     * @var object
     */
    protected $b2bProductImageRepository;

    /**
     * ProductVideoRepository instance
     *
     * @var \Webkul\Product\Repositories\productVideoRepository
     */
    protected $productVideoRepository;

    /**
     * ProductVideoRepository instance
     *
     * @var object
     */
    protected $b2BVideoRepository;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository      $attribute
     * @param  Webkul\Product\Repositories\ProductRepository          $productRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository $productInventoryRepository
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplierRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository $productInventory
     * @param  Webkul\Product\Repositories\ProductImageRepository     $productImage
     * @param  Illuminate\Container\Container                         $app
     * @param \Webkul\Product\Repositories\ProductVideoRepository     $productVideoRepository
     * @return void
     */

    public function __construct(
        AttributeRepository $attribute,
        BaseProductRepository $productRepository,
        ProductInventoryRepository $productInventoryRepository,
        ProductAttributeValueRepository $attributeValue,
        ProductInventoryRepository $productInventory,
        ProductImageRepository $productImage,
        SupplierRepository $supplierRepository,
        Product $product,
        App $app,
        B2BProductImageRepository $b2bProductImageRepository,
        ProductVideoRepository $productVideoRepository,
        B2BVideoRepository $b2BVideoRepository
    )
    {
        $this->attribute = $attribute;

        $this->productRepository = $productRepository;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->supplierRepository = $supplierRepository;

        $this->product = $product;

        $this->attributeValue = $attributeValue;

        $this->productInventory = $productInventory;

        $this->productImage = $productImage;

        $this->b2bProductImageRepository = $b2bProductImageRepository;

        $this->productVideoRepository = $productVideoRepository;

        $this->b2BVideoRepository = $b2BVideoRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\B2BMarketplace\Contracts\Product';
    }

    /**
     * Create supplier product
     *
     * @return mixed
     */
    public function create(array $data)
    {
        Event::dispatch('b2b_marketplace.catalog.product.create.before');

        $supplierId = auth()->guard('supplier')->user()->id;

        $supplierProduct = parent::create(array_merge($data, [
                'supplier_id' => $supplierId,
                'is_approved' => core()->getConfigData('b2b_marketplace.settings.general.product_approval_required') ? 0 : 1
            ]));

        foreach ($supplierProduct->product->variants as $baseVariant) {
            parent::create([
                    'parent_id' => $supplierProduct->id,
                    'product_id' => $baseVariant->id,
                    'is_owner' => 1,
                    'supplier_id' => $supplierId,
                    'is_approved' => core()->getConfigData('b2b_marketplace.settings.general.product_approval_required') ? 0 : 1
                ]);
        }

        Event::dispatch('b2b_marketplace.catalog.product.create.after', $supplierProduct);

        return $supplierProduct;
    }

    /**
     * Create Base product
     *
     * @return array
     * @param int $data
     */
    public function createBaseProduct(array $data)
    {
        //before store of the product
        Event::dispatch('catalog.product.create.before');

        $product= ProductModel::create($data);

        $nameAttribute = $this->attribute->findOneByField('code', 'status');
        $this->attributeValue->create([
                'product_id' => $product->id,
                'attribute_id' => $nameAttribute->id,
                'value' => 1
            ]);

        if (isset($data['super_attributes'])) {

            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attribute->findOneByField('code', $attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id);
            }

            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }

        //after store of the supplier product
        Event::dispatch('b2bmarketplace.supplier.catalog.product.create.after', $product);

        return $product;
    }

    /**
     * Create Quote product
     *
     * @return mixed
     */
    public function createQuoteProduct($data) {
        Event::dispatch('b2b_marketplace.catalog.qupteProduct.create.before');

        $supplierProduct = parent::create(array_merge($data, [
            'supplier_id' => $data['supplier_id'],
            'is_approved' => 1
        ]));

        foreach ($supplierProduct->product->variants as $baseVariant) {
            parent::create([
                'parent_id' => $supplierProduct->id,
                'product_id' => $baseVariant->id,
                'is_owner' => 1,
                'supplier_id' => $data['supplier_id'],
                'is_approved' => 1
            ]);
        }

        Event::dispatch('b2b_marketplace.catalog.qupteProduct.create.after', $supplierProduct);

        return $supplierProduct;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('b2b_marketplace.catalog.product.update.before', $id);

        $supplierProduct = $this->find($id);

        parent::update($data, $id);

        foreach ($supplierProduct->product->variants as $baseVariant) {

            if (! $supplierChildProduct = $this->getMarketplaceProductByProduct($baseVariant->id)) {
                parent::create([
                    'parent_id' => $supplierProduct->id,
                    'product_id' => $baseVariant->id,
                    'is_owner' => 1,
                    'supplier_id' => $supplierProduct->supplier_id,
                    'is_approved' => $supplierProduct->is_approved
                ]);
            } else {
                if (isset($data['variants'])) {
                    foreach($data['variants'] as $variantId=> $variant) {
                        if($variantId == $supplierChildProduct->product_id) {
                            $supplierChildProduct->update(['price' => $variant['price']]);
                        }
                    }
                }

            }
        }

        Event::dispatch('b2b_marketplace.catalog.product.update.after', $supplierProduct);

        return $supplierProduct;
    }

    /**
     * @param array $data
     * @param int $id
     * @param string $attribute
     * @return mixed
     */
    public function updateSupplierProduct(array $data, $id, $attribute = "id")
    {
        Event::dispatch('b2b_marketplace.upplier.scatalog.product.update.before', $id);

        Event::dispatch('catalog.product.update.before', $id);

        $data['locale'] = app()->getLocale();

        $product = $this->product->find($id);

        if ($product->parent_id && $this->product->checkVariantOptionAvailabiliy($data, $product)) {
            $data['parent_id'] = NULL;
        }

        $product->update($data);


        $attributes = $product->attribute_family->custom_attributes;

        foreach ($attributes as $attribute) {
            if (! isset($data[$attribute->code]) || (in_array($attribute->type, ['date', 'datetime']) && ! $data[$attribute->code]))
                continue;

            if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                $data[$attribute->code] = implode(",", $data[$attribute->code]);
            }

            if ($attribute->type == 'image' || $attribute->type == 'file') {
                $dir = 'product';
                if (gettype($data[$attribute->code]) == 'object') {
                    $data[$attribute->code] = request()->file($attribute->code)->store($dir);
                } else {
                    $data[$attribute->code] = NULL;
                }
            }

            $attributeValue = $this->attributeValue->findOneWhere([
                'product_id' => $product->id,
                'attribute_id' => $attribute->id,
                'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                'locale' => $attribute->value_per_locale ? $data['locale'] : null
            ]);

            if (! $attributeValue) {
                $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];
                $this->attributeValue->create([
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    $columnName    => $data[$attribute->code],
                    'channel'      => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale'       => $attribute->value_per_locale ? $data['locale'] : null
                ]);
            } else {
                $this->attributeValue->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                    ], $attributeValue->id
                );

                if ($attribute->type == 'image' || $attribute->type == 'file') {
                    Storage::delete($attributeValue->text_value);
                }
            }
        }

        $route = request()->route() ? request()->route()->getName() : "";

        if ($route != 'admin.catalog.products.massupdate') {
            if  (isset($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (isset($data['up_sell'])) {
                $product->up_sells()->sync($data['up_sell']);
            } else {
                $data['up_sell'] = [];
                $product->up_sells()->sync($data['up_sell']);
            }

            if (isset($data['cross_sell'])) {
                $product->cross_sells()->sync($data['cross_sell']);
            } else {
                $data['cross_sell'] = [];
                $product->cross_sells()->sync($data['cross_sell']);
            }

            if (isset($data['related_products'])) {
                $product->related_products()->sync($data['related_products']);
            } else {
                $data['related_products'] = [];
                $product->related_products()->sync($data['related_products']);
            }

            $previousVariantIds = $product->variants->pluck('id');

            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variantId => $variantData) {
                    if (str_contains($variantId, 'variant_')) {
                        $permutation = [];
                        foreach ($product->super_attributes as $superAttribute) {
                            $permutation[$superAttribute->id] = $variantData[$superAttribute->code];
                        }

                        $this->createVariant($product, $permutation, $variantData);
                    } else {
                        if (is_numeric($index = $previousVariantIds->search($variantId))) {
                            $previousVariantIds->forget($index);
                        }

                        $variantData['channel'] = $data['channel'];
                        $variantData['locale'] = $data['locale'];

                        $this->updateVariant($variantData, $variantId);
                    }
                }
            }

            foreach ($previousVariantIds as $variantId) {
                $this->product->delete($variantId);
            }

            $this->productInventory->saveInventories($data, $product);

            $this->productImage->uploadImages($data, $product);

            $this->productVideoRepository->uploadVideos($data, $product);

            app(ProductCustomerGroupPriceRepository::class)->saveCustomerGroupPrices($data,
                $product);
        }

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        Event::dispatch('catalog.product.update.after', $product);

        Event::dispatch('b2bmarketplace.supplier.catalog.product.update.after', $product);

        return $product;
    }

    /**
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array                              $permutation
     * @param  array                              $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $permutation, $data = [])
    {
        if (! count($data)) {
            $data = [
                'sku'         => $product->sku . '-variant-' . implode('-', $permutation),
                'name'        => '',
                'inventories' => [],
                'price'       => 0,
                'weight'      => 0,
                'status'      => 1,
            ];
        }

        $typeOfVariants = 'simple';
        $productInstance = app(config('product_types.' . $product->type . '.class'));

        if (isset($productInstance->variantsType) && ! in_array($productInstance->variantsType , ['bundle', 'configurable', 'grouped'])) {
            $typeOfVariants = $productInstance->variantsType;
        }

        $variant = $this->productRepository->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => $typeOfVariants,
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $data['sku'],
        ]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attribute->findOneByField('code', $attributeCode);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValue->create([
                                'product_id'   => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                                'value'        => $data[$attributeCode],
                            ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValue->create([
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => $channel->code,
                            'value'        => $data[$attributeCode],
                        ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValue->create([
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'locale'       => $locale->code,
                            'value'        => $data[$attributeCode],
                        ]);
                    }
                } else {
                    $this->attributeValue->create([
                        'product_id'   => $variant->id,
                        'attribute_id' => $attribute->id,
                        'value'        => $data[$attributeCode],
                    ]);
                }
            }
        }

        foreach ($permutation as $attributeId => $optionId) {
            $this->attributeValue->create([
                'product_id'   => $variant->id,
                'attribute_id' => $attributeId,
                'value'        => $optionId,
            ]);
        }

        $this->productInventory->saveInventories($data, $variant);

        return $variant;
    }

    /**
    * Update Varient Product
    *
    * @param array $data
    * @param $id
    * @return mixed
    */
    public function updateVariant(array $data, $id)
    {
        $variant = $this->product->find($id);

        $variant->update(['sku' => $data['sku']]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attribute->findOneByField('code', $attributeCode);

            $attributeValue = $this->attributeValue->findOneWhere([
                'product_id' => $id,
                'attribute_id' => $attribute->id,
                'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                'locale' => $attribute->value_per_locale ? $data['locale'] : null
            ]);

            if (! $attributeValue) {
                $this->attributeValue->create([
                    'product_id' => $id,
                    'attribute_id' => $attribute->id,
                    'value' => $data[$attribute->code],
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);
            } else {
                $this->attributeValue->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                ], $attributeValue->id);
            }
        }

        $this->productInventory->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProducts($term)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($term) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                    ->addSelect('product_flat.*')
                    ->leftJoin('products', 'product_flat.product_id', 'products.id')
                    ->where('product_flat.status', 1)
                    ->where('products.type','<>','virtual')
                    ->where('products.type','<>','grouped')
                    ->where('products.type','<>','bundle')
                    ->where('products.type','<>','downloadable')
                    ->where('products.type','<>','booking')
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->where('product_flat.visible_individually', '!=', 0)
                    ->where('product_flat.visible_individually', '!=', null)
                    ->whereNotNull('product_flat.url_key')
                    ->where('product_flat.name', 'like', '%' . $term . '%')
                    ->orderBy('product_id', 'desc');
            })->paginate(16);

        return $results;
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchSupplierProducts($term, $supplierId)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($term, $supplierId) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                    ->addSelect('product_flat.*')
                    ->leftJoin('products', 'products.id', '=', 'product_flat.product_id')
                    ->leftJoin('b2b_marketplace_products', 'products.id', '=', 'b2b_marketplace_products.product_id')
                    ->where('product_flat.status', 1)
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->where('product_flat.visible_individually', '!=', 0)
                    ->where('product_flat.visible_individually', '!=', null)
                    ->whereNotNull('product_flat.url_key')
                    ->where('product_flat.name', 'like', '%' . $term . '%')
                    ->where('b2b_marketplace_products.supplier_id', $supplierId)
                    ->where('b2b_marketplace_products.is_owner', 1)
                    ->orderBy('product_id', 'desc');
            })->paginate(16);

        return $results;
    }

    /**
     * Returns supplier by product
     *
     * @param integer $productId
     *
     */
    public function getSupplierByProductId($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
                'is_owner' => 1
            ]);

        if (! $product) {
            return;
        }

        return $product->supplier;
    }

    /**
     * Returns supplier by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getSupplierByAssignProductId($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
                'is_owner' => 0
            ]);

        if (! $product) {
            return;
        }

        return $product->supplier;
    }

    /**
     * Returns supplier by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getApprovedProduct($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
                'is_owner' => 1,
                'is_approved' => 1
            ]);

        if (! $product) {
            return false;
        }

        return $product;
    }

    /**
     * Returns supplier by product
     *
     * @param integer $productId
     * @return boolean
     */
    public function getApprovedAssignProduct($productId)
    {
        $product = parent::findOneWhere([
                'product_id' => $productId,
                'is_owner' => 0,
                'is_approved' => 1
            ]);

        if (! $product) {
            return false;
        }

        return $product;
    }

    /**
     * Returns the supplier products of the product id
     *
     * @param integer $productId
     * @param integer $suppllierId
     * @return Collection
     */
    public function getMarketplaceProductByProduct($productId, $supplierId = null)
    {
        if (! $supplierId) {
            if (auth()->guard('supplier')->check()) {
                $supplierId = auth()->guard('supplier')->user()->id;
            } else {
                return;
            }
        }

        return $this->findOneWhere([
                'product_id' => $productId,
                'supplier_id' => $supplierId,
            ]);
    }

    /**
     * Returns the total products of the seller
     *
     * @param Supplier $supplier
     * @return integer
     */
    public function getTotalProducts($supplier)
    {
        return $supplier->products()->where('is_approved', 1)->where('parent_id', NULL)->count();
    }

     /**
     * @param integer $sellerId
     * @return Collection
     */
    public function getPopularProducts($supplierId, $pageTotal = 4)
    {
        return app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($supplierId) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = $query->distinct()
                    ->addSelect('product_flat.*')
                    ->leftJoin('b2b_marketplace_products', 'product_flat.product_id', '=', 'b2b_marketplace_products.product_id')
                    ->where('product_flat.visible_individually', 1)
                    ->where('product_flat.status', 1)
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key')
                    ->where('b2b_marketplace_products.supplier_id', $supplierId)
                    ->where('b2b_marketplace_products.is_approved', 1)
                    ->whereIn('product_flat.product_id', $this->getTopSellingProducts($supplierId))
                    ->orderBy('id', 'desc');

                return $qb;
            })->paginate($pageTotal);
    }

    /**
     * Returns the all products of the supplier
     *
     * @param integer $supplier
     * @return Collection
     */
    public function findAllBySupplier($supplier)
    {
        $params = request()->input();

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($supplier, $params) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->addSelect(DB::raw('IF( product_flat.special_price_from IS NOT NULL
                            AND product_flat.special_price_to IS NOT NULL , IF( NOW( ) >= product_flat.special_price_from
                            AND NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , IF( product_flat.special_price_from IS NULL , IF( product_flat.special_price_to IS NULL , IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , IF( NOW( ) <= product_flat.special_price_to, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) ) , IF( product_flat.special_price_to IS NULL , IF( NOW( ) >= product_flat.special_price_from, IF( product_flat.special_price IS NULL OR product_flat.special_price = 0 , product_flat.price, LEAST( product_flat.special_price, product_flat.price ) ) , product_flat.price ) , product_flat.price ) ) ) AS price1'))
                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('b2b_marketplace_products', 'product_flat.product_id', '=', 'b2b_marketplace_products.product_id')
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.status', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key')
                        ->where('b2b_marketplace_products.supplier_id', $supplier->id)
                        ->where('b2b_marketplace_products.is_approved', 1);

                        $qb->addSelect(DB::raw('(CASE WHEN b2b_marketplace_products.is_owner = 0 THEN b2b_marketplace_products.price ELSE product_flat.price END) AS price2'));

                $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                    $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                        ->where('flat_variants.channel', $channel)
                        ->where('flat_variants.locale', $locale);
                });

                if (isset($params['sort'])) {
                    $attribute = $this->attribute->findOneByField('code', $params['sort']);

                    if ($params['sort'] == 'price') {
                        $qb->orderBy($attribute->code, $params['order']);
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }

                $qb = $qb->where(function($query1) {
                    foreach (['product_flat', 'flat_variants'] as $alias) {
                        $query1 = $query1->orWhere(function($query2) use($alias) {
                            $attributes = $this->attribute->getProductDefaultAttributes(array_keys(request()->input()));

                            foreach ($attributes as $attribute) {
                                $column = $alias . '.' . $attribute->code;

                                $queryParams = explode(',', request()->get($attribute->code));

                                if ($attribute->type != 'price') {
                                    $query2 = $query2->where(function($query3) use($column, $queryParams) {
                                        foreach ($queryParams as $filterValue) {
                                            $query3 = $query3->orWhere($column, $filterValue);
                                        }
                                    });
                                } else {
                                    $query2 = $query2->where($column, '>=', core()      ->convertToBasePrice(current($queryParams)))->where($column, '<=',  core()->convertToBasePrice(end($queryParams)));
                                }
                            }
                        });
                    }
                });

                return $qb->groupBy('product_flat.id');
            })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }

    /**
     * Returns top selling products
     *
     * @param integer $sellerId
     * @return mixed
     */
    public function getTopSellingProducts($sellerId)
    {
        $seller = $this->supplierRepository->find($sellerId);

        $result = app('Webkul\B2BMarketplace\Repositories\OrderItemRepository')->getModel()
            ->leftJoin('b2b_marketplace_products', 'b2b_marketplace_order_items.b2b_marketplace_product_id', 'b2b_marketplace_products.id')
            ->leftJoin('order_items', 'b2b_marketplace_order_items.b2b_marketplace_order_id', 'order_items.id')
            ->leftJoin('b2b_marketplace_orders', 'b2b_marketplace_order_items.b2b_marketplace_order_id', 'b2b_marketplace_orders.id')
            ->select(DB::raw('SUM(qty_ordered) as total_qty_ordered'), 'b2b_marketplace_products.product_id')
            ->where('b2b_marketplace_orders.supplier_id', $seller->id)
            ->where('b2b_marketplace_products.is_approved', 1)
            ->whereNull('order_items.parent_id')
            ->groupBy('b2b_marketplace_products.product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(4)
            ->get();

        return $result->pluck('product_id')->toArray();
    }

    /**
     * Returns the product type
     *
     * @param integer $productId
     * @return boolean
     */
    public function getProductType($productId)
    {
        $is_config = $this->product->FindOneWhere(['id' => $productId, 'type' => 'configurable']);

        return $is_config ? true : false;
    }

    /**
     * @return mixed
     */
    public function createAssign(array $data)
    {

        Event::dispatch('b2b_marketplace.catalog.assign-product.create.before');

        if (isset($data['supplier_id']) && auth()->guard('admin')->user()) {

            $supplier = $this->supplierRepository->findOneByField('id', $data['supplier_id']);
            unset($data['supplier_id']);

        } else if (auth()->guard('supplier')->user()) {

            $supplier = auth()->guard('supplier')->user();
        }

        $supplierProduct = parent::create(array_merge($data, [
                'supplier_id' => $supplier->id,
                'is_approved' => core()->getConfigData('b2b_marketplace.settings.general.product_approval_required') ? 0 : 1
            ]));

        if (isset($data['selected_variants'])) {

            foreach ($data['selected_variants'] as $baseVariantId) {
                $supplierChildProduct = parent::create(array_merge($data['variants'][$baseVariantId], [
                        'parent_id' => $supplierProduct->id,
                        'condition' => $supplierProduct->condition,
                        'product_id' => $baseVariantId,
                        'is_owner' => 0,
                        'supplier_id' => $supplier->id,
                        'is_approved' => core()->getConfigData('b2b_marketplace.settings.general.product_approval_required') ? 0 : 1
                    ]));

                $this->productInventory->saveInventories(array_merge($data['variants'][$baseVariantId], [
                        'vendor_id' => $supplierChildProduct->supplier_id
                    ]), $supplierChildProduct->product);
            }
        }

        $this->productInventory->saveInventories(array_merge($data, [
                'vendor_id' => $supplierProduct->supplier_id,
            ]), $supplierProduct->product);

        $this->b2bProductImageRepository->uploadImages($data, $supplierProduct);

        $this->b2BVideoRepository->uploadVideos($data, $supplierProduct);

        Event::dispatch('b2b_marketplace.catalog.assign-product.create.after', $supplierProduct);

        return $supplierProduct;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function updateAssign(array $data, $id, $attribute = "id")
    {
        Event::dispatch('b2b_marketplace.catalog.assign-product.update.before', $id);

        $supplierProduct = $this->find($id);

        parent::update($data, $id);

        $previousBaseVariantIds = $supplierProduct->variants->pluck('product_id');

        if (isset($data['selected_variants'])) {
            foreach ($data['selected_variants'] as $baseVariantId) {
                $variantData = $data['variants'][$baseVariantId];

                if (is_numeric($index = $previousBaseVariantIds->search($baseVariantId))) {
                    $previousBaseVariantIds->forget($index);
                }

                $supplierChildProduct = $this->findOneWhere([
                        'product_id' => $baseVariantId,
                        'supplier_id' => $supplierProduct->supplier_id,
                        'is_owner' => 0
                    ]);

                if ($supplierChildProduct) {
                    parent::update(array_merge($variantData, [
                            'price' => $variantData['price'],
                            'condition' => $supplierProduct->condition
                        ]), $supplierChildProduct->id);

                    $this->productInventory->saveInventories(array_merge($variantData, [
                            'vendor_id' => $supplierChildProduct->supplier_id
                        ]), $supplierChildProduct->product);

                } else {
                    $supplierChildProduct = parent::create(array_merge($variantData, [
                            'parent_id' => $supplierProduct->id,
                            'product_id' => $baseVariantId,
                            'condition' => $supplierProduct->condition,
                            'is_approved' => $supplierProduct->id_approved,
                            'is_owner' => 0,
                            'supplier_id' => $supplierProduct->supplier->id,
                        ]));

                    $this->productInventory->saveInventories(array_merge($variantData, [
                            'vendor_id' => $supplierChildProduct->supplier_id
                        ]), $supplierChildProduct->product);
                }
            }
        }

        if ($previousBaseVariantIds->count()) {
            $supplierProduct->variants()
                ->whereIn('product_id', $previousVariantIds)
                ->delete();
        }

        $this->b2bProductImageRepository->uploadImages($data, $supplierProduct);

        $this->productInventory->saveInventories(array_merge($data, [
                'vendor_id' => $supplierProduct->supplier_id
            ]), $supplierProduct->product);

        $this->b2BVideoRepository->uploadVideos($data, $supplierProduct);

        Event::dispatch('b2b_marketplace.catalog.assign-product.update.after', $supplierProduct);

        return $supplierProduct;
    }

    /**
     * Returns count of seller that selling the same product
     *
     * @param Product $product
     * @return integer
     */
    public function getSupplierCount($product)
    {
        return $this->scopeQuery(function($query) use($product) {
                return $query
                        ->where('b2b_marketplace_products.product_id', $product->id)
                        ->where('b2b_marketplace_products.is_owner', 0)
                        ->where('b2b_marketplace_products.is_approved', 1);
            })->count();
    }

    /**
     * Returns the Supplier products of the product
     *
     * @param Product $product
     * @return Collection
     */
    public function getSupplierProducts($product)
    {
        return $this->findWhere([
                'product_id' => $product->id,
                'is_owner' => 0,
                'is_approved' => 1
            ]);
    }

    /**
     * Search Product by Attribute
     *
     * @param string $term
     *
     * @return \Illuminate\Support\Collection
     */
    public function searchProductByAttribute($term)
    {
        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function ($query) use ($term, $channel, $locale) {

            $query = $query->distinct()
                ->addSelect('product_flat.*')
                ->join('product_flat as variants', 'product_flat.id', '=', DB::raw('COALESCE(' . DB::getTablePrefix() . 'variants.parent_id, ' . DB::getTablePrefix() . 'variants.id)'))
                ->leftJoin('products', 'products.id', '=', 'product_flat.product_id')
                ->leftJoin('b2b_marketplace_products', 'products.id', '=', 'b2b_marketplace_products.product_id')
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereIn('products.type', ['configurable','simple'])
                ->whereNotNull('product_flat.url_key');

            if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                $query = $this->checkOutOfStockItem($query);
            }

            return $query->where('product_flat.status', 1)
                ->where(function ($subQuery) use ($term) {
                    $queries = explode('_', $term);

                    foreach (array_map('trim', $queries) as $value) {
                        $subQuery->orWhere('product_flat.name', 'like', '%' . urldecode($value) . '%');
                    }
                })

                ->orderBy('product_id', 'desc');
        })->paginate(16);

        return $results;
    }
}