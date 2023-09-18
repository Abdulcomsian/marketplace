<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account;

use Webkul\B2BMarketplace\Http\Controllers\Shop\Controller;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\Product\Repositories\ProductRepository as BaseProduct;
use Cart;

/**
 * Quick Order Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class QuickOrderController extends Controller
{
    /**
     * Product Flat Repository Object
     *
     * @var object
     */
    protected $productFlat;

    /**
     * Product Repository Object
     *
     * @var object
     */
    protected $product;

    /**
     * Supplier Product Repository Object
     *
     * @var object
     */
    protected $supplierProduct;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository   $product
     * @param  Webkul\Product\Repositories\ProductFlatRepository     $productFlat
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository     $supplierProduct
     * @return void
     */
    public function __construct(
        ProductFlat $productFlat,
        Product $product,
        SupplierProduct $supplierProduct
    )
    {
        $this->_config = request('_config');

        $this->productFlat = $productFlat;

        $this->product = $product;

        $this->supplierProduct = $supplierProduct;
    }

    /**
     * Product Search
     *
     * @return Illuminate\http\response
     */
    public function searchProduct()
    {
        if (request()->all()) {
            $results = [];
            $data = request()->all();

            foreach ($this->supplierProduct->searchSupplierProducts($data['params']['query'], $data['params']['supplier']) as $row) {

                $ifConfig = $this->product->findOneWhere(['id' => $row->product_id, 'type' => 'configurable']);

                if (isset($ifConfig)) {
                    $isConfig = 1;
                } else {
                    $isConfig = 0;
                }

                $results[] = [
                    'id' => $row->product_id,
                    'sku' => $row->sku,
                    'name' => $row->name,
                    'price' => core()->convertPrice($row->price),
                    'formated_price' => core()->currency(core()->convertPrice($row->price)),
                    'base_image' => $row->product->base_image_url,
                    'parent_id' => $row->product->parent_id,
                    'is_config' => $isConfig
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

    /**
     * Add To Cart Product
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        $product = $this->product->findOneWhere(['id' => $data['product']]);

        $SupplierProduct = $this->supplierProduct->findOneWhere([
            'product_id' => $product->id,
            'is_owner' => 1
        ]);

        if ($product->type == 'simple') {

            request()->merge([
                'is_configurable' => false,
                'selected_configurable_option' => '',
                'product_id' => $data['product']
            ]);

            if (!$SupplierProduct->haveSufficientQuantity($data['quantity'])) {

                throw new \Exception('Requested quantity not available.');
            }

            Cart::addProduct($data['product'], request()->all());
        }
    }

    /**
     * Get the varient of the product
     *
     * @return object
     */
    public function getConfigData()
    {
        $data = request()->all();

        $product = $this->product->findOneWhere(['id' => $data['id']]);

        $config = app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product);

        return response()->json($config);
    }

    /**
     * Variant product Add to Cart
     *
     * @return void
     */
    public function addToCart()
    {
        $data = request()->all();

        $supplierProduct = $this->supplierProduct->findOneWhere(['product_id' => $data['selected_configurable_option'], 'is_owner' => 1]);

        request()->merge([
            'is_configurable' => true,
            'product_id' => $data['product']
        ]);

        if (! core()->getConfigData('catalog.inventory.stock_options.backorders')) {
            if (!$supplierProduct->haveSufficientQuantity($data['quantity'])) {
              throw new \Exception('Requested quantity not available.');
            }
        }

        Cart::addProduct($data['product'], request()->all());

        return response('true');
    }
}