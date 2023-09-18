<?php

namespace Webkul\B2BMarketplace\Http\Middleware;

use Closure;

class B2BMarketplaceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!core()->getConfigData('b2b_marketplace.settings.general.status')) {

            $message = core()->getConfigData('b2b_marketplace.settings.general.message') ? : __('b2b_marketplace::app.admin.system.status-message');

            session()->flash('warning', $message);

            abort(401, 'This action is unauthorized.');
        }

        return $next($request);
    }
}