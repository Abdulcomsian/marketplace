<?php

namespace Webkul\B2BMarketplace\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('b2b_marketplace::admin.layouts.style');
        });

        try {
            if (core()->getConfigData('b2b_marketplace.settings.general.status')) {

                Event::listen('bagisto.shop.products.b2b.price.after', function($viewRenderEventManager) {
                    $viewRenderEventManager->addTemplate('b2b_marketplace::shop.products.product-suppliers');
                });

                Event::listen(['bagisto.shop.checkout.cart.item.name.after', 'bagisto.shop.checkout.cart-mini.item.name.after', 'bagisto.shop.checkout.name.after'], function($viewRenderEventManager) {
                    $viewRenderEventManager->addTemplate('b2b_marketplace::shop.checkout.cart.item-seller-info');
                });

                try {

                    if (isset(core()->getCurrentChannel()->theme) && core()->getCurrentChannel()->theme == "velocity") {

                        Event::listen('bagisto.shop.layout.head', function($viewRenderEventManager) {
                            $viewRenderEventManager->addTemplate('b2b_marketplace::shop.velocity.layouts.style');
                        });


                    } else {
                        Event::listen('bagisto.shop.layout.header.currency-item.before', function($viewRenderEventManager) {
                            $viewRenderEventManager->addTemplate('b2b_marketplace::shop.layouts.header.index');
                        });
                    }

                } catch(Exception $e) {
                    return $data = null;
                }

                Event::listen('b2b_marketplace.supplier.catalog.products.edit_form_accordian.video.before', function($viewRenderEventManager) {
                    $viewRenderEventManager->addTemplate('b2b_marketplace::supplier.catalog.products.accordians.product-links');
                });

                Event::listen('bagisto.shop.products.view.short_description.after', function($viewRenderEventManager) {
                    $viewRenderEventManager->addTemplate('b2b_marketplace::shop.products.supplier-product-info');
                });

                // if ( (core()->getConfigData('b2b_marketplace.settings.product_flag.status'))) {

                    Event::listen('bagisto.admin.catalog.product.edit_form_accordian.additional_views.after', function($viewRenderEventManager) {
                        $viewRenderEventManager->addTemplate('b2b_marketplace::admin.products.flags.index');
                    });

                    Event::listen('bagisto.shop.products.view.before', function($viewRenderEventManager) {
                        $viewRenderEventManager->addTemplate('b2b_marketplace::shop.products.flags.product-flag');
                    });

                // }

                Event::listen('supplier.registration.after', 'Webkul\B2BMarketplace\Listeners\Supplier@registerSupplier');

                Event::listen('b2b_marketplace.supplier.delete.after','Webkul\B2BMarketplace\Listeners\Supplier@afterSupplierDelete');

                Event::listen('checkout.cart.add.before', 'Webkul\B2BMarketplace\Listeners\Cart@cartItemAddBefore');

                Event::listen('b2bmarketplace.catelog.supplier.quote-product.create.before', 'Webkul\B2BMarketplace\Listeners\Quote@createQuoteProduct');

                Event::listen('checkout.cart.add.after', 'Webkul\B2BMarketplace\Listeners\Cart@cartItemAddAfter');

                Event::listen('checkout.order.save.after', 'Webkul\B2BMarketplace\Listeners\Order@afterPlaceOrder');

                Event::listen('sales.invoice.save.after', 'Webkul\B2BMarketplace\Listeners\Invoice@afterInvoice');

                Event::listen('sales.shipment.save.after', 'Webkul\B2BMarketplace\Listeners\Shipment@afterShipment');

                Event::listen('sales.order.cancel.after', 'Webkul\B2BMarketplace\Listeners\Order@afterOrderCancel');

                Event::listen('sales.refund.save.after', 'Webkul\B2BMarketplace\Listeners\Refund@afterRefund');

                Event::listen('b2bmarketplace.supplier.catalog.product.create.before', 'Webkul\B2BMarketplace\Listeners\ProductFlat@BeforeProductCreate');

                Event::listen('b2bmarketplace.supplier.catalog.product.create.after', 'Webkul\B2BMarketplace\Listeners\ProductFlat@afterProductCreatedUpdated');

                Event::listen('b2bmarketplace.supplier.catalog.product.update.after', 'Webkul\B2BMarketplace\Listeners\ProductFlat@afterProductCreatedUpdated');

                Event::listen('catalog.product.update.after', 'Webkul\B2BMarketplace\Listeners\ProductFlat@BeforeProductCreate');

                //Send sales mails
                Event::listen('b2b_marketplace.sales.order.save.after', 'Webkul\B2BMarketplace\Listeners\Order@sendNewOrderMail');

                Event::listen('b2b_marketplace.sales.invoice.save.after', 'Webkul\B2BMarketplace\Listeners\Order@sendNewInvoiceMail');

                Event::listen('b2b_marketplace.sales.shipment.save.after', 'Webkul\B2BMarketplace\Listeners\Order@sendNewShipmentMail');
            }
        } catch(\Exception $e) {

        }
    }
}
