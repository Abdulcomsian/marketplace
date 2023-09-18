<?php

namespace Webkul\B2BMarketplace\Facades;

use Illuminate\Support\Facades\Facade;

class SupplierBouncer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'supplierbouncer';
    }
}