<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account;

use Webkul\B2BMarketplace\Http\Controllers\Shop\Controller;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\Category\Repositories\CategoryRepository as CategoryRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\B2BMarketplace\Repositories\QuoteRepository as RequestQuote;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository as CustomerQuoteItem;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuoteItem;
use Webkul\Customer\Repositories\CustomerRepository as CustomerRepository;
use Webkul\B2BMarketplace\Repositories\CategoryRepository as SupplierCategory;
use Webkul\B2BMarketplace\Repositories\QuoteImageRepository as QuoteImage;
use Webkul\Product\Repositories\ProductRepository as BaseProduct;
use Webkul\B2BMarketplace\Repositories\QuoteAttachmentRepository as QuoteAttachment;
use Carbon;
use Storage;
use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Exception;
use DB;

class RequestQuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * Create a new Repository instance.
     *
     * @param  \Webkul\B2BMarketplace\Repositories\SupplierRepository          $supplier
     * @param  \Webkul\B2BMarketplace\Repositories\ProductRepository           $supplierProduct
     * @param  \Webkul\B2BMarketplace\Repositories\SupplierQuoteItem           $supplierQuoteItem
     * @param  \Webkul\Category\Repositories\CategoryRepository                $categoryRepository
     * @param  \Webkul\B2BMarketplace\Repositories\QuoteRepository             $quoteRepository
     * @param  \Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository $customerRepository
     * @param  \Webkul\B2BMarketplace\Repositories\CategoryRepository          $supplierCategory
     * @return void
     */
    public function __construct(
        protected SupplierRepository  $supplierRepository,
        protected CategoryRepository  $categoryRepository,
        protected SupplierProduct     $supplierProduct,
        protected RequestQuote        $quoteRepository,
        protected CustomerQuoteItem   $customerQuoteItem,
        protected CustomerRepository  $customerRepository,
        protected SupplierQuoteItem   $supplierQuoteItem,
        protected SupplierCategory    $supplierCategory,
        protected QuoteImage          $quoteImage,
        protected BaseProduct         $baseProduct,
        protected QuoteAttachment     $quoteAttachment,
        protected ProductFlat         $productFlat
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Method to populate supplier review page which will be populated.
     *
     * @return Mixed
     */
    public function index($productId = null)
    {
        if (! auth()->guard('customer')->user()) {
            return redirect()->route('customer.session.index');
        }

        $categories = null;

        if ( isset($productId) && ! isset($productId['view']) && $productId != null ) {
            $product = $this->productFlat->findOneWhere(['product_id' => $productId]);

            $supplier = $this->supplierProduct->getSupplierByProductId($productId);
        }

        $allCategories = $this->categoryRepository->all();

        foreach ($allCategories as $category) {
            foreach ($category->translations as $maincategory) {

                if(app()->getLocale() == $maincategory->locale) {

                    $categories[] = $maincategory;
                    $directCategories[] = $maincategory->id;
                }
            }
        }

        if ( isset($productId) && ! isset($productId['view']) && $productId != null ) {
            $collectCategories = collect($directCategories)->implode(',');
            return view($this->_config['view'])->with([
                'categories' => $collectCategories,
                'supplier' => $supplier,
                'product' => $product
            ]);
        } else {
            return view($this->_config['view'])->with('categories', $categories);
        }

    }

    /**
     * Store rfq for products.
     *
     * @return void
     */
    public function store()
    {
        $customerId = auth()->guard('customer')->user()->id;
        $data = request()->all();

        if(isset($data['rfqInfo'])) {
            //rfq from global
            $rfqInfo = json_decode($data['rfqInfo']);
        } else {
            $this->validate(request(), [
                'quote_title' => 'required',
                'quote_brief' => 'required',
                'name' => 'required',
                'company_name' => 'required',
                'address' => 'required',
                'contact_number' => 'required',
                'products' => 'required',
            ]);
        }

        if (isset($data['products'])) {

            DB::beginTransaction();

            try {

                $addedProducts = $data['products'];

                foreach ($addedProducts as $product) {
                    $requestData[]= json_decode($product);
                }

                $requestedQuotes = collect($requestData);

                $RFQData['customer_id'] = $customerId;

                if(isset($rfqInfo)) {

                    //request from global rfq
                    $RFQData['quote_title']  = $rfqInfo->quote_title;
                    $RFQData['quote_brief']  = $rfqInfo->quote_brief;
                    $RFQData['name']         = $rfqInfo->name;
                    $RFQData['company_name'] = $rfqInfo->company_name;
                    $RFQData['address']      = $rfqInfo->address;
                    $RFQData['phone']        = $rfqInfo->contact_number;
                } else {

                    $RFQData['quote_title'] = $data['quote_title'];
                    $RFQData['quote_brief'] = $data['quote_brief'];
                    $RFQData['name'] = $data['name'];
                    $RFQData['company_name'] = $data['company_name'];
                    $RFQData['address'] = $data['address'];
                    $RFQData['phone'] = $data['contact_number'];
                }

                $requestQuote = $this->quoteRepository->create($RFQData);

                $this->quoteImage->uploadImages($data, $requestQuote);

                $this->quoteAttachment->uploadFiles($data, $requestQuote);

                if ( isset($data['supplier_id'])) {
                    $requestedQuotes = [0];
                    $category_id = array_map('intval', explode(',', $data['products']['category_id']));
                }

                if ($requestedQuotes) {
                    foreach ($requestedQuotes as $key=>$requestedQuote) {

                        if ( isset($data['supplier_id'])) {
                            $requestedProduct['product_id']         = $data['products']['product_id'];
                            $requestedProduct['quantity']           = $data['products']['quantity'];
                            $requestedProduct['description']        = $data['products']['description'];
                            $requestedProduct['price_per_quantity'] = $data['products']['priceperqty'];
                            $requestedProduct['is_sample']          = $data['products']['is_sample'];
                            $requestedProduct['categories']         = json_encode($category_id);

                            if ( isset($data['supplier_id']) && $data['supplier_id'] != 'NULL') {
                                $requestedProduct['supplier_id'] = $data['supplier_id'];
                            }

                        } else {
                            $requestedProduct['product_id']         = $requestedQuote->product_id;
                            $requestedProduct['quantity']           = $requestedQuote->quantity;
                            $requestedProduct['description']        = $requestedQuote->description;
                            $requestedProduct['price_per_quantity'] = $requestedQuote->priceperqty;
                            $requestedProduct['is_sample']          = $requestedQuote->is_sample;
                            $requestedProduct['categories']         = json_encode($requestedQuote->category_id);
                        }

                        $requestedProduct['quote_id']           = $requestQuote->id;
                        $requestedProduct['customer_id']        = $customerId;
                        $requestedProduct['status']             = 'New';
                        $requestedProduct['quote_status']       = 'New';
                        $requestedProduct['is_requested_quote'] = 1;

                        $this->customerQuoteItem->create($requestedProduct);
                    }
                }

                session()->flash('success', trans('b2b_marketplace::app.shop.account.rfq.success-create'));

            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }

            DB::commit();

        } else {
            session()->flash('error', trans('b2b_marketplace::app.shop.account.rfq.add'));

            return back();
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Populate The Request For Quote Page.
     *
     * @return Illuminate\http\response
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Doqnload the quote Images.
     *
     * @param $intquoteId
     * @return Illuminate\http\response
     */
    public function downloadImages($quoteId)
    {
        $quoteImages = $this->quoteImage->findWhere(['customer_quote_id' => $quoteId]);

        $public_dir=base_path().'/storage/app/public/';
        $zipFileName = Carbon\Carbon::now().'.zip';
        $zip = new \ZipArchive;
        $i = 0;

        if ($zip->open($public_dir . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($quoteImages as $quoteImage) {

                $getExtension = explode(".",$quoteImage->path);
                $path = base_path().'/storage/app/public/'.$quoteImage->path;
                $name = 'image_'. $i . '.' . $getExtension[1];

                $zip->addFile($path, $name);
                $i++;
            }

            $zip->close();
        }

        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );

        $filetopath=$public_dir . $zipFileName;

        if (file_exists($filetopath)){

            return response()->download($filetopath,$zipFileName,$headers);
        } else {
            session()->flash('success', trans('b2b_marketplace::app.shop.account.rfq.not-exist',['name' => 'Image']));
            return back();
        }
    }

    /**
     * Download the Quote Attachments.
     *
     * @param $intquoteId
     * @return Illuminate\http\response
     */
    public function downloadAttachment($quoteId)
    {
        $quoteFile = $this->quoteAttachment->findOneWhere(['customer_quote_id' => $quoteId]);

        if ($quoteFile != null) {
            $filepath = storage_path('app/public/zipFiles');

            $zipFileName = Carbon\Carbon::now().'.zip';
            $zip = new \ZipArchive;

            if (! Storage::exists($filepath)) {

                Storage::makeDirectory('/zipFiles/'. $quoteId);
            }

            if ($zip->open($filepath . '/' . $quoteId . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {

                $getExtension = explode(".",$quoteFile->path);

                $path = base_path().'/storage/app/public/'.$quoteFile->path;
                $name = 'file_'. $quoteId . '.' . $getExtension[1];
                $zip->addFile($path, $name);
                $zip->close();
            }

            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );

            $filetopath=$filepath . '/' . $quoteId . '/' . $zipFileName;

            if (file_exists($filetopath)){

                return response()->download($filetopath,$zipFileName,$headers);
            } else {
                session()->flash('success', trans('b2b_marketplace::app.shop.account.rfq.not-exist',['name' => 'File']));

                return back();
            }
        } else {
            session()->flash('success', trans('b2b_marketplace::app.shop.account.rfq.not-exist',['name' => 'File']));

            return back();
        }
    }

    /**
     * Product Search
     *
     * @return \Illuminate\View\View
     */
    public function searchProduct()
    {
        if (request()->all()) {
            $results = [];

            foreach ($this->supplierProduct->searchProducts(request()->input('query')) as $row) {

                $ifConfig = $this->baseProduct->findOneWhere(['id' => $row->product_id, 'type' => 'configurable']);

                if (isset($ifConfig)) {
                    $isConfig = 1;
                } else {
                    $isConfig = 0;
                }

                $results[] = [
                    'id' => $row->product_id,
                    'sku' => $row->sku,
                    'name' => $row->name,
                    'price' => core()->convertPrice($row->price),
                    'formated_price' => core()->currency(core()->convertPrice($row->price)),
                    'base_image' => $row->product->base_image_url,
                    'parent_id' => $row->product->parent_id,
                    'is_config' => $isConfig
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

    /**
     * Product Search
     *
     * @return \Illuminate\View\View
     */
    public function searchProductFromProfile()
    {
        if (request()->all()) {
            $results = [];

            foreach ($this->supplierProduct->searchSupplierProducts(request()->input('params')['query'], request()->input('params')['supplierId']) as $row) {

                $ifConfig = $this->baseProduct->findOneWhere(['id' => $row->product_id, 'type' => 'configurable']);

                if (isset($ifConfig)) {
                    $isConfig = 1;
                } else {
                    $isConfig = 0;
                }

                $results[] = [
                    'id' => $row->product_id,
                    'sku' => $row->sku,
                    'name' => $row->name,
                    'price' => core()->convertPrice($row->price),
                    'formated_price' => core()->currency(core()->convertPrice($row->price)),
                    'base_image' => $row->product->base_image_url,
                    'parent_id' => $row->product->parent_id,
                    'is_config' => $isConfig
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }

    /**
     * Add products to the rfq.
     */
    public function addProduct()
    {
        $selectedProduct = request()->input();

        if (! isset($selectedProduct['product_name']) )
        $selectedProduct = null;

        return response()->json([
                            'selectedProduct' => $selectedProduct,
                            "error" => true,
                            "message" => trans('b2b_marketplace::app.shop.account.rfq.not-found')
                        ]);
    }

    /**
     * Response to the RFQ.
     *
     * @param int $quoteId
     * @param int $productId
     * @return \Illuminate\View\View
     */
    public function respond($quoteId, $productId)
    {
        $customerName = auth()->guard('customer')->user();
        $customerName = $customerName->first_name . " " . $customerName->last_name;
        $customerRfq = $this->quoteRepository->with('customerQuote')->all();

        foreach ($customerRfq as $requestQuotes)
        {
            foreach ($requestQuotes['customerQuote'] as $quotes) {
                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId) {

                    $supplierQuote = $quotes->where(['supplier_id'=> !null, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                    $quote = $requestQuotes;

                    if(isset($supplierQuote)) {
                        $supplierName = $this->supplierRepository->findOneWhere(['id' => $supplierQuote->supplier_id]);
                        $supplierName = $supplierName->first_name ."". $supplierName->last_name;
                    }
                }
            }
        }

        //Supplier RFQ
        $supplierRfq = $this->quoteRepository->with('supplierQuote')->all();

        foreach ($supplierRfq as $requestQuotes)
        {
            foreach ($requestQuotes['supplierQuote'] as $quotes) {
                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId) {

                    $customerQuote = $quotes->where(['supplier_id'=> !null, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                    $quote = $requestQuotes;

                    if(isset($supplierQuote)) {
                        $supplierName = $this->supplierRepository->findOneWhere(['id' => $supplierQuote->supplier_id]);
                        $supplierName = $supplierName->first_name ."". $supplierName->last_name;
                    }
                }
            }
        }

        return view($this->_config['view'])
        ->with('supplierQuote', $supplierQuote)
        ->with('customerQuote', $customerQuote)
        ->with('quote', $quote)
        ->with('customerName', $customerName)
        ->with('supplierName', $customerName);
    }

    /**
     * Approve Supplier Quote Request.
     *
     * @param int $supplier_quote
     * @param int $customer_quote
     * @param int $customer_id
     *
     * @return \Illuminate\View\View
     */
    public function approveQuote($supplier_quote, $customer_quote, $customer_id)
    {
        $data =[];
        $data['is_approve'] = 1;
        $data['status'] = 'Approved';

        //update status of the supplier quote approved
        $supplierQuote = $this->supplierQuoteItem->findOneWhere(['id' => $supplier_quote , 'customer_id' => $customer_id])->id;

        $this->supplierQuoteItem->find($supplierQuote)->update($data);

        // update status of the customer quote approved
        $customerQuote = $this->customerQuoteItem->findOneWhere(['id' => $customer_quote , 'customer_id' => $customer_id])->id;

        $this->customerQuoteItem->find($customerQuote)->update($data);

        return redirect()->back();
    }

    /**
     * Reject speacific Request for quote
     *
     * @param $id
     * @param $item_id
     * @return void
     */
    public function reject($supplierQuoteId, $customerQuoteId)
    {
        $this->supplierQuoteItem->update(['status'=> 'Rejected', 'quote_status'=> 'Rejected'], $supplierQuoteId);
        $this->customerQuoteItem->update(['status'=> 'Rejected', 'quote_status'=> 'Rejected'], $customerQuoteId);

        session()->flash('success', trans('b2b_marketplace::app.shop.account.rfq.rejected'));

        return back();
    }

}
