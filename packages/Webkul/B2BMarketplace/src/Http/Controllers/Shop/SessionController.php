<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Cookie;

/**
 * session controller for suppiler
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SessionController extends Controller
{
    /**
     * create controller instance
     *
     * @return illuminate\http\response
     */
    protected $_config;

    /**
     * Supplier Repository Object
     *
     * @var object
     */
    protected $customerRepository;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplierRepository
     * @param  Webkul\Customer\Repositories\CustomerRepository        $customerRepository
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        SupplierRepository $supplierRepository
    )
    {
        $this->middleware('supplier')->except(['index','create']);

        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;

        $this->supplierRepository = $supplierRepository;
    }

    /**
     * show login page
     *
     * @return void
     */
    public function index()
    {
        return auth()->guard('supplier')->check()
            ? redirect()->route('b2b_marketplace.supplier.dashboard.index')
            : view($this->_config['view']);

    }

    /**
     * perform login supplier
     *
     * @return void
     */
    public function create(Request $request)
    {
        if (auth()->guard('supplier')->check()) {
            return redirect()->route('b2b_marketplace.supplier.dashboard.index');
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (! auth()->guard('supplier')->attempt(request(['email', 'password']))) {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

            return redirect()->back();
        }

        if (auth()->guard('supplier')->user()->is_approved == 0) {

            session()->flash('info', trans('b2b_marketplace::app.shop.supplier.login-form.no-approval'));

            return redirect()->back();
        }

        Event::dispatch('supplier.after.login', $request->input('email'));

        return redirect()->intended(route($this->_config['redirect']));
    }

    /**
     * logout supplier
     *
     * @return void
     */
    public function destroy($id)
    {
        auth()->guard('supplier')->logout();

        Event::dispatch('supplier.after.logout', $id);

        return redirect()->route($this->_config['redirect']);
    }
}
