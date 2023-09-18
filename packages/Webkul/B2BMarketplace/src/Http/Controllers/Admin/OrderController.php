<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\B2BMarketplace\Repositories\TransactionRepository;

/**
 * B2BMarketplace Order controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var object
    */
    protected $orderRepository;

    /**
     * TransactionRepository object
     *
     * @var object
    */
    protected $transactionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\B2BMarketplace\Repositories\TransactionRepository $transactionRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->transactionRepository = $transactionRepository;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the supplier order page.
     *
     * @return Mixed
     */
    public function index($url)
    {
        return view($this->_config['view']);
    }

    /**
     * Pay seller
     *
     * @return Mixed
     */
    public function pay()
    {
        if ($this->transactionRepository->paySupplier(request()->all())) {
            session()->flash('success', trans('b2b_marketplace::app.admin.orders.payment-success-msg'));
        }

        return redirect()->back();
    }
}