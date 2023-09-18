<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Account;

use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;

/**
 * Supplier Review Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * SupplierRepository object
     *
     * @var object
    */
    protected $supplierRepository;


    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplier
     * @return void
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->_config = request('_config');

        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Method to populate supplier review page which will be populated.
     *
     * @return Mixed
     */
    public function index($url)
    {
        $isSupplier = auth()->guard('supplier')->user()->id;

        if (! $isSupplier) {
            return redirect()->route('b2b_marketplace.shop.supplier.session.index');
        }

        return view($this->_config['view']);
    }
}
