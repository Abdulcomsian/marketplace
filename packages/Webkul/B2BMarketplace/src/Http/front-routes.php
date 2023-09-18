<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency', 'b2b_marketplace']], function () {

    Route::prefix('b2b-marketplace')->group( function () {
        //Display the supplier central
        Route::get('/sell', 'Webkul\B2BMarketplace\Http\Controllers\Shop\B2BMarketplaceController@index')->defaults('_config', [
            'view' => 'b2b_marketplace::shop.supplier-central.index'
        ])->name('b2b_marketplace.supplier_central.index');

         // Flag routes
         Route::post('flag/product/create', 'Webkul\B2BMarketplace\Http\Controllers\Shop\FlagController@productFlagstore')->name('b2b_marketplace.flag.product.store');

         Route::post('flag/supplier/create', 'Webkul\B2BMarketplace\Http\Controllers\Shop\FlagController@supplierFlagstore')->name('b2b_marketplace.flag.supplier.store');
    });

    $domainName = config('app.url');

    Route::group(['domain' => '{url}'.'.'.$domainName], function () {

        Route::get('/b2b/shop', 'Webkul\B2BMarketplace\Http\Controllers\Shop\SupplierProfileController@show')->defaults('_config', [
            'view' => 'b2b_marketplace::shop.supplier.profile.index'
        ])->name('b2b_marketplace.supplier.show');

    });

    Route::post('/{url}/contact', 'Webkul\B2BMarketplace\Http\Controllers\Shop\SupplierProfileController@contact')
        ->name('b2b_marketplace.supplier.contact');

    Route::post('iscustomer', 'Webkul\B2BMarketplace\Http\Controllers\Shop\SupplierProfileController@isCustomer')
    ->name('b2b_marketplace.supplier.customer');

    //Supplier Products routes
    Route::get('supplier/{url}/products', 'Webkul\B2BMarketplace\Http\Controllers\Shop\SupplierProfileController@index')->defaults('_config', [
        'view' => 'b2b_marketplace::shop.supplier.products.index'
    ])->name('b2b_marketplace.products.index');

    //search supplier Product quick order
    Route::post('/quick-order/search', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\QuickOrderController@searchProduct')->name('b2b_marketplace.shop.customers.quick-order.search');

    //Quick Order routes
    Route::post('quick-order/', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\QuickOrderController@store')->name('b2b_marketplace.shop.profile.quick-order.store');

    //Get variant product
    Route::post('quick-order/config', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\QuickOrderController@getConfigData')->name('b2b_marketplace.shop.profile.quick-order.config.store');

    //Quick Order Add To Cart
    Route::post('quick-order/add', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\QuickOrderController@addToCart')->name('b2b_marketplace.shop.profile.quick-order.addToCart');

    //Supplier Review routes
    Route::get('supplier/{url}/reviews', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ReviewController@index')->defaults('_config', [
        'view' => 'b2b_marketplace::shop.supplier.reviews.index'
    ])->name('b2b_marketplace.reviews.index');

    // Display The Registration Page
    route::get('supplier/register','Webkul\B2BMarketplace\Http\Controllers\Shop\RegistrationController@index')
    ->defaults('_config', [
        'view' => 'b2b_marketplace::shop.customers.signup.onepage.index'
    ])->name('b2b_marketplace.shop.suppliers.signup.index');

    //Supplier Account Info
    Route::post('store-address', 'Webkul\B2BMarketplace\Http\Controllers\Shop\RegistrationController@storeAddress')
        ->name('b2b_marketplaceshop.shop.suppliers.signup.account-information');

    //Check Shop Url
    Route::post('/check-shop', 'Webkul\B2BMarketplace\Http\Controllers\Shop\RegistrationController@checkShopUrl')
        ->name('b2b_marketplace.shop.suppliers.checkurl');

    //Registration Supplier
    Route::post('supplier/register', 'Webkul\B2BMarketplace\Http\Controllers\Shop\RegistrationController@store')
        ->name('b2b_marketplaceshop.shop.suppliers.signup.store');

    //login routes
    Route::get('supplier/login','Webkul\B2BMarketplace\Http\Controllers\Shop\SessionController@index')
    ->defaults('_config',[
        'view' => 'b2b_marketplace::shop.customers.session.index'
    ])->name('b2b_marketplace.shop.supplier.session.index');

    //login supplier
    Route::post('supplier/login','Webkul\B2BMarketplace\Http\Controllers\Shop\SessionController@create')
    ->defaults('_config',[
        'redirect' => 'b2b_marketplace.supplier.dashboard.index'
    ])->name('b2b_marketplace.shop.supplier.session.create');

    //verify account
    Route::get('/verify-account/{token}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\RegistrationController@verifyAccount')->name('b2b_marketplace.supplier.verify');

    //resend verification email
    Route::get('/resend/verification/{email}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\RegistrationController@resendVerificationEmail')->name('supplier.resend.verification-email');

    // Forgot Password Form Show
    Route::get('/supplier/forgot-password', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ForgotPasswordController@create')->defaults('_config', [
        'view' => 'b2b_marketplace::shop.supplier.signup.forgot-password'
    ])->name('supplier.forgot-password.create');

    // Forgot Password Form Store
    Route::post('/supplier/forgot-password', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ForgotPasswordController@store')->name('supplier.forgot-password.store');

    // Reset Password Form Show
    Route::get('/supplier/reset-password/{token}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ResetPasswordController@create')->defaults('_config', [
        'view' => 'b2b_marketplace::shop.supplier.signup.reset-password'
    ])->name('supplier.reset-password.create');

    // Reset Password Form Store
    Route::post('/supplier/reset-password', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ResetPasswordController@store')->defaults('_config', [
        'redirect' => 'b2b_marketplace.supplier.dashboard.index'
    ])->name('supplier.reset-password.store');

    //Shop buynow button action
    Route::get('buynow/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\CartController@buyNow')->name('b2b_marketplace.shop.product.buynow');

    //Customer's Route
    Route::group(['middleware' => ['customer']], function () {

        //Message from Product Page Route
        Route::post('account/shop-message', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\MessageController@storeProductMsg')
        ->name('b2b_marketplace.customers.account.supplier.messages.storeProductMsg');

        //Supplier Review routes
        Route::get('supplier/{url}/reviews/create', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ReviewController@create')->defaults('_config', [
            'view' => 'b2b_marketplace::shop.supplier.reviews.create'
        ])->name('b2b_marketplace.reviews.create');

        Route::post('supplier/{url}/reviews/create', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ReviewController@store')->defaults('_config', [
            'redirect' => 'b2b_marketplace.supplier.show'
        ])->name('b2b_marketplace.reviews.store');

        //Supplier's Messages Route
        Route::get('account/supplier-message', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\MessageController@index')->defaults('_config', [
            'view' => 'b2b_marketplace::shop.customers.account.messages.index'
        ])->name('b2b_marketplace.customers.account.supplier.messages.index');

        //Show The Detailed Messages
        Route::post('account/messages', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\MessageController@show')
        ->name('b2b_marketplace.customers.account.supplier.messages.show');


        Route::post('/chat-customer/upload-files','Webkul\B2BMarketplace\Http\Controllers\Shop\Account\MessageController@uploadFiles')
        ->name('b2b_marketplace.customer-chat.upload-chat.file');

        //Show The Detailed Messages
        Route::post('account/messages/store', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\MessageController@store')
        ->name('b2b_marketplace.customers.account.supplier.messages.store');

        //Search messages
        Route::post('account/messages/search', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\MessageController@search')
        ->name('b2b_marketplace.shop.customer.messages.search');

        Route::prefix('customer')->group(function () {

            //Show All Requested Quotes
            Route::get('quote', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@show')->defaults('_config', [
                'view' => 'b2b_marketplace::shop.customers.request-quote.index'
            ])->name('b2b_marketplace.shop.customers.rfq.show');

            //Download Quote Attachments
            Route::get('download/images/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@downloadImages')->name('b2b_marketplace.shop.customers.quote.images.download');

            //Download Quote Images
            Route::get('download/attachment/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@downloadAttachment')->name('b2b_marketplace.shop.customers.quote.attachment.download');

            //Approve Quote By Customer
            Route::Post('quote/supplier_item/{id}/customer_item/{quoteId}/supplier_id/{supplierId}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@approveQuote')->defaults('_config', [
            ])->name('b2b_marketplace.customers.account.supplier.quote.approve');

            //reject last quote
            Route::get('quote/reject/{id}/item_id/{item_id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@reject')->name('b2b_marketplace.customers.rfq.reject');

            Route::prefix('quote/')->group(function () {

                //supplier response status Start from here

                //New Status Quote action view
                Route::get('response/new/view/{id}/id/{supplier_id}/producct/{product_id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestedQuote\QuoteResponseController@show')->defaults('_config', [
                    'view' => 'b2b_marketplace::shop.customers.request-quote.view'
                ])->name('b2b_marketplace.customer.request-quote.view');

                Route::get('{new}/id/{id}/item/{item_id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestedQuote\QuoteResponseController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::shop.customers.request-quote.tab'
                ])->name('b2b_marketplace.supplier.request-quote.status');

            });

            //customer request for quote
            Route::get('request-for-quote', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::shop.customers.request-quote.create'
            ])->name('b2b_marketplace.shop.customers.rfq.index');

            //product search request quote
            Route::post('/product/search', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@searchProduct')->defaults('_config', [
                'view' => 'b2b_marketplace::shop.customers.request-quote.create'
            ])->name('b2b_marketplace.shop.customers.rfq.search');

            //profile product search request quote
            Route::post('/profile/product/search', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@searchProductFromProfile')->defaults('_config', [
                'view' => 'b2b_marketplace::shop.customers.request-quote.create'
            ])->name('b2b_marketplace.shop.profile.rfq.search');

            //add product for the request quote
            Route::post('requestquote', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@addProduct')->defaults('_config', [
                'view' => 'b2b_marketplace::shop.customers.request-quote.create'
            ])->name('b2b_marketplace.shop.customers.rfq.addproduct');

            //Store Request For Quote
            Route::post('requestquote/add', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@store')->defaults('_config', [
                'redirect' => 'b2b_marketplace.shop.customers.rfq.show'
            ])->name('b2b_marketplace.shop.customers.rfq.store');

            //Store Request For Quote From The Supplier Profile
            Route::post('profile/requestquote/add', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\SupplierProfileController@store')->defaults('_config', [
                'redirect' => 'b2b_marketplace.shop.customers.rfq.show'
            ])->name('b2b_marketplace.profile.customers.rfq.store');

            //bulk add-to-cart
            Route::post('bulkcart/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\CartController@add')->defaults('_config', [
                'redirect' => 'customer.profile.index'
            ])->name('b2b_marketplace.cart.add');

            //customer request for quote
            Route::get('/shop/rfq/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::shop.customers.request-quote.create-direct-quote'
            ])->name('b2b_marketplace.home.rfq');

            Route::post('/shop/rfq/store/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestQuoteController@store')->defaults('_config', [
                'redirect' => 'b2b_marketplace.shop.customers.rfq.show'
            ])->name('b2b_marketplace.home.products.rfq.store');

        });

        //customer messages
        Route::post('customer-quote/messages/supplier_item/{id}/customer_item/{quoteId}/supplier_id/{supplierId}', 'Webkul\B2BMarketplace\Http\Controllers\Shop\Account\Messages\QuoteMessageController@store')->name    ('b2b_marketplace.customer.request-quote.message');

    });

    //Supplier review routes
    Route::get('products/supplier/{id}/offers', 'Webkul\B2BMarketplace\Http\Controllers\Shop\ProductController@offers')->defaults('_config', [
        'view' => 'b2b_marketplace::shop.products.offers'
    ])->name('b2b_marketplace.product.offers.index');

});
