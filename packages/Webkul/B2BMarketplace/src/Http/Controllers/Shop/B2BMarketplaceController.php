<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;

/**
 * B2B Marketplace Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class B2BMarketplaceController extends Controller
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
}