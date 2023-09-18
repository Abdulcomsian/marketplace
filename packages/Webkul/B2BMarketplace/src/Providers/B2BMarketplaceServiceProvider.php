<?php

namespace Webkul\B2BMarketplace\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\B2BMarketplace\Http\Middleware\RedirectIfNotSupplier;
use Webkul\B2BMarketplace\Models\MessageMapping;
use Webkul\Core\Tree;
use Webkul\B2BMarketplace\Bouncer;
use Webkul\B2BMarketplace\Facades\SupplierBouncer as BouncerFacade;
use Webkul\B2BMarketplace\Http\Middleware\Bouncer as BouncerMiddleware;
use Webkul\B2BMarketplace\Http\Middleware\B2BMarketplaceMiddleware as B2BMiddleware;

class B2BMarketplaceServiceProvider extends ServiceProvider
{
     /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/front-routes.php';

        include __DIR__ . '/../Http/admin-routes.php';

        include __DIR__ . '/../Http/supplier-routes.php';

        $router->aliasMiddleware('supplierbouncer', BouncerMiddleware::class);

        $router->aliasMiddleware('b2b_marketplace', B2BMiddleware::class);

        $this->app->register(ModuleServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'b2b_marketplace');

        $router->aliasMiddleware('supplier', RedirectIfNotSupplier::class);

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'b2b_marketplace');

        $this->overrideModels();

        $this->composeView();

        $this->registerACL();

        $this->publishable();
    }

    /**
     * publish the existing views
     */
    public function publishable()
    {
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/b2b/assets'),
        ], 'public');

        //admin nav left override....
        $this->publishes([
            __DIR__ . '/../Resources/views/admin/layouts/nav-left.blade.php' => resource_path('views/vendor/admin/layouts/nav-left.blade.php'),
        ]);

        //default theme overrides......
        //side menu
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/customers/account/partials' => resource_path('themes/default/views/customers/account/partials'),
        ]);

        //checkout page
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/checkout/cart/index.blade.php' => resource_path('themes/default/views/checkout/cart/index.blade.php')
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/products/add-buttons.blade.php' => resource_path('themes/default/views/products/add-buttons.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/products/add-to-cart.blade.php' => resource_path('themes/default/views/products/add-to-cart.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/products/buy-now.blade.php' => resource_path('themes/default/views/products/buy-now.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/products/view/stock.blade.php' => resource_path('themes/default/views/products/view/stock.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/products/view.blade.php' => resource_path('themes/default/views/products/view.blade.php'),
        ]);


        //Velocity theme overrides........
        //velocity master
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/layouts/velocity-master.blade.php' => resource_path('themes/velocity/views/layouts/master.blade.php')
        ]);

        //supplier login header
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/layouts/top-nav' => resource_path('themes/velocity/views/layouts/top-nav'),
        ]);

        //side menu
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/partials' => resource_path('themes/velocity/views/customers/account/partials'),
        ]);

        //compare products
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/customers/account/compare' => resource_path('themes/velocity/views/customers/account/compare'),
        ]);

        //mobile side-menu
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/layouts/header/mobile.blade.php' => resource_path('themes/velocity/views/layouts/header/mobile.blade.php')
        ]);

        //supplier info event
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products/view.blade.php' => resource_path('themes/velocity/views/products/view.blade.php'),
        ]);

        //checkout page for bulk porduct quantity
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/checkout/cart/index.blade.php' => resource_path('themes/velocity/views/checkout/cart/index.blade.php')
        ]);

        // checkout onepage review images blade file overriding.
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/checkout/onepage/review.blade.php' => resource_path('themes/velocity/views/checkout/onepage/review.blade.php')
        ]);

        //add-to-cart
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products/add-to-cart.blade.php' => resource_path('themes/velocity/views/products/add-to-cart.blade.php')
        ]);

        //buy now button
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products/buy-now.blade.php' => resource_path('themes/velocity/views/products/buy-now.blade.php')
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products/view/stock.blade.php' => resource_path('themes/velocity/views/products/view/stock.blade.php')
        ]);

        // mini cart with supplier details.
        // overridinge the mini-cart
        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/checkout/cart/mini-cart.blade.php' => resource_path('themes/default/views/checkout/cart/mini-cart.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/checkout/cart/mini-cart.blade.php' => resource_path('themes/velocity/views/checkout/cart/mini-cart.blade.php'),
        ]);
          
        $this->publishes([
            __DIR__ . '/../Cart.php' => __DIR__ .'/../../../Checkout/src/Cart.php',
        ]);

        $this->publishes([
            __DIR__ . '/../CartController.php' => __DIR__ .'/../../../Velocity/src/Http/Controllers/Shop/CartController.php',
        ]);


        $this->publishes([
            __DIR__ . '/../ShipmentRepository.php' => __DIR__ .'/../../../Sales/src/Repositories/ShipmentRepository.php',
        ]);

        // chat assets
        $this->publishes([
            __DIR__ . '/../Resources/assets/images/icon' => public_path('themes/velocity/assets/images'),
        ], 'public');

    }

    /**
     * Override the existing models
     */
    public function overrideModels()
    {
        $this->app->concord->registerModel(
            \Webkul\Product\Contracts\ProductInventory::class, \Webkul\B2BMarketplace\Models\ProductInventory::class
        );
    }
    /**
     * Register Services
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package Config
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/supplier-menu.php', 'menu.supplier'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/supplier.php', 'menu'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__ ) . '/Config/acl.php', 'acl'
        );

        //merge config/auth/supplier
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/auth/guards.php', 'auth.guards'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/auth/providers.php', 'auth.providers'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/auth/passwords.php', 'auth.passwords'
        );

        //product type
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/product-types.php', 'product-types'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/supplier-acl.php', 'supplier-acl'
        );
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerBouncer()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('SupplierBouncer', BouncerFacade::class);

        $this->app->singleton('supplierbouncer', function () {
            return new Bouncer();
        });
    }

    /**
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer(['b2b_marketplace::supplier.layouts.nav-left', 'b2b_marketplace::supplier.layouts.mobile-nav', 'b2b_marketplace::supplier.layouts.tabs'], function ($view) {
            $tree = Tree::create();

            $permissionType = auth()->guard('supplier')->user()->role->permission_type;
            $allowedPermissions = auth()->guard('supplier')->user()->role->permissions;

            foreach (config('menu.supplier') as $index => $item) {

                if (! app()->make(\Webkul\B2BMarketplace\Bouncer::class)->hasPermission($item['key'])) {
                    continue;
                }
                if ($item['key'] == 'messages' && isset(auth()->guard('supplier')->user()->id)) {
                    $messages = MessageMapping::with('messages')->where('supplier_id', auth()->guard('supplier')->user()->id)->orderBy('id', 'DESC')->get();
                    $msgCount = 0;
                    foreach ($messages as $messageToCustomer) {
                        $totalMessages = $messageToCustomer['messages']
                            ->where('role', 'customer')
                            ->where('is_new', '1');
                        $msgCount += count($totalMessages);
                    }
                    $item['msgCount'] = $msgCount;
                }
                
                if ($index + 1 < count(config('menu.supplier')) && $permissionType != 'all') {
                    $permission = config('menu.supplier')[$index + 1];

                    if (substr_count($permission['key'], '.') == 2 && substr_count($item['key'], '.') == 1) {
                        foreach ($allowedPermissions as $key => $value) {
                            if ($item['key'] == $value) {
                                $neededItem = $allowedPermissions[$key + 1];

                                foreach (config('menu.supplier') as $key1 => $findMatced) {
                                    if ($findMatced['key'] == $neededItem) {
                                        $item['route'] = $findMatced['route'];
                                    }
                                }
                            }
                        }
                    }
                }

                $tree->add($item, 'menu');
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });

        view()->composer(['b2b_marketplace::admin.suppliers.roles.create', 'b2b_marketplace::admin.suppliers.roles.edit', 'b2b_marketplace::supplier.layouts.tabs'], function ($view) {
            $view->with('acl', $this->createACL());
        });
    }

    /**
     * Registers acl to entire application
     *
     * @return void
     */
    public function registerACL()
    {
        $this->app->singleton('supplier-acl', function () {
            return $this->createACL();
        });
    }

    /**
     * Create acl tree
     *
     * @return mixed
     */
    public function createACL()
    {
        static $tree;

        if ($tree) {
            return $tree;
        }

        $tree = Tree::create();

        foreach (config('supplier-acl') as $item) {
            $tree->add($item, 'acl');
        }

        $tree->items = core()->sortItems($tree->items);

        return $tree;
    }
}