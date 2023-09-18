<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;


class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function isSupplier()
    {
        $sellerRepository = app()->make('Webkul\B2bMarketplace\Repositories\SupplierRepository');

        $isSupplier = $sellerRepository->isSupplier(auth()->guard('customer')->user()->id);

        return  $isSupplier ? true : false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToLogin()
    {
        return redirect()->route('b2b_marketplace.shop.supplier.session.create');
    }
}
