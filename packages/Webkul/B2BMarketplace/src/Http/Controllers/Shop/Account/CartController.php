<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account;

use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuoteItem;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Cart;

/**
 * Cart controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    /*
    * Contains route related configuration
    *
    * @var array
    */
    protected $_config;

    /*
    * Supplier Repository
    *
    * @var array
    */
    protected $supplierRepository;

    /*
    * Supplier Address Repository
    *
    * @var array
    */
    protected $supplierAddress;

    /*
    * Supplier Quote Item
    *
    * @var array
    */
    protected $supplierQuoteItem;

    /*
    * Product Repository Object
    *
    * @var array
    */
    protected $product;

    /*
    * Supplier Product Repository Object
    *
    * @var array
    */
    protected $supplierProduct;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository   $supplierProduct
     * @param  Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository     $supplierAddress
     * @param  Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository     $supplierQuoteItem
     * @param  Webkul\Product\Repositories\ProductRepository $product
     * @return void
     */
    public function __construct(
       SupplierAddress $supplierAddress,
       SupplierRepository $supplierRepository,
       SupplierQuoteItem $supplierQuoteItem,
       ProductRepository $product,
       SupplierProduct $supplierProduct
    )
    {
        $this->supplierAddress = $supplierAddress;

        $this->supplierRepository = $supplierRepository;

        $this->supplierQuoteItem = $supplierQuoteItem;

        $this->product = $product;

        $this->supplierProduct = $supplierProduct;

        $this->_config = request('_config');
    }

    /**
     * Method to add  product in the cart.
     *
     * @param int $productId
     * @return void
     */
    public function add($productId)
    {
        $requestData = request()->all();
        if (Cart::getCart() != null) {
            foreach (Cart::getCart()['items'] as $items){

                if (isset($items->additional['quote_id'])
                    && $items->additional['quote_id'] == $requestData['quote_id']) {

                    $quote = $this->supplierQuoteItem->findOneWhere(['id' => $items->additional['quote_id']]);
                }
            }

            if (isset($quote) && $quote != null){
                session()->flash('error', 'Item already In the Cart.');
                return back();
            }
        }

        try {
            Event::dispatch('b2bmarketplace.catelog.supplier.quote-product.create.before');

            $data = request()->except('_token');

            $data['isQuoteProduct'] = 1;

            $productId = $data['product_id'];

            $result = Cart::addProduct($productId, $data);

            $sku = 'bulk-product-' . $requestData['quote_id'];

            $supplierBulkProduct = $this->product->findOneWhere(['sku' => $sku]);
            
            $supplierBulkProduct->update(['status' => 0]);

            Cart::collectTotals();

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                return redirect()->back();
            } else {
                session()->flash('warning', trans('shop::app.checkout.cart.item.error-add'));

                return redirect()->back();
            }

            return redirect()->route($this->_config['redirect']);

        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Method to BuyNow  product.
     *
     * @param int $id
     * @param int $quantity
     *
     * @return void
     */
    public function buyNow($id, $quantity = 1)
    {
        try {
            Event::dispatch('checkout.cart.add.before', $id);

            $result = $this->proceedToBuyNow($id, $quantity);

            Event::dispatch('checkout.cart.add.after', $result);

            Cart::collectTotals();

            if (! $result) {
                return redirect()->back();
            } else {
                return redirect()->route('shop.checkout.onepage.index');
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Proceed to BuyNow  product.
     *
     * @param int $id
     * @param int $quantity
     * @return void
     */
    public function proceedToBuyNow($id, $quantity)
    {
        $product = $this->product->findOneByField('id', $id);

        if ($product->type == 'configurable') {
            session()->flash('warning', trans('shop::app.buynow.no-options'));

            return false;
        } else {
            $simpleOrVariant = $this->product->find($id);

            if ($simpleOrVariant->parent_id != null) {
                $parent = $simpleOrVariant->parent;

                $data['product'] = $parent->id;
                $data['selected_configurable_option'] = $simpleOrVariant->id;
                $data['quantity'] = $quantity;
                $data['super_attribute'] = 'From Buy Now';

                $result = Cart::add($parent->id, $data);

                return $result;
            } else {
                $data['product'] = $id;
                $data['is_configurable'] = false;
                $data['quantity'] = $quantity;

                $supplierProduct = $this->supplierProduct->findOneWhere(['product_id' => $id]);

                if (isset($supplierProduct)) {
                    $data['supplier_info'] = [
                        'product_id' => $supplierProduct->id,
                        'is_owner' => $supplierProduct->is_owner,
                        'supplier_id' => $supplierProduct->supplier_id
                    ];
                }

                $result = Cart::add($id, $data);

                return $result;
            }
        }
    }
}