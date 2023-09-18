<?php
return [
    [
        'key' => 'b2b-marketplace',
        'name' => 'b2b_marketplace::app.admin.acl.b2b-marketplace',
        'route' => 'b2b_marketplace.admin.suppliers.index',
        'sort' => 3,
        'icon-class' => 'dashboard-icon',
    ], [
        'key' => 'b2b-marketplace.suppliers',
        'name' => 'b2b_marketplace::app.admin.acl.suppliers',
        'route' => 'b2b_marketplace.admin.suppliers.index',
        'sort' => 1,
    ], [
        'key' => 'b2b-marketplace.products',
        'name' => 'b2b_marketplace::app.admin.acl.products',
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
    ],
];