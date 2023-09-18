<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
}
