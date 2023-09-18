<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\B2BMarketplace\Repositories\ProductInventoryRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;
use Webkul\B2BMarketplace\Repositories\AllowedSupplierCategoryRepository as SupplierCategoryRepository;

/**
 * Supplier Product's Controller
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
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
     * ProductInventoryRepository object
     *
     * @var object
     */
    protected $productInventoryRepository;

    /**
     * SupplierCategoryRepository object
     *
     * @var object
     */
    protected $supplierCategoryRepository;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository $attributeFamily
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplier
     * @param  Webkul\Product\Repositories\ProductRepository           $product
     * @param  Webkul\Category\Repositories\CategoryRepository         $category
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository $inventorySource
     * @param  Webkul\B2BMarketplace\Repositories\AllowedSupplierCategoryRepository $supplierCategoryRepository
     * @return void
     */
    public function __construct(
        AttributeFamily $attributeFamily,
        Product $product,
        SupplierProduct $supplierProduct,
        Category $category,
        InventorySource $inventorySource,
        ProductInventoryRepository $productInventoryRepository,
        SupplierCategoryRepository $supplierCategoryRepository
    ) {
        $this->attributeFamily = $attributeFamily;

        $this->product = $product;

        $this->supplierProduct = $supplierProduct;

        $this->category = $category;

        $this->inventorySource = $inventorySource;

        $this->productInventoryRepository = $productInventoryRepository;

        $this->supplierCategoryRepository = $supplierCategoryRepository;

        $this->_config = request('_config');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(\Webkul\B2BMarketplace\DataGrids\Supplier\ProductDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Copy a given Product.
     */
    public function copy(int $productId)
    {
        $originalProduct = $this->product->findOrFail($productId);

        $supplier = $this->supplierProduct->getSupplierByProductId($originalProduct->id);

        if (!$supplier) {
            session()->flash('error', trans('b2b_marketplace::app.supplier.account.products.copy-error'));

            return redirect()->to(route('b2b_marketplace.supplier.catalog.products.index'));
        }

        if (!$originalProduct->getTypeInstance()->canBeCopied()) {
            session()->flash(
                'error',
                trans('admin::app.response.product-can-not-be-copied', [
                    'type' => $originalProduct->type,
                ])
            );

            return redirect()->to(route('b2b_marketplace.supplier.catalog.products.index'));
        }

        if ($originalProduct->parent_id) {
            session()->flash(
                'error',
                trans('admin::app.catalog.products.variant-already-exist-message')
            );

            return redirect()->to(route('b2b_marketplace.supplier.catalog.products.index'));
        }

        $copiedProduct = $this->product->copy($originalProduct);

        if ($copiedProduct instanceof ProductModel && $copiedProduct->id) {
            session()->flash('success', trans('admin::app.response.product-copied'));
        } else {
            session()->flash('error', trans('admin::app.response.error-while-copying'));
        }

        $supplierProduct = $this->supplierProduct->create([
            'product_id' => $copiedProduct->id,
            'is_owner' => 1
        ]);

        return redirect()->to(route('b2b_marketplace.supplier.catalog.products.edit', ['id' => $copiedProduct->id]));
    }

    /**
     * Create Product Supplier
     *
     * @return void
     */
    public function create()
    {
        $families = $this->attributeFamily->all();

        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamily->find($familyId);
        }

        return view($this->_config['view'], compact('families', 'configurableFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (!request()->get('family') && request()->input('type') == 'configurable' && request()->input('sku') != '') {
            return redirect(url()->current() . '?family=' . request()->input('attribute_family_id') . '&sku=' . request()->input('sku'));
        }

        if (request()->input('type') == 'configurable' && (!request()->has('super_attributes') || !count(request()->get('super_attributes')))) {

            session()->flash('error', trans('b2b_marketplace::app.supplier.account.products.attribute-error'));

            return back();
        }

        $this->validate(request(), [
            'type' => 'required',
            'attribute_family_id' => 'required',
            'sku' => ['required', 'unique:products,sku', new \Webkul\Core\Contracts\Validations\Slug]
        ]);

        $product = $this->product->create(request()->all());

        $supplierProduct = $this->supplierProduct->create([
            'product_id' => $product->id,
            'is_owner' => 1
        ]);

        session()->flash('success', trans('b2b_marketplace::app.supplier.account.products.create-success'));

        return redirect()->route($this->_config['redirect'], ['id' => $product->id]);
    }

    /**
     * Edit Specific Supplier Product
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        $supplier = auth()->guard('supplier')->user();

        $supplierProduct = $this->supplierProduct->findOneWhere([
            'product_id' => $id,
            'supplier_id' => $supplier->id,
            'is_owner' => 0
        ]);

        if ($supplierProduct) {

            return redirect()->route('b2b_marketplace.account.products.edit-assign', ['id' => $supplierProduct->id]);
        }

        $product = $this->product->with(['variants', 'variants.inventories'])->findOrFail($id);

        // $categories = $this->category->getCategoryTree();

        if (core()->getConfigData('b2b_marketplace.settings.supplier_category.allow') == 'ALL') {
            //if all categories are allowed for supplier
            $categories = $this->category->getModel()->get();
        } else {
            //if specific categories are allowed for supplier
            $supplierCategoryData = $this->supplierCategoryRepository->findOneWhere(['supplier_id' => $supplier->id]);

            if ($supplierCategoryData) {

                $supplierCategory = json_decode($supplierCategoryData->categories);

                $categories = $this->category->getModel()->whereIn('id', $supplierCategory)->get();
            } else {

                $categories = $this->category->getModel()::with('ancestors')->where('id', core()->getCurrentChannel()->root_category_id)->get();
            }
        }

        $inventorySources = $this->inventorySource->all();

        return view($this->_config['view'], compact('product', 'categories', 'inventorySources'));
    }

    /**
     * update Specific Supplier Product
     *
     * @return void
     */
    public function update($id)
    {
        $data = request()->all();

        if (!isset($data['status'])) {
            $data['status'] = 0;
        }

        if (!isset($data['new'])) {
            $data['new'] = 0;
        }

        if (!isset($data['featured'])) {
            $data['featured'] = 0;
        }

        if (!isset($data['visible_individually'])) {
            $data['visible_individually'] = 0;
        }

        if (!isset($data['guest_checkout'])) {
            $data['guest_checkout'] = 0;
        }

        $this->supplierProduct->updateSupplierProduct($data, $id);

        $supplierProduct = $this->supplierProduct->getMarketplaceProductByProduct($id);

        $this->supplierProduct->update(request()->all(), $supplierProduct->id);

        session()->flash('success', session()->flash('success', trans('b2b_marketplace::app.supplier.account.products.update-success')));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->findOrFail($id);

        $productInventory = $this->productInventoryRepository->findWhere([
            'vendor_id' => auth()->guard('supplier')->user()->id,
            'product_id' => $id
        ]);

        try {

            $supplierProduct = $this->supplierProduct->getMarketplaceProductByProduct($id);

            $this->supplierProduct->delete($supplierProduct->id);

            foreach ($productInventory as $inventory) {
                $inventory->delete();
            }
            if ($supplierProduct->is_owner == 1) {

                $this->product->delete($supplierProduct->product_id);
            }

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Product']));
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Product']));
        }

        return response()->json(['message' => true], 200);
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {

            $supplierProduct = $this->supplierProduct->getMarketplaceProductByProduct($productId);


            if ($supplierProduct) {
                if ($supplierProduct->is_owner == 1) {

                    $this->supplierProduct->delete($supplierProduct->id);
                    $this->product->delete($supplierProduct->product_id);
                } else {

                    $productInventory = $this->productInventoryRepository->findWhere(['vendor_id' => 0, 'supplier' => auth()->guard('supplier')->user()->id, 'product_id' => $productId]);

                    foreach ($productInventory as $inventory) {
                        $inventory->delete();
                    }

                    $this->supplierProduct->delete($supplierProduct->id);
                }
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Result of search product.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function productLinkSearch()
    {
        if (request()->ajax()) {
            $results = [];

            foreach ($this->supplierProduct->searchProductByAttribute(request()->input('query')) as $row) {
                $results[] = [
                    'id'   => $row->product_id,
                    'sku'  => $row->sku,
                    'name' => $row->name,
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }
}
