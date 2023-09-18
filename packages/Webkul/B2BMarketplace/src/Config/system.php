<?php
return [
    [
        'key' => 'b2b_marketplace',
        'name' => 'b2b_marketplace::app.admin.system.b2b-marketplace',
        'sort' => 1
    ], [
        'key' => 'b2b_marketplace.settings',
        'name' => 'b2b_marketplace::app.admin.system.settings',
        'sort' => 1,
    ], [
        'key' => 'b2b_marketplace.settings.general',
        'name' => 'b2b_marketplace::app.admin.system.general',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'b2b_marketplace::app.admin.system.status',
                'type' => 'boolean'
            ], [
                'name' => 'message',
                'title' => 'b2b_marketplace::app.admin.system.message',
                'type' => 'text',
                'info' => 'b2b_marketplace::app.admin.system.message-note'
            ], [
                'name' => 'allow_rfq',
                'title' => 'b2b_marketplace::app.admin.system.allow_rfq',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name' => 'commission_per_unit',
                'title' => 'b2b_marketplace::app.admin.system.commission-per-unit',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'supplier_approval_required',
                'title' => 'b2b_marketplace::app.admin.system.supplier-approval-required',
                'type'          => 'boolean',
            ], [
                'name' => 'product_approval_required',
                'title' => 'b2b_marketplace::app.admin.system.product-approval-required',
                'type'          => 'boolean',
            ], [
                'name' => 'can_create_invoice',
                'title' => 'b2b_marketplace::app.admin.system.can-create-invoice',
                'type'          => 'boolean',
            ], [
                'name' => 'can_create_shipment',
                'title' => 'b2b_marketplace::app.admin.system.can-create-shipment',
                'type'          => 'boolean',
            ], [
                'name' => 'can_cancel_order',
                'title' => 'b2b_marketplace::app.admin.system.can_cancel_order',
                'type'          => 'boolean',
            ], [
                'name' => 'chat_notification',
                'title' => 'b2b_marketplace::app.admin.system.is_chat_notification',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'b2b_marketplace.settings.supplier_category',
        'name' => 'b2b_marketplace::app.admin.system.supplier-category',
        'sort' => 1,
        'fields' => [

            [
                'name' => 'allow',
                'title' => 'b2b_marketplace::app.admin.system.allow-category',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.all',
                        'value' => 'ALL'
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.specific',
                        'value' => 'SPECIFIC'
                    ]
                ],
                'info' => 'b2b_marketplace::app.admin.system.info'
            ],
        ]
    ], [
        'key' => 'b2b_marketplace.settings.supplier_flag',
        'name' => 'b2b_marketplace::app.admin.system.supplier-flag',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'b2b_marketplace::app.admin.system.status',
                'type'          => 'boolean',
            ], [
                'name' => 'text',
                'title' => 'b2b_marketplace::app.admin.system.text',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'guest_can',
                'title' => 'b2b_marketplace::app.admin.system.guest-can',
                'type' => 'boolean',
            ], [
                'name' => 'other_reason',
                'title' => 'b2b_marketplace::app.admin.system.other-reason',
                'type' => 'select',
                'type'          => 'boolean',
            ], [
                'name' => 'other_placeholder',
                'title' => 'b2b_marketplace::app.admin.system.other-placeholder',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ],
        ]
    ], [
        'key' => 'b2b_marketplace.settings.product_flag',
        'name' => 'b2b_marketplace::app.admin.system.product-flag',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'status',
                'title' => 'b2b_marketplace::app.admin.system.status',
                'type'          => 'boolean',
            ], [
                'name' => 'text',
                'title' => 'b2b_marketplace::app.admin.system.text',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'guest_can',
                'title' => 'b2b_marketplace::app.admin.system.guest-can',
                'type'          => 'boolean',
            ], [
                'name' => 'other_reason',
                'title' => 'b2b_marketplace::app.admin.system.other-reason',
                'type'          => 'boolean',
            ], [
                'name' => 'other_placeholder',
                'title' => 'b2b_marketplace::app.admin.system.other-placeholder',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ],
        ]
    ], [
        'key' => 'b2b_marketplace.settings.supplier_profile_page',
        'name' => 'b2b_marketplace::app.admin.system.profile-page',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'policies_enable',
                'title' => 'b2b_marketplace::app.admin.system.policies-enable',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'rewrite_shop_url',
                'title' => 'b2b_marketplace::app.admin.system.rewrite-shop-url',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ],

        ]
    ], [
        'key' => 'b2b_marketplace.settings.email',
        'name' => 'b2b_marketplace::app.admin.system.email-verification',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'verification',
                'title' => 'b2b_marketplace::app.admin.system.email-verification',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ], [
        'key' => 'b2b_marketplace.settings.landing_page',
        'name' => 'b2b_marketplace::app.admin.system.landing-page',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'page_title',
                'title' => 'b2b_marketplace::app.admin.system.page-title',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_banner',
                'title' => 'b2b_marketplace::app.admin.system.show-banner',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'banner',
                'title' => 'b2b_marketplace::app.admin.system.banner',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'banner_content',
                'title' => 'b2b_marketplace::app.admin.system.banner-content',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_features',
                'title' => 'b2b_marketplace::app.admin.system.show-features',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'feature_heading',
                'title' => 'b2b_marketplace::app.admin.system.feature-heading',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_info',
                'title' => 'b2b_marketplace::app.admin.system.feature-info',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_1',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-1',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_1',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-1',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_2',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-2',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_2',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-2',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_3',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-3',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_3',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-3',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_4',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-4',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_4',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-4',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_5',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-5',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_5',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-5',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_6',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-6',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_6',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-6',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_7',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-7',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_7',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-7',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_8',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-8',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_8',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-8',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_9',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-9',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_9',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-9',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_popular_sellers',
                'title' => 'b2b_marketplace::app.admin.system.show-popular-suppliers',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'open_shop_button_label',
                'title' => 'b2b_marketplace::app.admin.system.open-shop-button-label',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'about_b2bmarketplace',
                'title' => 'b2b_marketplace::app.admin.system.about-b2bmarketplace',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_open_shop_block',
                'title' => 'b2b_marketplace::app.admin.system.show-open-shop-block',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'open_shop_info',
                'title' => 'b2b_marketplace::app.admin.system.open-shop-info',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ]
        ]
    ], [
        'key' => 'b2b_marketplace.settings.velocity',
        'name' => 'b2b_marketplace::app.velocity.system.velocity-content',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'page_title',
                'title' => 'b2b_marketplace::app.admin.system.page-title',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_banner',
                'title' => 'b2b_marketplace::app.admin.system.show-banner',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'banner',
                'title' => 'b2b_marketplace::app.admin.system.banner',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'banner_content',
                'title' => 'b2b_marketplace::app.admin.system.banner-content',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_features',
                'title' => 'b2b_marketplace::app.admin.system.show-features',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'feature_heading',
                'title' => 'b2b_marketplace::app.admin.system.feature-heading',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_info',
                'title' => 'b2b_marketplace::app.admin.system.feature-info',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_1',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-1',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_1',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-1',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_2',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-2',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_2',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-2',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_3',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-3',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_3',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-3',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_4',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-4',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_4',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-4',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_5',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-5',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_5',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-5',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_6',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-6',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_6',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-6',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_7',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-7',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_7',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-7',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_8',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-8',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_8',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-8',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'feature_icon_9',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-9',
                'type' => 'image',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'feature_icon_label_9',
                'title' => 'b2b_marketplace::app.admin.system.feature-icon-label-9',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_popular_suppliers',
                'title' => 'b2b_marketplace::app.admin.system.show-popular-suppliers',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'open_shop_button_label',
                'title' => 'b2b_marketplace::app.admin.system.open-shop-button-label',
                'type' => 'text',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'about_b2bmarketplace',
                'title' => 'b2b_marketplace::app.admin.system.about-b2bmarketplace',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'show_open_shop_block',
                'title' => 'b2b_marketplace::app.admin.system.show-open-shop-block',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'b2b_marketplace::app.admin.system.yes',
                        'value' => true
                    ], [
                        'title' => 'b2b_marketplace::app.admin.system.no',
                        'value' => false
                    ]
                ]
            ], [
                'name' => 'open_shop_info',
                'title' => 'b2b_marketplace::app.admin.system.open-shop-info',
                'type' => 'textarea',
                'channel_based' => true,
                'locale_based' => true
            ], [
                'name' => 'setup_icon_1',
                'title' => 'b2b_marketplace::app.admin.system.setup-icon-1',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'setup_icon_2',
                'title' => 'b2b_marketplace::app.admin.system.setup-icon-2',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'setup_icon_3',
                'title' => 'b2b_marketplace::app.admin.system.setup-icon-3',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'setup_icon_4',
                'title' => 'b2b_marketplace::app.admin.system.setup-icon-4',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ], [
                'name' => 'setup_icon_5',
                'title' => 'b2b_marketplace::app.admin.system.setup-icon-5',
                'type' => 'image',
                'validation' => 'mimes:jpeg,bmp,png,jpg',
                'channel_based' => true,
                'locale_based' => false
            ]
        ]
    ]
];