<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var object
     */
    protected $_config;

    /**
     * Supplier Repository object
     *
     * @var object
    */
    protected $supplier;

    /**
     * ProductRepository object
     *
     * @var object
    */
    protected $product;

    /**
     * ProductRepository object
     *
     * @var object
    */
    protected $baseProduct;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplier
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository      $product
     * @param  Webkul\Product\Repositories\ProductRepository             $baseProduct
     * @return void
     */
    public function __construct(
        SupplierRepository $supplier,
        ProductRepository $product,
        BaseProductRepository $baseProduct
    )
    {
        $this->_config = request('_config');

        $this->supplier = $supplier;

        $this->product = $product;

        $this->baseProduct = $baseProduct;
    }

    /**
     * Method to populate the supplier product page which will be populated.
     *
     * @param  string  $url
     * @return Mixed
     */
    public function index($url)
    {
        $supplier = $this->supplier->findByUrlOrFail($url);

        return view($this->_config['view'], compact('supplier'));
    }

    /**
     * Product offers by suppliers
     *
     * @param  integer $id
     * @return Mixed
     */
    public function offers($id)
    {
        $product = $this->baseProduct->findOrFail($id);

        if ($product->type == 'configurable') {
            session()->flash('error', trans('shop::app.checkout.cart.integrity.missing_options'));

            return redirect()->route('shop.productOrCategory.index', ['slug' => $product->url_key]);
        }

        return view($this->_config['view'], compact('product'));
    }
}