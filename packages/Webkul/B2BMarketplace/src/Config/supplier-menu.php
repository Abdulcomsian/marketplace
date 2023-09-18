<?php

return [
    [
        'key' => 'dashboard',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.dashboard',
        'route' => 'b2b_marketplace.supplier.dashboard.index',
        'sort' => 1,
        'icon-class' => 'dashboard-icon',
    ], [
        'key' => 'messages',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.messages',
        'route' => 'b2b_marketplace.supplier.messages.index',
        'sort' => 2,
        'icon-class' => 'message-icon',
    ],
    [
        'key' => 'products',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.products',
        'route' => 'b2b_marketplace.supplier.catalog.products.search',
        'sort' => 3,
        'icon-class' => 'product-icon',
    ], [
        'key' => 'products.add',
        'name' => 'b2b_marketplace::app.shop.supplier.account.products.add-product',
        'route' => 'b2b_marketplace.supplier.catalog.products.search',
        'sort' => 1,
    ], [
        'key' => 'products.show',
        'name' => 'b2b_marketplace::app.shop.supplier.account.products.product-list',
        'route' => 'b2b_marketplace.supplier.catalog.products.index',
        'sort' => 2,
    ], [
        'key' => 'sales',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.orders',
        'route' => 'b2b_marketplace.supplier.sales.orders.index',
        'sort' => 4,
        'icon-class' => 'order-icon',
    ], [
        'key' => 'sales.orders',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.orders',
        'route' => 'b2b_marketplace.supplier.sales.orders.index',
        'sort' => 1,
        'icon-class' => 'order-icon',
    ], [
        'key' => 'sales.shipments',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.shipments',
        'route' => 'b2b_marketplace.supplier.sales.shipments.index',
        'sort' => 2,
    ], [
        'key' => 'sales.invoices',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.invoices',
        'route' => 'b2b_marketplace.supplier.sales.invoices.index',
        'sort' => 3,
    ], [
        'key' => 'customers',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.customers',
        'route' => 'b2b_marketplace.supplier.customers.index',
        'sort' => 5,
        'icon-class' => 'customer-icon',
    ], [
        'key' => 'customers.manage',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.customers',
        'route' => 'b2b_marketplace.supplier.customers.index',
        'sort' => 5,
        'icon-class' => 'customer-icon',
    ], [
        'key' => 'transactions',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.transactions',
        'route' => 'b2b_marketplace.suppliers.transaction.index',
        'sort' => 6,
        'icon-class' => 'transection-icon',
    ], [
        'key' => 'transactions.list',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.transactions',
        'route' => 'b2b_marketplace.suppliers.transaction.index',
        'sort' => 1,
    ], [
        'key' => 'buying-leads',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.buying-leads',
        'route' => 'b2b_marketplace.supplier.leads.index',
        'sort' => 7,
        'icon-class' => 'lead-icon',
    ], [
        'key' => 'buying-leads.lead',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.buying-leads',
        'route' => 'b2b_marketplace.supplier.leads.index',
        'sort' => 7,
        'icon-class' => 'buying-leads-icon',
    ], [
        'key' => 'request-for-quote',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.request-for-quote',
        'route' => 'b2b_marketplace.supplier.request-quote.new.index',
        'sort' => 8,
        'icon-class' => 'rfq-icon',
    ], [
        'key' => 'request-for-quote.quote',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.request-for-quote',
        'route' => 'b2b_marketplace.supplier.request-quote.new.index',
        'sort' => 1,
    ], [
        'key' => 'request-for-quote.quote.new',
        'name' => 'New',
        'route' => 'b2b_marketplace.supplier.request-quote.new.index',
        'sort' => 1,
    ], [
        'key' => 'request-for-quote.quote.pending',
        'name' => 'Pending',
        'route' => 'b2b_marketplace.supplier.request-quote.pending.index',
        'sort' => 2,
    ], [
        'key' => 'request-for-quote.quote.answered',
        'name' => 'Answered',
        'route' => 'b2b_marketplace.supplier.request-quote.answered.index',
        'sort' => 3,
    ], [
        'key' => 'request-for-quote.quote.confirmed',
        'name' => 'Confirmed',
        'route' => 'b2b_marketplace.supplier.request-quote.confirmed.index',
        'sort' => 4,
    ], [
        'key' => 'request-for-quote.quote.rejected',
        'name' => 'Rejected',
        'route' => 'b2b_marketplace.supplier.request-quote.rejected.index',
        'sort' => 5,
    ], [
        'key' => 'reviews',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.reviews',
        'route' => 'b2b_marketplace.supplier.reviews.index',
        'sort' => 9,
        'icon-class' => 'review-icon',
    ], [
        'key' => 'reviews.supplier-review',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.reviews',
        'route' => 'b2b_marketplace.supplier.reviews.index',
        'sort' => 1,
    ], [
        'key' => 'settings',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.settings',
        'route' => 'b2b_marketplace.supplier.settings.index',
        'sort' => 10,
        'icon-class' => 'settings-icon',
    ], [
        'key' => 'settings.supplier',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.supplier-profile',
        'route' => 'b2b_marketplace.supplier.settings.index',
        'sort' => 1,
    ], [
        'key' => 'settings.supplier.profile',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.company-info',
        'route' => 'b2b_marketplace.supplier.settings.index',
        'sort' => 2,
    ], [
        'key' => 'settings.categories',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.categories',
        'route' => 'b2b_marketplace.supplier.profile.category',
        'sort' => 3,
    ], [
        'key' => 'settings.categories.allow_category',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.categories',
        'route' => 'b2b_marketplace.supplier.profile.category',
        'sort' => 3,
    ], [
        'key' => 'settings.supplier.password',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.password',
        'route' => 'b2b_marketplace.supplier.profile.password',
        'sort' => 4,
    ], [
        'key' => 'settings.verification',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.verification',
        'route' => 'b2b_marketplace.supplier.verification.show',
        'sort' => 5,
    ], [
        'key' => 'settings.verification.supplier',
        'name' => 'b2b_marketplace::app.shop.supplier.layouts.verified-supplier',
        'route' => 'b2b_marketplace.supplier.verification.show',
        'sort' => 6,
    ],
];