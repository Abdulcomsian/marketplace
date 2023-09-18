<?php

namespace Webkul\B2BMarketplace\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\B2BMarketplace\Models\Supplier::class,
        \Webkul\B2BMarketplace\Models\SupplierAddresses::class,
        \Webkul\B2BMarketplace\Models\Product::class,
        \Webkul\B2BMarketplace\Models\ProductImage::class,
        \Webkul\B2BMarketplace\Models\Order::class,
        \Webkul\B2BMarketplace\Models\OrderItem::class,
        \Webkul\B2BMarketplace\Models\Invoice::class,
        \Webkul\B2BMarketplace\Models\InvoiceItem::class,
        \Webkul\B2BMarketplace\Models\Shipment::class,
        \Webkul\B2BMarketplace\Models\ShipmentItem::class,
        \Webkul\B2BMarketplace\Models\Review::class,
        \Webkul\B2BMarketplace\Models\Transaction::class,
        \Webkul\B2BMarketplace\Models\Quote::class,
        \Webkul\B2BMarketplace\Models\CustomerQuoteItem::class,
        \Webkul\B2BMarketplace\Models\SupplierQuoteItem::class,
        \Webkul\B2BMarketplace\Models\QuoteMessage::class,
        \Webkul\B2BMarketplace\Models\Category::class,
        \Webkul\B2BMarketplace\Models\Message::class,
        \Webkul\B2BMarketplace\Models\MessageMapping::class,
        \Webkul\B2BMarketplace\Models\QuoteImage::class,
        \Webkul\B2BMarketplace\Models\QuoteAttachment::class,
        \Webkul\B2BMarketplace\Models\Role::class,
        \Webkul\B2BMarketplace\Models\Refund::class,
        \Webkul\B2BMarketplace\Models\RefundItem::class,
        \Webkul\B2BMarketplace\Models\ProductVideo::class,
        \Webkul\B2BMarketplace\Models\ProductFlag::class,
        \Webkul\B2BMarketplace\Models\ProductFlagReason::class,
        \Webkul\B2BMarketplace\Models\SupplierFlag::class,
        \Webkul\B2BMarketplace\Models\SupplierFlagReason::class,
        \Webkul\B2BMarketplace\Models\AllowedSupplierCategory::class
    ];
}