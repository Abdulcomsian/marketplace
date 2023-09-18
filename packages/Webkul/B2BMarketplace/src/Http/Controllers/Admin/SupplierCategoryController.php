<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\AllowedSupplierCategoryRepository as SupplierCategoryRepository;

/**
 * Supplier's Category Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierCategoryController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SupplierCategoryRepository object
     *
     * @var object
     */
    protected $supplierCategoryRepository;


    /**
     * SupplierRepository object
     *
     * @var object
     */
    protected $supplierRepository;


    /**
     * CategoryRepository object
     *
     * @var object
     */
    protected $categoryRepository;

       /**
     * Create a new controller instance.
     *
     * @param  Webkul\Category\Repositories\CategoryRepository $categoryRepository
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\AllowedSupplierCategoryRepository $supplierCategoryRepository
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        CategoryRepository $categoryRepository,
        supplierCategoryRepository $supplierCategoryRepository
    )
    {
        $this->_config = request('_config');

        $this->supplierRepository = $supplierRepository;

        $this->categoryRepository = $categoryRepository;

        $this->supplierCategoryRepository = $supplierCategoryRepository;

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
        $suppliers = $this->supplierRepository->all();

        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('suppliers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->all();

        $data['categories'] = json_encode($data['categories']);

        $supplierCategories = $this->supplierCategoryRepository->findOneByField('supplier_id',$data['supplier_id']);

        if($supplierCategories){
            $a1 = json_decode($data['categories']);
            $a2 = json_decode($supplierCategories->categories);
            $data['categories'] = json_encode(array_unique(array_merge($a1,$a2), SORT_REGULAR));

            $supplierCategories->update($data);

            session()->flash('success', __('b2b_marketplace::app.admin.suppliers.category.update-success'));
        }
        else{
            $this->supplierCategoryRepository->create($data);

            session()->flash('success', __('b2b_marketplace::app.admin.suppliers.category.save-success'));
        }
        return redirect()->route('b2b_marketplace.admin.supplier.category.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $supplierCategories = $this->supplierCategoryRepository->find($id);

        $suppliers = $this->supplierRepository->all();

        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('suppliers', 'categories', 'supplierCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supplierCategories = $this->supplierCategoryRepository->find($id);

        $supplierCategories->update(request()->all());

        session()->flash('success', __('b2b_marketplace::app.admin.suppliers.category.update-success'));

        return redirect()->route('b2b_marketplace.admin.supplier.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $product = $this->supplierCategoryRepository->findOrFail($id);

        try {
            $this->supplierCategoryRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'supplier Category']));

            return response()->json(['message' => true], 200);
        } catch (Exception $e) {
            report($e);

            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'supplier Category']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $data = request()->all();

        $indexs = explode(",",$data['indexes']);

        foreach($indexs as $id) {

            $supplierCategories = $this->supplierCategoryRepository->find($id);

            $supplierCategories->delete();
        }

        session()->flash('success', __('b2b_marketplace::app.admin.suppliers.category.delete-success'));

        return redirect()->route('b2b_marketplace.admin.supplier.category.index');
    }

}
