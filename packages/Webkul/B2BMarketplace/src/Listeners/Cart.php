<?php

namespace Webkul\B2BMarketplace\Listeners;

use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository as BaseProduct;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuoteItem;
use Cart as CartFacade;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * Cart event handler
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart
{
    /**
     * SupplierRepository object
     *
     * @var Seller
    */
    protected $supplierRepository;

    /**
     * ProductRepository object
     *
     * @var Product
    */
    protected $productRepository;

    /**
     * Base ProductFlatRepository object
     *
     * @var Product
    */
    protected $baseProduct;

    /**
     * SupplierQuoteItem Repository object
     *
     * @var Product
    */
    protected $supplierQuoteItem;

    /**
     * core product Repository object
     *
     * @var Product
    */
    protected $coreProduct;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository          $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\ProductRepository           $productRepository
     * @param  Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository $supplierQuoteItem
     * @param  Webkul\Product\Repositories\ProductFlatRepository              $baseProduct
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        ProductRepository $productRepository,
        SupplierQuoteItem $supplierQuoteItem,
        BaseProduct $baseProduct,
        Product $coreProduct
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->productRepository = $productRepository;

        $this->supplierQuoteItem = $supplierQuoteItem;

        $this->baseProduct = $baseProduct;

        $this->coreProduct = $coreProduct;
    }

    /**
     * Product added to the cart
     *
     * @param int $productId
     *
     * @return void
     */
    public function cartItemAddBefore($productId)
    {
        $requestData = request()->all();

        if (! isset($requestData['quote_id'])
            && ( ! isset($requestData['selected_configurable_option'])
            || ! $requestData['selected_configurable_option'] )
            ) {

            return trans('shop::app.checkout.cart.integrity.missing_options');
        }

        //data after event dispatch
        $data = request()->all();

        if ($data != null)
            $productId = $data['product_id'];


        if (isset($data['supplier_info']) && !$data['supplier_info']['is_owner']) {
            $supplierProduct = $this->productRepository->find($data['supplier_info']['product_id']);
        } else if (isset($data['supplier_info']) && !$data['supplier_info']['is_owner']) {

            $supplierProduct = $this->productRepository->find($data['supplier_info']['product_id']);
        } else {
            if (isset($data['selected_configurable_option'])) {

                $supplierProduct = $this->productRepository->findOneWhere([
                    'product_id' => $data['selected_configurable_option'],
                    'is_owner' => 1
                ]);
            } else {
                $supplierProduct = $this->productRepository->findOneWhere([
                    'product_id' => $productId,
                    'is_owner' => 1
                ]);
            }
        }

        if (!$supplierProduct) {
            return;
        }

        if (! isset($data['quantity']))
            $data['quantity'] = 1;

        if ($cart = CartFacade::getCart()) {

            $cartItem = $cart->items()->where('product_id', $supplierProduct->product_id)->first();

            if ($cartItem) {
                if (!$supplierProduct->haveSufficientQuantity($data['quantity']))
                    throw new \Exception('Requested quantity not available.');

                $quantity = $cartItem->quantity + $data['quantity'];
            } else {
                $quantity = $data['quantity'];
            }
        } else {
            $quantity = $data['quantity'];
        }

        if (! core()->getConfigData('catalog.inventory.stock_options.backorders')) {
            if ( !$supplierProduct->haveSufficientQuantity($quantity)) {
                throw new \Exception('Requested quantity not available.');
            }
        }

        Event::dispatch('b2bmarketplace.catelog.supplier.quote-product.create.after');
    }

    /**
     * Product added to the cart
     *
     * @param mixed $cartItem
     *
     * @return Mixed
     */
    public function cartItemAddAfter($cartItem)
    {
        $data = request()->all();

        if (isset($data['quote_id'])) {
            foreach(CartFacade::getCart()->items as $item) {

                if (isset($item->additional['supplier_id'])) {

                    $quoteItem = $this->supplierQuoteItem->findOneWhere(['id' => $item->additional['quote_id']]);

                    if (isset($quoteItem)) {
                        $item->quantity = $quoteItem->quantity;
                        $item->price = core()->convertPrice($quoteItem->price_per_quantity);
                        $item->base_price = $quoteItem->price_per_quantity;
                        $item->custom_price = $quoteItem->price_per_quantity;
                        $item->total = core()->convertPrice($quoteItem->price_per_quantity * $quoteItem->quantity);
                        $item->base_total = $quoteItem->price_per_quantity * $quoteItem->quantity;

                        $item->save();
                    }

                    $item->save();
                } else {
                    $item->save();
                }
            }

        } elseif (isset($data['supplier_info']) && !$data['supplier_info']['is_owner']) {

            foreach(CartFacade::getCart()->items as $items) {
                if (isset($items->additional['supplier_info']) && !$items->additional['supplier_info']['is_owner']) {
                    $product = $this->productRepository->find($items->additional['supplier_info']['product_id']);

                    if ($product) {
                        $items->price = core()->convertPrice($product->price);
                        $items->base_price = $product->price;
                        $items->custom_price = $product->price;
                        $items->total = core()->convertPrice($product->price * $items->quantity);
                        $items->base_total = $product->price * $items->quantity;

                        $items->save();
                    }
                    $items->save();
                } else {
                    $items->save();
                }
            }
        } else {
            $cartItem->save();
        }
    }
}
