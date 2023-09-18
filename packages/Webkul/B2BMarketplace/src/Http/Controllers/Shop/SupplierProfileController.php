<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Webkul\Checkout\Facades\Cart;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Mail\ContactSupplierNotification;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;

/**
 * SupplierProfile Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierProfileController extends Controller
{
    /**
     * Supplier Repository Object
     *
     * @var object
     */
    protected $supplierRepository;

    /**
     * Customer Repository Object
     *
     * @var object
     */
    protected $customerRepository;

    /**
     * Customer Repository Object
     *
     * @var object
     */
    protected $customerGroup;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplierRepository
     * @param  Webkul\Customer\Repositories\CustomerRepository        $customerRepository
     * @param  Webkul\Customer\Repositories\CustomerGroupRepository   $customerGroup
     * @param  Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository   $supplierAddress
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        CustomerRepository $customerRepository,
        CustomerGroupRepository $customerGroup,
        SupplierAddress $supplierAddress
    )
    {
        $this->middleware('customer', ['except' => ['show', 'isCustomer', 'index', 'contact']]);

        $this->_config = request('_config');

        $this->supplierRepository = $supplierRepository;

        $this->customerRepository = $customerRepository;

        $this->customerGroup = $customerGroup;

        $this->supplierAddress = $supplierAddress;

    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($url)
    {
        $cartItems = [];
        $supplier = $this->supplierRepository->findByUrlOrFail($url);

        $cart = Cart::getCart();

        if ($cart) {
            $cartItems = $cart->items()->get();
        }

        return view($this->_config['view'], compact('supplier', 'cartItems'));
    }

    /**
     * Populate supplier product page.
     *
     * @param  string  $url
     * @return Mixed
     */
    public function index($url)
    {
        $supplier = $this->supplierRepository->findByUrlOrFail($url);
        
        return view($this->_config['view'], compact('supplier'));
    }

    /**
     * Send query email to seller
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function contact($url)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'query' => 'required',
        ]);

        $supplier = $this->supplierRepository->findByUrlOrFail($url);

        try {
            Mail::send(new ContactSupplierNotification($supplier, request()->all()));
        } catch (\Exception $e) {}

        return response()->json([
                'success' => true,
                'message' => trans('b2b_marketplace::app.shop.account.profile.success')
            ]);
    }
}