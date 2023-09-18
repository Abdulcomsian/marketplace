<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

/**
* Supplier Message's Controller
*
* @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
*/
class MessageController extends Controller
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
     * @var array
    */
    protected $supplier;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

}