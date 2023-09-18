<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Webkul\B2BMarketplace\Repositories\ReviewRepository;

/**
 * Supplier Review  Controller
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
     * ReviewRepository object
     *
     * @var object
    */
    protected $reviewRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\ReviewRepository $reviewRepository
     * @return void
     */
    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;

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
     * Mass updates the Reviews
     *
     * @return response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type']) || !$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $reviewIds = explode(',', $data['indexes']);

        foreach ($reviewIds as $reviewId) {

            $this->reviewRepository->update([
                    'status' => $data['update-options']
                ], $reviewId);
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.reviews.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }
}
