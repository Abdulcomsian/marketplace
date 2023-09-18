<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;

class AssignProductController extends Controller
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
     * @var object
    */
    protected $supplier;

    /**
     * ProductRepository object
     *
     * @var object
     */
    protected $product;

    /**
     * Supplier ProductRepository object
     *
     * @var object
     */
    protected $supplierProduct;
    /**
     * AttributeFamilyRepository object
     *
     * @var object
     */
    protected $attributeFamily;

    /**
     * CategoryRepository object
     *
     * @var object
     */
    protected $category;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamily
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplier
     * @param  Webkul\Product\Repositories\ProductRepository           $product
     * @param  Webkul\Category\Repositories\CategoryRepository         $category
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository $inventorySource
     * @return void
     */
    public function __construct(
        AttributeFamily $attributeFamily,
        Product $product,
        SupplierProduct $supplierProduct,
        Category $category,
        InventorySource $inventorySource
    )
    {
        $this->attributeFamily = $attributeFamily;

        $this->product = $product;

        $this->supplierProduct = $supplierProduct;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->_config = request('_config');
    }


    /**
     * Display product search page.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        if (request()->input('query')) {
            $results = [];

            foreach ($this->supplierProduct->searchProducts(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->product_id,
                        'sku' => $row->sku,
                        'name' => $row->name,
                        'price' => core()->convertPrice($row->price),
                        'formated_price' => core()->currency($row->price),
                        'base_image' => $row->product->base_image_url,
                    ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $supplier = auth()->guard('supplier')->user()->id;

        $product = $this->supplierProduct->findOneWhere([
                        'product_id' => $id,
                        'supplier_id' => $supplier,
                    ]);

        if ($product) {
            session()->flash('error', trans('b2b_marketplace::app.supplier.account.products.already-selling'));

            return redirect()->route('b2b_marketplace.supplier.catalog.products.search');
        }

        $baseProduct = $this->product->find($id);

        if ($baseProduct->type != "simple" && $baseProduct->type != "configurable") {
            session()->flash('error', trans('b2b_marketplace::app.supplier.account.products.not-allowed', ['product' => $baseProduct->type]));

            return redirect()->route('b2b_marketplace.supplier.catalog.products.search');
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('baseProduct', 'inventorySources'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $this->validate(request(), [
            'condition' => 'required',
            'description' => 'required',
        ]);

        $data = array_merge(request()->all(), [
                'product_id' => $id,
                'is_owner' => 0,
            ]);

        $this->supplierProduct->createAssign($data);

        session()->flash('success', trans('b2b_marketplace::app.supplier.account.products.create-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->supplierProduct->findorFail($id);

        if ($product->parent) {
            return redirect()->route('b2b_marketplace.account.products.edit-assign', ['id' => $product->parent->id]);
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('product', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'condition' => 'required',
            'description' => 'required'
        ]);

        $data = request()->all();

        $this->supplierProduct->updateAssign($data, $id);

        session()->flash('success', trans('b2b_marketplace::app.supplier.account.products.update-success'));

        return redirect()->route($this->_config['redirect']);
    }
}