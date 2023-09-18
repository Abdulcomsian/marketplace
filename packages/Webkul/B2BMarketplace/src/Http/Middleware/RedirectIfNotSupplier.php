<?php

namespace Webkul\B2BMarketplace\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfNotSupplier
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string|null  $guard
    * @return mixed
    */
    public function handle($request, Closure $next, $guard = 'supplier')
    {
        if (! Auth::guard($guard)->check()) {
            return redirect()->route('b2b_marketplace.shop.supplier.session.index');
        } else {

            if (Auth::guard($guard)->user()->is_approved == 0) {
                Auth::guard($guard)->logout();

                session()->flash('warning', trans('b2b_marketplace::app.shop.supplier.login-form.unapprove'));
                return redirect()->route('b2b_marketplace.shop.supplier.session.index');
            }

            $this->checkIfAuthorized($request);
        }

        return $next($request);
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return mixed
    */
    public function checkIfAuthorized($request)
    {
        if (! $role = auth()->guard('supplier')->user()->role) {
            abort(401, 'This action is unauthorized.');
        }

        if ($role->permission_type == 'all') {
            return;
        } else {
            $acl = app('supplier-acl');

            if ($acl && isset($acl->roles[Route::currentRouteName()])) {
                app()->make(\Webkul\B2BMarketplace\Bouncer::class)->allow($acl->roles[Route::currentRouteName()]);
            }
        }
    }

}
