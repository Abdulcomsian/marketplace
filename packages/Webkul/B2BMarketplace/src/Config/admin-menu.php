<?php

return [
    [
        'key' => 'b2b-marketplace',
        'name' => 'b2b_marketplace::app.admin.layouts.b2b-marketplace',
        'route' => 'b2b_marketplace.admin.suppliers.index',
        'sort' => 3,
        'icon-class' => 'b2b-icon',
    ], [
        'key' => 'b2b-marketplace.suppliers',
        'name' => 'b2b_marketplace::app.admin.layouts.suppliers',
        'route' => 'b2b_marketplace.admin.suppliers.index',
        'sort' => 1,
    ], [
        'key' => 'b2b-marketplace.products',
        'name' => 'b2b_marketplace::app.admin.layouts.products',
        'route' => 'b2b_marketplace.admin.products.index',
        'sort' => 2,
    ], [
        'key' => 'b2b-marketplace.orders',
        'name' => 'b2b_marketplace::app.admin.orders.title',
        'route' => 'admin.b2b_marketplace.supplier.orders.index',
        'sort' => 3,
    ], [
        'key' => 'b2b-marketplace.transactions',
        'name' => 'b2b_marketplace::app.admin.layouts.transactions',
        'route' => 'b2b_marketplace.admin.transactions.index',
        'sort' => 4,
    ], [
        'key' => 'b2b-marketplace.reviews',
        'name' => 'b2b_marketplace::app.admin.layouts.reviews',
        'route' => 'b2b_marketplace.admin.reviews.index',
        'sort' => 5,
    ], [
        'key' => 'b2b-marketplace.request-quote',
        'name' => 'b2b_marketplace::app.admin.layouts.request-quote',
        'route' => 'b2b_marketplace.admin.request-quote.index',
        'sort' => 6,
    ], [
        'key' => 'b2b-marketplace.suppliers.user',
        'name' => 'b2b_marketplace::app.admin.layouts.suppliers',
        'route' => 'b2b_marketplace.admin.suppliers.index',
        'sort' => 1,
    ], [
        'key' => 'b2b-marketplace.suppliers.roles',
        'name' => 'b2b_marketplace::app.admin.suppliers.roles',
        'route' => 'b2b_marketplace.admin.supplier.roles.index',
        'sort' => 1,
    ], [
        'key' => 'b2b-marketplace.supplier_flag',
        'name' => 'b2b_marketplace::app.admin.layouts.supplier-flag',
        'route' => 'b2b_marketplace.admin.supplier-flag.flag.index',
        'sort' => 7
    ], [
        'key' => 'b2b-marketplace.supplier_flag.flag',
        'name' => 'b2b_marketplace::app.admin.layouts.flag',
        'route' => 'b2b_marketplace.admin.supplier-flag.flag.index',
        'sort' => 7
    ], [
        'key' => 'b2b-marketplace.supplier_flag.reason',
        'name' => 'b2b_marketplace::app.admin.layouts.reason',
        'route' => 'b2b_marketplace.admin.supplier-flag.reason.index',
        'sort' => 7
    ],
    [
        'key' => 'b2b-marketplace.product_flag',
        'name' => 'b2b_marketplace::app.admin.layouts.product-flag',
        'route' => 'b2b_marketplace.admin.product-flag.flag.index',
        'sort' => 8
    ], [
        'key' => 'b2b-marketplace.product_flag.flag',
        'name' => 'b2b_marketplace::app.admin.layouts.flag',
        'route' => 'b2b_marketplace.admin.product-flag.flag.index',
        'sort' => 8
    ], [
        'key' => 'b2b-marketplace.product_flag.reason',
        'name' => 'b2b_marketplace::app.admin.layouts.reason',
        'route' => 'b2b_marketplace.admin.product-flag.reason.index',
        'sort' => 8
    ], [
        'key' => 'b2b-marketplace.category',
        'name' => 'b2b_marketplace::app.admin.layouts.supplier-category',
        'route' => 'b2b_marketplace.admin.supplier.category.index',
        'sort' => 10
    ],
];