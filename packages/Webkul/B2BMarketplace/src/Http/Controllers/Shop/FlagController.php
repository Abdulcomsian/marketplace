<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop;

use Mail;

use Webkul\User\Repositories\AdminRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\ProductFlagRepository;
use Webkul\B2BMarketplace\Repositories\SupplierFlagRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;

use Webkul\B2BMarketplace\Mail\Flag\NewReportSupplierNotification;
use Webkul\B2BMarketplace\Mail\Flag\NewAdminReportProductNotification;
use Webkul\B2BMarketplace\Mail\Flag\NewAdminReportSupplierNotification;
use Webkul\B2BMarketplace\Mail\Flag\NewSupplierReportProductNotification;

/**
 * B2B Marketplace flag controller
 *
 * @author    Naresh verma<naresh.verma327@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class FlagController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * productFlagRepository object
     *
     *  @var object
     */
    protected $productFlagRepository;

    /**
     * productRepository object
     *
     *  @var object
     */
    protected $productRepository;

    /**
     * userRepository object
     *
     *  @var object
     */
    protected $adminRepository;

    /**
     * SupplierFlagRepository object
     *
     * @var object
     */
    protected $supplierFlagRepository;

    /**
     * SupplierRepository object
     *
     * @var object
     */
    protected $supplierRepository;

    /**
     * Supplier Product Repository object
     *
     * @var object
     */
    protected $supplierProduct;
    /**
     * Create a new controller instance.
     *
     *  @param Webkul\User\Repositories\AdminRepository $adminRepository
     *  @param Webkul\B2BMarketplace\Repositories\ProductRepository      $supplierProduct
     *  @param Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplierRepository
     *  @param Webkul\B2BMarketplace\Repositories\ProductFlagRepository  $productFlagRepository
     *  @param Webkul\B2BMarketplace\Repositories\SupplierFlagRepository $supplierFlagRepository
     *  @param Webkul\Product\Repositories\ProductRepository             $productRepository
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        SupplierFlagRepository $supplierFlagRepository,
        ProductFlagRepository $productFlagRepository,
        ProductRepository $productRepository,
        SupplierProduct $supplierProduct,
        AdminRepository $adminRepository
    )
    {
        $this->_config = request('_config');

        $this->supplierRepository = $supplierRepository;

        $this->productFlagRepository = $productFlagRepository;

        $this->supplierFlagRepository = $supplierFlagRepository;

        $this->productRepository = $productRepository;

        $this->supplierProduct = $supplierProduct;

        $this->adminRepository = $adminRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function productFlagstore()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required',
            'product_id' => 'required'
        ]);

        $flag = $this->productFlagRepository->findOneByField(['email'=>request()->email,'product_id'=>request()->product_id]);

        $data = request()->all();

        if(!$flag){

            if (isset($data['selected_reason']) && $data['reason'] == null) {
                $data['reason'] = $data['selected_reason'];
            }

            if (request()->supplier_id != 0) {
                $supplier = $this->supplierRepository->find(request()->supplier_id);
                $product = $this->supplierProduct->findOneByField('product_id',request()->product_id)->product;
            } else {
                $supplier = Null;
                $product = $this->productRepository->findOneByField('id',request()->product_id)->product;
            }

            $admin = $this->adminRepository->findOneWhere(['role_id' => 1]);

            $this->productFlagRepository->create($data);

            try {

                Mail::send(new NewAdminReportProductNotification($supplier, $admin, $product, $data));

                if (request()->supplier_id != 0) {
                    Mail::send(new NewSupplierReportProductNotification($supplier, $product, $data));
                }

            } catch (\Exception $e) {
                report($e);
                session()->flash('warning', session()->flash('error', trans('b2b_marketplace::app.shop.account.flag.went-wrong')));
            }

            if (isset($data['selected_reason'])) {
                return response()->json([
                    'success' => true,
                    'message' => trans('b2b_marketplace::app.shop.account.flag.create-success', ['name' => 'Product'])
                ]);
            } else {
                session()->flash('success', trans('b2b_marketplace::app.shop.account.flag.create-success', ['name' => 'Product']));
                return redirect()->back();
            }
        } else{

            if (isset($data['selected_reason'])) {
                return response()->json([
                    'success' => false,
                    'message' => trans('b2b_marketplace::app.shop.account.flag.already-reported', ['name' => 'Product'])
                ]);
            } else {
                session()->flash('warning', trans('b2b_marketplace::app.shop.account.flag.already-reported', ['name' => 'Product']));
                return redirect()->back();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplierFlagstore()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required',
            'supplier_id' => 'required'
        ]);

        $data = request()->all();

        if (isset($data['selected_reason']) && $data['reason'] == null) {
            $data['reason'] = $data['selected_reason'];
        }

        $data['subject'] = 'Report Supplier';
        $data['query'] = $data['reason'];

        $flag = $this->supplierFlagRepository->findOneByField(['email'=>request()->email,'supplier_id'=>request()->supplier_id]);

        if($flag) {
            if (isset($data['selected_reason'])) {
                return response()->json([
                    'success' => false,
                    'message' => trans('b2b_marketplace::app.shop.account.flag.already-reported', ['name' => 'Supplier'])
                ]);
            } else {
                session()->flash('warning', trans('b2b_marketplace::app.shop.account.flag.already-reported', ['name' => 'Supplier']));
                return redirect()->back();
            }
        } else {
            $supplier = $this->supplierRepository->find($data['supplier_id']);
            $admin = $this->adminRepository->findOneWhere(['role_id' => 1]);

            $this->supplierFlagRepository->create($data);

            try {

                Mail::send(new NewReportsupplierNotification($supplier, $data));
                Mail::send(new NewAdminReportSupplierNotification($supplier,$admin, $data));
            } catch (\Exception $e) {
                report($e);
            }

            if (isset($data['selected_reason'])) {
                return response()->json([
                    'success' => true,
                    'message' => trans('b2b_marketplace::app.shop.account.flag.create-success', ['name' => 'Supplier'])
                ]);
            } else {
                session()->flash('success', trans('b2b_marketplace::app.shop.account.flag.create-success', ['name' => 'Supplier']));
                return redirect()->back();
            }
        }

        return redirect()->back();
    }
}