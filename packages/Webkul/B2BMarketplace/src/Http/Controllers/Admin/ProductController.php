<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Admin;

use Webkul\B2BMarketplace\Repositories\ProductRepository;
use Webkul\B2BMarketplace\Mail\ProductApprovalNotification;
use Webkul\B2BMarketplace\Mail\ProductDisApprovalNotification;
use Illuminate\Support\Facades\Mail;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;

/**
 * B2BMarketplace Admin Product's Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
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
     * ProductRepository object
     *
     * @var object
    */
    protected $productRepository;

    /**
     * ProductFlatRepository object
     *
     * @var object
    */
    protected $productFlat;

    /**
     * Create a new controller instance.
     *
     * @param Webkul\B2BMarketplace\Repositories\ProductRepository    $productRepository
     * @param Webkul\Product\Repositories\ProductFlatRepository $productFlat
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository, ProductFlat $productFlat)
    {
        $this->_config = request('_config');

        $this->productRepository = $productRepository;

        $this->productFlat = $productFlat;
    }

    /**
     * display the specific supplier's products
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            
            return app(\Webkul\B2BMarketplace\DataGrids\Admin\ProductDataGrid::class)->toJson();
        }
        return view($this->_config['view']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->productRepository->delete($id);

        session()->flash('success', trans('b2b_marketplace::app.admin.response.delete-success', ['name' => 'Product']));

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
            $product = $this->productRepository->find($productId);


            if ($product) {
                $this->productRepository->delete($product->id);
            }
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Mass updates the products
     *
     * @return response
     */
    public function massUpdate()
    {
        $data = request()->all();

        if (! isset($data['massaction-type']) || !$data['massaction-type'] == 'update') {
            return redirect()->back();
        }

        $productIds = explode(',', $data['indexes']);

        foreach ($productIds as $productId) {
            $supplierProduct = $this->productRepository->find($productId);

            if ($supplierProduct) {
                $product = $supplierProduct->product;

                $supplierProduct->update([
                        'is_approved' => $data['update-options']
                    ]);

                if ($data['update-options']) {

                    $baseProduct = $supplierProduct->product;

                    try {
                        Mail::send(new ProductApprovalNotification($supplierProduct));
                    } catch (\Exception $e) {

                    }
                } else if (! $data['update-options']) {
                    $baseProduct = $supplierProduct->product;

                    $statusAttributeValue =$baseProduct->attribute_values->where('attribute_id', 8)->first();
                    $statusAttributeValue->update(['boolean_value' => 0]);

                    $locale = core()->getCurrentLocale()->code;

                    $this->productFlat->findOneWhere(['product_id' => $baseProduct->id, 'locale' => $locale])->update(['status' => 0]);

                    try {
                        Mail::send(new ProductDisApprovalNotification($supplierProduct));
                    } catch (\Exception $e) {

                    }
                }
            }
        }

        session()->flash('success', trans('b2b_marketplace::app.admin.products.mass-update-success'));

        return redirect()->route($this->_config['redirect']);
    }
}
