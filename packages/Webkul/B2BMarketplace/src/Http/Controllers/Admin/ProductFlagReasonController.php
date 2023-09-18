<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Webkul\B2BMarketplace\Repositories\ProductFlagReasonRepository;
use Webkul\B2BMarketplace\Repositories\ProductFlagRepository;

class ProductFlagReasonController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductFlagReasonRepository instance
     *
     * @var object
     */
    protected $productFlagReasonRepository;

    /**
     * ProductFlagRepository instance
     *
     * @var object
     */
    protected $productFlagRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\B2BMarketplace\Repositories\ProductFlagReasonRepository $productFlagReasonRepository
     * @param \Webkul\B2BMarketplace\Repositories\ProductFlagRepository $productFlagRepository
     */
    public function __construct(
        ProductFlagReasonRepository $productFlagReasonRepository,
        ProductFlagRepository $productFlagRepository
    )
    {
        $this->_config = request('_config');

        $this->productFlagReasonRepository = $productFlagReasonRepository;

        $this->productFlagRepository = $productFlagRepository;

        $this->middleware('admin');

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'status' => 'required',
            'reason' => 'required',
        ]);

        $this->productFlagReasonRepository->create(request()->all());

        session()->flash('success', trans('b2b_marketplace::app.admin.response.create-success', ['name' => 'Product Flag Reason']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $flagReason  = $this->productFlagReasonRepository->find($id);

        return view($this->_config['view'], compact('flagReason'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'status' => 'required',
            'reason' => 'required',
        ]);

        $flagReason  = $this->productFlagReasonRepository->find($id);

        $data  = request()->all();

        $data['status'] = $data['status'] == 'on' ? 1 : 0;

        $flagReason->update($data);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.update-success', ['name' => 'Product Flag Reason']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->productFlagReasonRepository->delete($id);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Product Flag Reason']));

        return response()->json(['message' => true], 200);
    }

    /**
     * mass delete the reason
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $flagReasonIds = explode(',', request()->input('indexes'));

        foreach ($flagReasonIds as $flagReasonId) {
            $this->productFlagReasonRepository->deleteWhere(['id' => $flagReasonId]);
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Product Flag Reasons']));

        return redirect()->back();

    }


    /**
     * mass delete the flag
     *
     * @return \Illuminate\Http\Response
     */
    public function flagMassDelete()
    {
        $flagIds = explode(',', request()->input('indexes'));

        foreach ($flagIds as $flagId) {
            $this->productFlagRepository->deleteWhere(['id' => $flagId]);
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Product Flags']));

        return redirect()->back();

    }
}
