<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Account;

use Webkul\B2BMarketplace\Repositories\TransactionRepository;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;

/**
 * Transaction  Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;


    /**
     * TransactionRepository object
     *
     * @var mixed
     */
    protected $transactionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\TransactionRepository $transactionRepository
     * @return void
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;

        $this->middleware('supplier');

        $this->_config = request('_config');
    }

    /**
     * display the specific supplier's products
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $transaction = $this->transactionRepository->findOrFail($id);

        return view($this->_config['view'], compact('transaction'));
    }
}
