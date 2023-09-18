<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency', 'b2b_marketplace']], function() {

    Route::prefix('supplier')->group(function () {

        Route::get('/', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller@redirectToLogin');

        /**
         * Route for getting the supplier information.
         */
        Route::post('/supplier-info', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@supplierinfo');

        Route::group(['middleware' => ['supplier']], function () {

            //supplier log out
            Route::get('/logout', 'Webkul\B2BMarketplace\Http\Controllers\Shop\SessionController@destroy')->defaults('_config', [
                'redirect' => 'b2b_marketplace.shop.supplier.session.index'
            ])->name('b2b_marketplace.session.destroy');

            // Supplier Dashboard Route
            Route::get('dashboard', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\DashboardController@index')
            ->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.dashboard.index'
            ])->name('b2b_marketplace.supplier.dashboard.index');


            //supplier's messages
            Route::get('message', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\MessageController@index')
            ->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.messages.index'
            ])->name('b2b_marketplace.supplier.messages.index');

            //show detailed messages
            Route::post('message/detail', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\MessageController@show')->name('b2b_marketplace.supplier.messages.show');

            //show detailed messages
            Route::post('message/store', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\MessageController@store')->name('b2b_marketplace.supplier.messages.store');
            // store attachements 
            Route::post('message/store/attachement', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\MessageController@uploadFiles')->name('b2b_marketplace.supplier.messages.uploadFiles');

            //show detailed messages
            Route::post('message/search', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\MessageController@search')->name('b2b_marketplace.supplier.messages.search');

            //suplier catalog route
            Route::prefix('catalog/')->group(function () {

                //search assign product
                Route::get('product/', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\AssignProductController@search')
                ->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.search'
                ])->name('b2b_marketplace.supplier.catalog.products.search');

                //create assign product
                Route::get('products/assign/{id?}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\AssignProductController@create')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.assign'
                ])->name('b2b_marketplace.account.products.assign');

                //store assign product
                Route::post('products/assign/{id?}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\AssignProductController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.catalog.products.index'
                ])->name('b2b_marketplace.account.products.assign-store');

                //edit assign poroduct
                Route::get('products/edit-assign/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\AssignProductController@edit')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.edit-assign'
                ])->name('b2b_marketplace.account.products.edit-assign');

                //update assign product
                Route::put('products/edit-assign/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\AssignProductController@update')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.catalog.products.index'
                ])->name('b2b_marketplace.account.products.update-assign');


                //catalog product route
                Route::get('show', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@index')
                ->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.index'
                ])->name('b2b_marketplace.supplier.catalog.products.index');

                //Create Supplier Product's
                Route::get('product/create', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@create')
                ->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.create'
                ])->name('b2b_marketplace.supplier.catalog.products.create');

                Route::post('product/create', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.catalog.products.edit'
                ])->name('b2b_marketplace.account.products.store');

                //product search for supplier linked products
                Route::get('products/search', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@productLinkSearch')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.edit',
                ])->name('b2b_marketplace.catalog.products.productlinksearch');

                //Edit the Specific Product
                Route::get('product/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@edit')
                ->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.edit'
                ])->name('b2b_marketplace.supplier.catalog.products.edit');

                //update the product
                Route::put('product/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@update')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.catalog.products.index'
                ])->name('b2b_marketplace.supplier.catalog.products.update');

                //product delete
                Route::post('/product/delete/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@destroy')->name('b2b_marketplace.supplier.catalog.products.delete');

                //product massdelete
                Route::post('product/massdelete', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@massDestroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.catalog.products.index'
                ])->name('b2b_marketplace.supplier.products.massdelete');

                Route::get('products/copy/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\ProductController@copy')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.catalog.products.edit',
                ])->name('supplier.catalog.products.copy');
            });

            Route::prefix('sales/')->group(function () {
                //Sales Route
                Route::get('orders', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\OrderController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.orders.index'
                ])->name('b2b_marketplace.supplier.sales.orders.index');

                //show invoice view route
                Route::get('orders/view/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\OrderController@view')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.orders.view'
                ])->name('b2b_marketplace.supplier.sales.orders.view');

                //Cancel Order From Supplier
                Route::get('/orders/cancel/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\OrderController@cancel')->name('b2b_marketplace.supplier.account.orders.cancel');

                // Sales Invoices Routes
                Route::get('invoices', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\OrderController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.invoices.index'
                ])->name('b2b_marketplace.supplier.sales.invoices.index');

                // Sales Invoices view page
                Route::get('invoices/view/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\InvoiceController@view')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.invoices.view'
                ])->name('b2b_marketplace.sales.invoices.view');

                //create invoices
                Route::get('invoices/create/{order_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\InvoiceController@create')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.invoices.create'
                ])->name('b2b_marketplace.supplier.sales.invoice.create');

                //invoice order
                Route::post('invoices/create/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\InvoiceController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.sales.orders.view'
                ])->name('b2b_marketplace.supplier.sales.invoice.store');

                //Print Invoice
                Route::get('invoices/print/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\InvoiceController@print')
                ->name('b2b_marketplace.supplier.sales.invoice.print');


                // Sales Shipments Routes
                Route::get('shipments', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\OrderController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.shipments.index'
                ])->name('b2b_marketplace.supplier.sales.shipments.index');

                //create shipment
                Route::get('shipments/create/{order_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\ShipmentController@create')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.shipments.create'
                ])->name('b2b_marketplace.supplier.sales.shipments.create');

                //create shippment for the order
                Route::post('shipments/create/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\ShipmentController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.supplier.sales.orders.view'
                ])->name('b2b_marketplace.supplier.sales.shipments.store');

                //view shippment
                Route::get('shipments/view/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales\ShipmentController@view')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.sales.shipments.view'
                ])->name('b2b_marketplace.sales.shipments.view');

                //DataGrid Export
                Route::post('supplier/export', 'Webkul\B2BMarketplace\Http\Controllers\ExportController@export')->name('b2b_marketplace.datagrid.export');
            });


            //Show Supplier's Customer's
            Route::get('customers', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\CustomerController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.customers.index'
            ])->name('b2b_marketplace.supplier.customers.index');

            //Supplier Transactions
            Route::get('/transactions', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\TransactionController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.transactions.index'
            ])->name('b2b_marketplace.suppliers.transaction.index');

            Route::get('transactions/view/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\TransactionController@view')->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.transactions.view'
            ])->name('b2b_marketplace.suppliers.transactions.view');

            Route::prefix('buyingleads')->group(function () {

                //Supplier Buying Leads
                Route::get('/', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\BuyingLeadController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.leads.index'
                ])->name('b2b_marketplace.supplier.leads.index');

                //Send quote request
                Route::get('/send-quote/{id}/item_id/{item_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\BuyingLeadController@show')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.leads.send-quote.create'
                ])->name('b2b_marketplace.supplier.leads.send-quote.create');

                //Send quote request
                Route::post('/send-quote/{id}/item_id/{quote_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\BuyingLeadController@sendQuote')->name('b2b_marketplace.supplier.leads.send-quote.store');

                //store message send to customer
                Route::post('message/store', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\BuyingLeadController@store')->name('b2b_marketplace.supplier.leads.messsage.store');
            });


            Route::prefix('supplier-quote')->group(function () {

                //Customer Request Status For Quote Start From Here
                Route::get('/', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.index'
                ])->name('b2b_marketplace.supplier.request-quote.index');

                //requested quote status new
                Route::get('/new', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@new')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.new.index'
                ])->name('b2b_marketplace.supplier.request-quote.new.index');

                //send quote for status new
                Route::get('/new/send-quote/{id}/item_id/{item_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@create')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.leads.send-quote.create'
                ])->name('b2b_marketplace.supplier.request-quote.new.send-quote.create');

                //Reject Quote Request
                Route::get('/reject/{id}/item_id/{item_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@reject')->name('b2b_marketplace.supplier.rfq.reject');

                //quote request status pending
                Route::get('/pending', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@pending')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.pending.index'
                ])->name('b2b_marketplace.supplier.request-quote.pending.index');

                //quote request status answered
                Route::get('/answered', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@answered')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.answered.index'
                ])->name('b2b_marketplace.supplier.request-quote.answered.index');

                //quote request status confirmed
                Route::get('/confirmed', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@confirmed')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.confirmed.index'
                ])->name('b2b_marketplace.supplier.request-quote.confirmed.index');

                //quote request status rejected
                Route::get('/rejected', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@rejected')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.rejected.index'
                ])->name('b2b_marketplace.supplier.request-quote.rejected.index');

                //Answered Status Quote action view
                Route::get('/answered/view/{id}/item/{product_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@answeredQuote')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.request-quote.new.view'
                ])->name('b2b_marketplace.supplier.request-quote.Answered.view');

                //supplier quote messages
                Route::post('/messages/id/{id}/item_id/{quoteId}/quote_id/{quote_id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Messages\QuoteMessageController@store')->name('b2b_marketplace.supplier.request-quote.message');

                //Download Quote Attachments
                Route::get('download/images/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@downloadImages')->name('b2b_marketplace.supplier.quote.images.download');

                //Download Quote Images
                Route::get('download/attachment/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@downloadAttachment')->name('b2b_marketplace.supplier.quote.attachment.download');
            });

            //supplier company review
            Route::get('reviews', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\ReviewController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.reviews.index'
            ])->name('b2b_marketplace.supplier.reviews.index');

            Route::prefix('settings')->group( function () {

                //Supplier Account Profile Related Route
                Route::get('/profile', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.settings.profile.index'
                ])->name('b2b_marketplace.supplier.settings.index');

                //Update Supplier Profile
                Route::post('/profile/edit', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@update')->name('b2b_marketplace.supplier.profile.update');

                 //Supplier Categories
                Route::get('/supplier/categories', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@getCategories')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.settings.category.index'
                ])->name('b2b_marketplace.supplier.profile.category');

                //Supplier Password Change
               Route::get('/supplier/password', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@passwordChange')->defaults('_config', [
                    'view' => 'b2b_marketplace::supplier.settings.password.index'
                ])->name('b2b_marketplace.supplier.profile.password');

                //Supplier Password Change
                Route::put('/supplier/password/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@storeNewPassword')
                ->name('b2b_marketplace.supplier.profile.password.store');

                 //Supplier Categories Store
                 Route::post('/supplier/categories', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@storeCategory')
                ->name('b2b_marketplace.supplier.profile.category.store');

                    //Supplier Verification
                Route::get('/verification', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@show')->defaults('_config', [
                'view' => 'b2b_marketplace::supplier.settings.verification.index'
                ])->name('b2b_marketplace.supplier.verification.show');

                //Resend Verification Mail
                Route::get('/resend-verification', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\Account\SupplierController@resendVerification')->name('b2b_marketplace.supplier.verification.resend');
            });
        });
    });
});
