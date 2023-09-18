<?php

Route::group(['middleware' => ['web', 'b2b_marketplace']], function () {

    Route::prefix('admin/b2b-marketplace')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //supplier roles
            Route::get('roles', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RoleController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.suppliers.roles.index'
            ])->name('b2b_marketplace.admin.supplier.roles.index');

            Route::get('/roles/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RoleController@create')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.suppliers.roles.create'
            ])->name('b2b_marketplace.admin.supplier.roles.create');

            Route::post('/roles/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RoleController@store')->defaults('_config', [
                'redirect' => 'b2b_marketplace.admin.supplier.roles.index'
            ])->name('b2b_marketplace.admin.roles.store');

            Route::get('/roles/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RoleController@edit')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.suppliers.roles.edit'
            ])->name('b2b_marketplace.admin.supplier.roles.edit');

            Route::put('/roles/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RoleController@update')->defaults('_config', [
                'redirect' => 'b2b_marketplace.admin.supplier.roles.index'
            ])->name('b2b_marketplace.admin.supplier.roles.update');

            Route::post('/roles/delete/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RoleController@destroy')->name('b2b_marketplace.admin.supplier.roles.delete');

            //supplier admin route start here
            Route::prefix('suppliers/')->group(function () {

                Route::get('/', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.suppliers.index'
                ])->name('b2b_marketplace.admin.suppliers.index');

                //create suppliers
                Route::get('suppliers/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@create')->defaults('_config',[
                    'view' => 'b2b_marketplace::admin.suppliers.create'
                ])->name('b2b_marketplace.admin.supplier.create');

                Route::post('suppliers/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@store')->defaults('_config',[
                    'redirect' => 'b2b_marketplace.admin.suppliers.index'
                ])->name('b2b_marketplace.admin.supplier.store');

                Route::get('edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@edit')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.suppliers.edit'
                ])->name('b2b_marketplace.admin.suppliers.edit');

                Route::put('edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@update')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.suppliers.index'
                ])->name('b2bmarketplace.admin.supplier.update');

                //Delete Specific Supplier
                Route::post('delete/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@destroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.suppliers.index'
                ])->name('b2b_marketplace.admin.suppliers.delete');

                //MassDelete Supplier
                Route::post('mass-delete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@massDestroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.suppliers.index'
                ])->name('b2b_marketplace.admin.suppliers.mass-delete');

                //MassUpdate Supplier
                Route::post('mass-update', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@massUpdate')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.suppliers.index'
                ])->name('b2b_marketplace.admin.suppliers.mass-update');

                Route::get('supplier/product/search/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@search')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.suppliers.products.search'
                ])->name('admin.b2b_marketplace.supplier.product.search');

                Route::get('supplier/product/assign/{supplier_id}/{product_id?}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@assignProduct')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.suppliers.products.assign'
                ])->name('admin.b2b_marketplace.supplier.product.create');

                Route::post('supplier/product/assign/{supplier_id}/{product_id?}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierController@saveAssignProduct')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.suppliers.index'
                ])->name('admin.b2b_marketplace.supplier.product.store');
            });

            //products route start from here
            Route::prefix('products/')->group(function () {

                Route::get('/', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.products.index'
                ])->name('b2b_marketplace.admin.products.index');

                //product delete
                Route::post('delete{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductController@destroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.products.index'
                ])->name('b2b_marketplace.admin.products.delete');

                //supplier Product Mass delete
                Route::post('mass-delete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductController@massDestroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.products.index'
                ])->name('b2b_marketplace.admin.products.mass-delete');

                //supplier product Mass Update
                Route::post('mass-update', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductController@massUpdate')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.products.index'
                ])->name('b2b_marketplace.admin.products.mass-update');
            });

            //Supplier Orders
            Route::get('/orders', 'Webkul\B2BMarketplace\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.orders.index'
            ])->name('admin.b2b_marketplace.supplier.orders.index');

            //Pay supplier's
            Route::post('orders', 'Webkul\B2BMarketplace\Http\Controllers\Admin\OrderController@pay')->defaults('_config', [
                'redirect' => 'admin.b2b_marketplace.supplier.orders.index'
            ])->name('admin.b2b_marketplace.orders.pay');


            //transactions route
            Route::get('transactions', 'Webkul\B2BMarketplace\Http\Controllers\Admin\TransactionController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.transactions.index'
            ])->name('b2b_marketplace.admin.transactions.index');

            //Reviews route
            Route::get('reviews', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ReviewController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.reviews.index'
            ])->name('b2b_marketplace.admin.reviews.index');

            Route::post('reviews/massupdate', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ReviewController@massUpdate')->defaults('_config', [
                'redirect' => 'b2b_marketplace.admin.reviews.index'
            ])->name('admin.b2b_marketplace.reviews.massupdate');

            //Requeste Quote Route
            Route::get('requested-quote', 'Webkul\B2BMarketplace\Http\Controllers\Admin\RequestQuoteController@index')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.request-quote.index'
            ])->name('b2b_marketplace.admin.request-quote.index');

            //Download Quote Images
            Route::get('download/attachment/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@downloadAttachment')->name('b2b_marketplace.admin.supplier.quote.attachment.download');

             //Download Quote Attachments
             Route::get('download/images/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Supplier\RequestForQuoteController@downloadImages')->name('b2b_marketplace.admin.supplier.quote.images.download');

             // product Flag routes
            Route::prefix('product-flag')->group(function () {

                Route::get('/flag', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.product-flag.flag.index',
                ])->name('b2b_marketplace.admin.product-flag.flag.index');

                Route::get('/reason', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.product-flag.reason.index',
                ])->name('b2b_marketplace.admin.product-flag.reason.index');

                Route::get('reason/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@create')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.product-flag.reason.create',
                ])->name('b2b_marketplace.admin.product-flag.reason.create');

                Route::post('reson/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.product-flag.reason.index',
                ])->name('b2b_marketplace.admin.product-flag.reason.store');

                Route::get('reason/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@edit')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.product-flag.reason.edit',
                ])->name('b2b_marketplace.admin.product-flag.reason.edit');

                Route::post('reason/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@update')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.product-flag.reason.index',
                ])->name('b2b_marketplace.admin.product-flag.reason.update');

                Route::post('reason/delete/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@delete')->name('b2b_marketplace.admin.product-flag.reason.delete');

                Route::post('/massdelete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@massDelete')->name('b2b_marketplace.admin.product-flag.reason.mass-delete');

                Route::post('flag/massdelete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\ProductFlagReasonController@flagMassDelete')->name('b2b_marketplace.admin.product-flag.flag.mass-delete');
            });

            //supplier flag related routes
            Route::prefix('supplier-flag')->group(function () {

                Route::get('/flag', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.supplier-flag.flag.index',
                ])->name('b2b_marketplace.admin.supplier-flag.flag.index');

                Route::get('/reason', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.supplier-flag.reason.index',
                ])->name('b2b_marketplace.admin.supplier-flag.reason.index');

                Route::get('reason/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@create')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.supplier-flag.reason.create',
                ])->name('b2b_marketplace.admin.supplier-flag.reason.create');

                Route::post('reason/create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.supplier-flag.reason.index',
                ])->name('b2b_marketplace.admin.supplier-flag.reason.store');

                Route::get('reason/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@edit')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.supplier-flag.reason.edit',
                ])->name('b2b_marketplace.admin.supplier-flag.reason.edit');

                Route::post('reason/edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@update')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.supplier-flag.reason.index',
                ])->name('b2b_marketplace.admin.supplier-flag.reason.update');

                Route::post('reason/delete/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@delete')->name('b2b_marketplace.admin.supplier-flag.reason.delete');

                Route::post('reason/massdelete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@massDelete')->name('b2b_marketplace.admin.supplier-flag.reason.mass-delete');

                Route::post('flag/massdelete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierFlagReasonController@flagMassDelete')->name('b2b_marketplace.admin.supplier-flag.flag.mass-delete');

            });

             // supplier category routes start here
            Route::prefix('supplier-categories')->group(function () {
                Route::get('/', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@index')->defaults('_config', [
                    'view' => 'b2b_marketplace::admin.suppliers.category.index'
                ])->name('b2b_marketplace.admin.supplier.category.index');

                Route::get('create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@create')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.suppliers.category.create',
                ])->name('b2b_marketplace.admin.supplier.category.create');

                Route::post('create', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@store')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.supplier.category.index',
                ])->name('b2b_marketplace.admin.supplier.category.store');

                Route::get('edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@edit')->defaults('_config', [
                'view' => 'b2b_marketplace::admin.suppliers.category.edit',
                ])->name('b2b_marketplace.admin.supplier.category.edit');

                Route::post('edit/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@update')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.supplier.category.index',
                ])->name('b2b_marketplace.admin.supplier.category.update');

                Route::post('/delete/{id}', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@destroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.supplier.category.index',
                ])->name('b2b_marketplace.admin.supplier.category.delete');

                Route::post('/massdelete', 'Webkul\B2BMarketplace\Http\Controllers\Admin\SupplierCategoryController@massDestroy')->defaults('_config', [
                    'redirect' => 'b2b_marketplace.admin.supplier.category.index',
                ])->name('b2b_marketplace.admin.supplier.category.mass-delete');

            });

        });
    });
});