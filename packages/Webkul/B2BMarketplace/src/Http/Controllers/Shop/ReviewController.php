<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\ReviewRepository;

/**
 * B2BMarketplace review controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
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
     * @var object
    */
    protected $supplier;

    /**
     * ReviewRepository object
     *
     * @var object
    */
    protected $review;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplier
     * @param  Webkul\B2BMarketplace\Repositories\ReviewRepository $review
     * @return void
     */
    public function __construct(
        ReviewRepository $review,
        SupplierRepository $supplier
    )
    {
        $this->_config = request('_config');

        $this->supplier = $supplier;

        $this->review = $review;
    }

    /**
     * Method to populate the supplier review page which will be populated.
     *
     * @param  string  $url
     * @return Mixed
     */
    public function index($url)
    {
        $supplier = $this->supplier->findByUrlOrFail($url);

        return view($this->_config['view'], compact('supplier'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function create($url)
    {
        $supplier = $this->supplier->findByUrlOrFail($url);

        return view($this->_config['view'], compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function store($url)
    {
        $supplier = $this->supplier->findByUrlOrFail($url);

        $this->validate(request(), [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required'
        ]);

        $data = request()->all();

        $data['status'] = 'pending';
        $data['supplier_id'] = $supplier->id;
        $data['customer_id'] = auth()->guard('customer')->user()->id;

        $this->review->create($data);

        session()->flash('success', trans('b2b_marketplace::app.shop.account.review.create-success'));

        return redirect()->route($this->_config['redirect'], ['url' => $url]);
    }
}