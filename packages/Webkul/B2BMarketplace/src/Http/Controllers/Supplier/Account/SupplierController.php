<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Account;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\B2BMarketplace\Mail\SupplierVerificationNotification;
use Webkul\B2BMarketplace\Repositories\AllowedSupplierCategoryRepository as SupplierCategoryRepository;
use Webkul\B2BMarketplace\Repositories\CategoryRepository as SupplierCategory;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;

/**
 * Supplier controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierController extends Controller
{
    /*
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /*
    * Supplier Repository
    *
    * @var array
    */
    protected $supplierRepository;

    /*
    * Supplier Address Repository
    *
    * @var array
    */
    protected $supplierAddress;

    /*
    * Supplier Address Repository
    *
    * @var array
    */
    protected $categoryRepository;

    /*
    * Supplier Category
    *
    * @var array
    */
    protected $supplierCategory;

    /**
     * SupplierCategoryRepository object
     *
     * @var object
     */
    protected $supplierCategoryRepository;

    /**
    * Create a new controller instance.
    *
    * @param  Webkul\B2BMarketplace\Repositories\AllowedSupplierCategoryRepository $supplierCategoryRepository
    * @return void
    */
    public function __construct(
       SupplierAddress $supplierAddress,
       SupplierRepository $supplierRepository,
       CategoryRepository $categoryRepository,
       SupplierCategory $supplierCategory,
       SupplierCategoryRepository $supplierCategoryRepository
    )
    {
        $this->supplierAddress = $supplierAddress;

        $this->supplierRepository = $supplierRepository;

        $this->categoryRepository = $categoryRepository;

        $this->supplierCategory = $supplierCategory;

        $this->supplierCategoryRepository = $supplierCategoryRepository;

        $this->_config = request('_config');
    }

    /**
    * display the specified resource.
    *
    * @return $requestedQuote
    */
    public function index()
    {
        $supplier = auth()->guard('supplier')->user();

        $supplier_addresses = $supplier->addresses;

        return view($this->_config['view'])->with([
            'supplier'=> $supplier,
            'supplier_addresses' => $supplier_addresses,
            'defaultCountry' => config('app.default_country')
        ]);
    }

    /**
    * display the specified resource.
    *
    * @return $requestedQuote
    */
    public function passwordChange()
    {
        $supplier = auth()->guard('supplier')->user();

        return view($this->_config['view'])->with([
            'supplier'=> $supplier
        ]);
    }

    /**
    * display the specified resource.
    *
    * @return $requestedQuote
    */
    public function storeNewPassword()
    {
            $isPasswordChanged = false;
            $supplier = auth()->guard('supplier')->user();

            $this->validate(request(), [
                'password'         => 'nullable|min:6|confirmed',
                'current_password' => 'required|min:6',
            ]);

            $data = request()->input();

            if (! Hash::check($data['current_password'], auth()->guard('supplier')->user()->password)) {
                session()->flash('warning', trans('admin::app.users.users.password-match'));

                return redirect()->back();
            }

            if (! $data['password']) {
                unset($data['password']);
            } else {
                $isPasswordChanged = true;
                $data['password'] = bcrypt($data['password']);
            }

            $supplier->update($data);

            if ($isPasswordChanged) {
                session()->flash('success', trans('b2b_marketplace::app.supplier.settings.save-pswd-msg'));
            }

            return back();
    }

    /**
    * display the verification page resource.
    *
    * @return $requestedQuote
    */
    public function show()
    {
        $supplier = auth()->guard('supplier')->user();

        return view($this->_config['view'])->with('supplier', $supplier);
    }

    /**
     * get the supplier categories
     *
     * @return void
     */
    public function getCategories()
    {
        $supplier = auth()->guard('supplier')->user();

        // $allCategories = $this->categoryRepository->get();

        if (core()->getConfigData('b2b_marketplace.settings.supplier_category.allow') == 'ALL') {
            //if all categories are allowed for supplier
            $allCategories = $this->categoryRepository->getModel()->get();
        } else {
            //if specific categories are allowed for supplier
            $supplierCategoryData = $this->supplierCategoryRepository->findOneWhere(['supplier_id' => $supplier->id]);

            if ($supplierCategoryData) {

                $supplierCategory = json_decode($supplierCategoryData->categories);

                $allCategories = $this->categoryRepository->getModel()->whereIn('id', $supplierCategory )->get();
            } else {

                $allCategories = $this->categoryRepository->getModel()::with('ancestors')->where('id', core()->getCurrentChannel()->root_category_id )->get();
            }
        }

        foreach ($allCategories as $category) {

            $category = [
                'key' => $category->id,
                'name' => $category->translation->name,
            ];

            $exist = $this->supplierCategory->findOneWhere(['supplier_id' => $supplier->id, 'category_id' => $category['key']]);

            if (isset ($exist) && $exist != null && $exist->status == 1) {
                $status = 1;
            } else {
                $status = 0;
            }

            $category['status'] = $status;

            $categories[] = $category;
        }

        return view($this->_config['view'])->with(['supplier' => $supplier, 'categories' => $categories]);
    }

    public function storeCategory()
    {
        $categories = request()->all();
        $supplier = auth()->guard('supplier')->user();
        
        if (isset($categories['categories']) && count($categories['categories']) > 0) {

            $existCategories = $this->supplierCategory->findWhere(['supplier_id' => $supplier->id]);
            $existCategoriesIds  = $this->indexedCategoriesId($existCategories);

            $disabledCategories =  array_diff($existCategoriesIds, $categories['categories']);
            $enabledCategories =   array_diff($categories['categories'], $existCategoriesIds);

            foreach ($disabledCategories as $categoryId) {

                $exist = $this->supplierCategory->findOneWhere(['supplier_id' => $supplier->id, 'category_id' => $categoryId]);

                if($exist)
                    $exist->update(['status' => 0]);
            }

            foreach ($categories['categories'] as $categoryId) {

                if(!in_array($categoryId, $disabledCategories)) {

                    $exist = $this->supplierCategory->findOneWhere(['supplier_id' => $supplier->id, 'category_id' => $categoryId]);

                    if($exist) {

                        $exist->update(['status' => 1]);
                    } else {

                        $this->supplierCategory->create(
                            [
                                'supplier_id' => $supplier->id,
                                'category_id' => $categoryId,
                                'status'    => 1
                            ]
                        );
                    }
                }
            }
        } else {

            $categories = $this->supplierCategory->findWhere(['supplier_id' => $supplier->id]);

            if (isset($categories) && count($categories) > 0) {
                foreach ($categories as $category) {
                    $category->update(['status' => 0]);
                }
            }
        }

        session()->flash('success', trans('b2b_marketplace::app.shop.supplier.account.profile.category-success'));

        return back();
    }


    /**
     *
     */
    protected function indexedCategoriesId($existCategories)
    {
        $indexedCategoriesId = [];
        foreach($existCategories as $category) {
            $indexedCategoriesId [] = $category->category_id;
        }

        return $indexedCategoriesId;
    }

    /**
     * resend the verification mail
     *
     * @return void
     */
    public function resendVerification()
    {
        $supplier = auth()->guard('supplier')->user();

        $supplier->token = md5(uniqid(rand(), true));

        $supplier->save();

        try {
            Mail::queue(new SupplierVerificationNotification($supplier));

        } catch (\Exception $e) {
            session()->flash('error', trans('shop::app.customer.signup-form.verification-not-sent'));

            return redirect()->back();
        }

        session()->flash('success', trans('shop::app.customer.signup-form.verification-sent'));

        return redirect()->back();
    }

    /**
    * update the specified resource.
    *
    * @return $requestedQuote
    */
    public function update()
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $data = request()->all();

        if (isset($data['is_newurl'])) {

            if (! $this->supplierRepository->findOneWhere(['id' => $supplierId, 'url' => $data['url']]))
            {
                $this->validate(request(), [
                    'url' => 'required|unique:b2b_marketplace_suppliers,url'
                ]);
            }
        }

        if(!empty($data['profile'])) {
            $imagePath['profile'] = $data['profile'];
            unset($data['profile']);
        } else {
            $imagePath['profile'] = [];
        }

        if(!empty($data['logo'])) {
            $imagePath['logo'] = $data['logo'];
            unset($data['logo']);
        } else {
            $imagePath['logo'] = [];
        }

        if(!empty($data['banner'])) {
            $imagePath['banner'] = $data['banner'];
            unset($data['banner']);
        } else {
            $imagePath['banner'] = [];
        }
       
        unset($data['is_newurl']);

        if (isset($data['url'])) {
            $data['url'] = Str::slug($data['url']);
            $supplier = $this->supplierRepository->findOneWhere(['id' => $supplierId])->update(['url' => $data['url']]);
        }

        $this->supplierRepository->findOneWhere(['id' => $supplierId])->update(['company_name' => $data['company_name']]);


        $supplierAddress = $this->supplierAddress->findOneWhere(['supplier_id' => $supplierId]);

        $supplierAddress->update($data);

        $this->uploadImages($imagePath, $supplierId, 'banner');
    
        $this->uploadImages($imagePath, $supplierId);
        
        $this->uploadImages($imagePath, $supplierId, 'profile');
        
        session()->flash('success', trans('b2b_marketplace::app.shop.supplier.account.profile.profile-success'));

        return back();
    }

    /**
     * @param array $data
     * @param mixed $id
     * @return void
     */
    public function uploadImages($data, $id, $type = "logo")
    {
        $supplier = $this->supplierAddress->findOneWhere(['supplier_id' => $id]);

        if (isset($data[$type])) {

            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'supplier/' . $id;

                if (request()->hasFile($file)) {

                    if ($supplier->{$type}) {

                        Storage::delete($supplier->{$type});
                    }

                    $supplier->{$type} = request()->file($file)->store($dir);

                    $data = $supplier->save();
                }
            }
        } else {
            if ($supplier->{$type}) {

                Storage::delete($supplier->{$type});
            }

            $supplier->{$type} = null;
            $data = $supplier->save();
        }
    }

    /**
     * Summary of SupplierVerificationNotification
     * Getting the supplier info.
     * @return mixed
     */
    public function supplierinfo(){
        $cartItems = request()->all();
        $supplierRepository  = app('Webkul\B2BMarketplace\Repositories\SupplierRepository');
        $productRepository   = app('Webkul\B2BMarketplace\Repositories\ProductRepository');
        $reviewRepository    = app('Webkul\B2BMarketplace\Repositories\ReviewRepository');

        $supplierInfo = [];

        foreach ($cartItems as $cartItem) {
            $supplier = $productRepository->getSupplierByProductId($cartItem['product_id']);

            if(! isset($supplier)){
                $supplier = $productRepository->getSupplierByAssignProductId($cartItem['product_id']);
            }


            if(! isset($supplier)) {
                $supplierInfo[$cartItem['product_id']] = ['supplier' => 0, 'rating'=> 0];
            } else {
                $supplierProduct = $productRepository->getMarketplaceProductByProduct($cartItem['product_id'], $supplier->id);
                $images  = $supplierProduct->images;

                if (count($images) < 1) {
                    $baseProductRepository = app('Webkul\Product\Repositories\ProductRepository')->find($cartItem['product_id']);
                    $images =  productimage()->getGalleryImages( $baseProductRepository);
                }

                if (isset($supplierProduct) && $supplierProduct->is_approved) {
                    $supplierInfo[$cartItem['product_id']] = ['supplier'=> $supplier, 'rating' => $reviewRepository->getAverageRating($supplier)];
                    $supplierInfo[$cartItem['product_id']] = ['supplier'=> $supplier, 'rating' => $reviewRepository->getAverageRating($supplier), 'image' => $images];
                }
            }
        }

        return response()->json($supplierInfo, 200);
    }
}