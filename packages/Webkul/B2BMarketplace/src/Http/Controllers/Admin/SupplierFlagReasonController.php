<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Webkul\B2BMarketplace\Repositories\SupplierFlagRepository;
use Webkul\B2BMarketplace\Repositories\SupplierFlagReasonRepository;

class SupplierFlagReasonController extends Controller
{
     /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * supplierFlagReasonRepository instance
     *
     * @var object
     */
    protected $supplierFlagReasonRepository;

    /**
     * supplierFlagRepository instance
     *
     * @var object
     */
    protected $supplierFlagRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\B2BMarketplace\Repositories\SupplierFlagReasonRepository $supplierFlagReasonRepository
     * @param \Webkul\B2BMarketplace\Repositories\SupplierFlagRepository $supplierFlagRepository
     */
    public function __construct(
        SupplierFlagReasonRepository $supplierFlagReasonRepository,
        SupplierFlagRepository $supplierFlagRepository
    )
    {
        $this->_config = request('_config');

        $this->supplierFlagReasonRepository = $supplierFlagReasonRepository;

        $this->supplierFlagRepository = $supplierFlagRepository;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'status' => 'required',
            'reason' => 'required',
        ]);

        $this->supplierFlagReasonRepository->create(request()->all());

        session()->flash('success', trans('b2b_marketplace::app.admin.response.create-success', ['name' => 'Supplier Flag Reason']));

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
        $flagReason  = $this->supplierFlagReasonRepository->find($id);

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

        $flagReason  = $this->supplierFlagReasonRepository->find($id);

        $data  = request()->all();

        $data['status'] = $data['status'] == 'on' ? 1 : 0;

        $flagReason->update($data);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.update-success', ['name' => 'Supplier Flag Reason']));

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
        $this->supplierFlagReasonRepository->delete($id);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Supplier Flag Reason']));

        return response()->json(['message' => true], 200);
    }

    /**
     * To mass delete the customer
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $flagIds = explode(',', request()->input('indexes'));

        foreach ($flagIds as $flagId) {
            $this->supplierFlagRepository->deleteWhere(['id' => $flagId]);
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Supplier Flag Reasons']));

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
            $this->supplierFlagRepository->deleteWhere(['id' => $flagId]);
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Supplier Flags']));

        return redirect()->back();

    }
}
