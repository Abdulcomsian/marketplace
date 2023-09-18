<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\B2BMarketplace\Mail\SupplierVerificationNotification;

/**
 * SignUp Controller controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RegistrationController extends Controller
{
    /**
     * Supplier Repository Object
     *
     * @var object
     */
    protected $supplierRepository;

    /**
     * Supplier Address Repository Object
     *
     * @var object
     */
    protected $supplierAddress;

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
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * save account Addresses.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function storeAddress()
    {
        $data = request()->all();

        $this->validate(request(), [
            'email' => 'email|required|unique:b2b_marketplace_suppliers,email'
        ]);

        return response()->json($data);
    }

    /**
     * Store Account Details.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->input();

        $data['url'] = Str::slug($data['url']);

        $data['password'] = bcrypt($data['password']);

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $data['token'] = md5(uniqid(rand(), true));

        $data['role_id'] = 1;

        if (core()->getConfigData('b2b_marketplace.settings.email.verification')) {
            $data['is_verified'] = 0;
        } else {
            $data['is_verified'] = 1;
        }

        if (core()->getConfigData('b2b_marketplace.settings.general.supplier_approval_required')) {
            $data['is_approved'] = 0;
        } else {
            $data['is_approved'] = 1;
        }

        Event::dispatch('supplier.registration.before');

        $supplier = $this->supplierRepository->create($data);

        if ($supplier) {
            $addressInfo['company_name'] = $data['company_name'];
            $addressInfo['url'] = $data['url'];
            $addressInfo['supplier_id'] = $supplier->id;

            $this->supplierAddress->create($addressInfo);
        }

        Event::dispatch('supplier.registration.after', $supplier);

        if ($data['is_verified']) {
            session()->flash('success', trans('shop::app.customer.signup-form.success'));
        } else {
            session()->flash('info', trans('b2b_marketplace::app.shop.supplier.signup-form.verification'));
        }

        return response()->json($addressInfo);
    }

    /**
     * Check Company Url Available
     *
     * @return \Illuminate\Http\Response
     */
    public function checkShopUrl()
    {
        $supplier = $this->supplierRepository->findOneByField([
            'url' => trim(request()->input('url'))
        ]);

        if (request()->input('url') == '') {
            return response()->json([
                'available' => null
            ]);
        } else {
            return response()->json([
                'available' => $supplier ? false : true
            ]);
        }
    }

    /**
     * Method to verify account
     *
     * @param string $token
     */
    public function verifyAccount($token)
    {
        $supplier = $this->supplierRepository->findOneByField('token', $token);

        if ($supplier) {
            $supplier->update(['is_verified' => 1, 'token' => 'NULL']);

            session()->flash('success', trans('b2b_marketplace::app.shop.supplier.signup-form.verified'));
        } else {
            session()->flash('warning', trans('b2b_marketplace::app.shop.supplier.signup-form.verify-failed'));
        }

        return redirect()->route('b2b_marketplace.shop.supplier.session.index');
    }

    /**
     * Method to resend verification mail
     *
     * @param string $email
     */
    public function resendVerificationEmail($email)
    {
        $verificationData['email'] = $email;
        $verificationData['token'] = md5(uniqid(rand(), true));

        $supplier = $this->supplierRepository->findOneByField('email', $email);

        $this->supplierRepository->update(['token' => $verificationData['token']], $supplier->id);

        try {
            Mail::queue(new SupplierVerificationNotification($supplier));

            if (Cookie::has('enable-resend')) {
                \Cookie::queue(\Cookie::forget('enable-resend'));
            }

            if (Cookie::has('email-for-resend')) {
                \Cookie::queue(\Cookie::forget('email-for-resend'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('shop::app.customer.signup-form.verification-not-sent'));

            return redirect()->back();
        }
        session()->flash('success', trans('shop::app.customer.signup-form.verification-sent'));

        return redirect()->back();
    }
}
