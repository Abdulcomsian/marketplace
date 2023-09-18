<?php
namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;

/**
 * Supplier's Customer controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SupplierRepository object
     *
     * @var mixed
     */
    protected $supplierRepository;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        OrderRepository $orderRepository
    )
    {
        $this->supplierRepository = $supplierRepository;

        $this->orderRepository = $orderRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isSupplier = auth()->guard('supplier')->check();

        if (! $isSupplier) {
            return redirect()->route('marketplace.account.seller.create');
        }

        return view($this->_config['view']);
    }
}
