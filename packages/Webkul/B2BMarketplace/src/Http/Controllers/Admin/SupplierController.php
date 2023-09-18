<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Mail\SupplierApprovalNotification;
use Illuminate\Support\Facades\Mail;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;
use Webkul\Product\Repositories\ProductRepository as Product;
use Illuminate\Support\Facades\Event;
use Webkul\B2BMarketplace\Mail\NewSupplierNotificationFromAdmin;
use Webkul\B2BMarketplace\Repositories\RoleRepository;

/**
 * Admin Supplier's Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SupplierRepository $supplierRepository,
        protected SupplierProduct $supplierProduct,
        protected Product $product,
        protected SupplierAddress $supplierAddress,
        protected RoleRepository $roleRepository
    )
    {
        $this->_config = request('_config');
    }

    /**
     * display the specific supplier's resource
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the supplier roles.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = $this->roleRepository->all();

        return view($this->_config['view'], compact('roles'));
    }

    /**
     * Edit the specific resource
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $roles = $this->roleRepository->all();

        $supplier = $this->supplierRepository->findOneWhere(['id' => $id]);

        return view($this->_config['view'])->with(['supplier' => $supplier, 'roles' => $roles]);
    }

    /**
     * Show the supplier roles.
     *
     * @return \Illuminate\View\View
     */
    public function store()
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'email'         => 'required|unique:b2b_marketplace_suppliers,email',
            'url'         => 'required|unique:b2b_marketplace_suppliers,url',
        ]);

        $data = request()->all();

        $password = rand(100000, 10000000);

        $data['password'] = bcrypt($password);

        $data['channel_id'] = core()->getCurrentChannel()->id;

        $data['token'] = md5(uniqid(rand(), true));

        $data['is_verified'] = 1;

        $data['is_approved'] = 1;

        Event::dispatch('admin.supplier.registration.before');

        $supplier = $this->supplierRepository->create($data);

        if ($supplier) {
            $addressInfo['company_name'] = $data['company_name'];
            $addressInfo['url'] = $data['url'];
            $addressInfo['supplier_id'] = $supplier->id;

            $this->supplierAddress->create($addressInfo);
        }

        Event::dispatch('admin.supplier.registration.after', $supplier);

        try {

            Mail::queue(new NewSupplierNotificationFromAdmin($supplier, $password));
        } catch (\Exception $e) {
            report($e);
        }

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Supplier']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Update the specific resource
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $data = request()->except('_token', '_method');

        $this->supplierRepository->update($data, $id);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.update-success', ['name' => 'Supplier']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * delete the specific resource
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $this->supplierRepository->delete($id);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Supplier']));

        return response()->json(['message' => true], 200);
    }

    /**
     * Mass Delete the Supplier
     *
     * @return \response
     */
    public function massDestroy()
    {
        $supplierIds = explode(',', request()->input('indexes'));

        foreach ($supplierIds as $supplierId) {
            $this->supplierRepository->delete($supplierId);
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.suppliers.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the suppliers
     *
     * @return \response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type']) || !$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $supplierIds = explode(',', $data['indexes']);

        foreach ($supplierIds as $supplierId) {

            if ($data['update-options']) {

                $this->supplierRepository->update([
                    'is_approved' => $data['update-options']
                ], $supplierId);

                $supplier = $this->supplierRepository->find($supplierId);

                try {
                    Mail::send(new SupplierApprovalNotification($supplier));
                } catch (\Exception $e) {

                }
            } else if ($data['update-options'] == 0) {
                $this->supplierRepository->update([
                    'is_approved' => $data['update-options']
                ], $supplierId);
            }
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.supplier.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $supplierId
     * @return \Illuminate\Http\JsonResponse
     * @return \Illuminate\View\View
     */
    public function search($id)
    {
        if (request()->input('query')) {
            $results = [];

            foreach ($this->supplierProduct->searchProducts(request()->input('query')) as $row) {
                $results[] = [
                        'id' => $row->product_id,
                        'sku' => $row->sku,
                        'name' => $row->name,
                        'price' => core()->convertPrice($row->price),
                        'formated_price' => core()->currency(core()->convertPrice($row->price)),
                        'base_image' => $row->product->base_image_url,
                    ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view'], compact('id'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $supplierId,  $productId
     * @return \Illuminate\View\View
     */
    public function assignProduct($supplierId, $productId)
    {
        $product = $this->supplierProduct->findOneWhere([
            'product_id' => $productId,
            'supplier_id' => $supplierId
        ]);

        if ($product) {
            session()->flash('error', trans('b2b_marketplace::app.admin.supplier.already-selling'));

            return redirect()->route('b2b_marketplace.admin.suppliers.index');
        }

        $baseProduct = $this->product->find($productId);

        if ($baseProduct->type != "simple" && $baseProduct->type != "configurable") {

            session()->flash('success', trans('b2b_marketplace::app.admin.supplier.not-allowed', ['product' => $baseProduct->type]));

            return redirect()->route('b2b_marketplace.admin.suppliers.index');
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view($this->_config['view'], compact('baseProduct', 'inventorySources'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $supplierId,  $productId
     * @return \Illuminate\Http\Response
     */
    public function saveAssignProduct($supplierId, $productId)
    {
        $this->validate(request(), [
            'condition' => 'required',
            'description' => 'required'
        ]);

        $data = array_merge(request()->all(), [
            'product_id' => $productId,
            'is_owner' => 0,
            'supplier_id' => $supplierId,
        ]);

        $product = $this->supplierProduct->createAssign($data);

        $product->update(['is_approved' => 1]);

        session()->flash('success', trans('b2b_marketplace::app.admin.products.assigned-msg'));

        return redirect()->route($this->_config['redirect']);
    }
}
