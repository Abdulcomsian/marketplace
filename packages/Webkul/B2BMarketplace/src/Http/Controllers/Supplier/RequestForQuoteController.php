<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Carbon;
use Storage;
use Webkul\B2BMarketplace\Repositories\QuoteRepository as QuoteRepository;
use Webkul\Customer\Repositories\CustomerRepository as CustomerRepository;
use Webkul\B2BMarketplace\Repositories\QuoteImageRepository as QuoteImage;
use Webkul\B2BMarketplace\Repositories\QuoteMessageRepository as QuoteMessage;
use Webkul\B2BMarketplace\Repositories\SupplierRepository as SupplierRepository;
use Webkul\B2BMarketplace\Repositories\QuoteAttachmentRepository as QuoteAttachment;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuoteItem;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository as CustomerQuoteItem;


/* Supplier Request For Quote Controller
*
* @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
*/
class RequestForQuoteController extends Controller
{
    /**
     * CategoryRepository object
     *
     * @var object
     */
    protected $_config;

    /**
     * Create a new Repository instance.
     *
     * @param  \Webkul\B2BMarketplace\Repositories\SupplierRepository          $supplierRepository
     * @param  \Webkul\B2BMarketplace\Repositories\QuoteMessageRepository      $quoteMessage
     * @param  \Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository $CustomerQuoteItem
     * @param  \Webkul\Customer\Repositories\CustomerRepository                $customerRepository
     * @param  \Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository $supplierQuoteItem
     * @return void
     */
    public function __construct(
        protected QuoteRepository $quoteRepository,
        protected CustomerRepository $customerRepository,
        protected CustomerQuoteItem $customerQuoteItem,
        protected SupplierRepository $supplierRepository,
        protected SupplierQuoteItem $supplierQuoteItem,
        protected QuoteMessage $quoteMessage,
        protected QuoteAttachment $quoteAttachment,
        protected QuoteImage $quoteImage
    )
    {
        $this->_config = request('_config');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Request For Quote New Status
     *
    * @return \Illuminate\View\View
     */
    public function new()
    {
        return view($this->_config['view']);
    }

    /**
     * Request For Quote Pending Status
     *
     * @return \Illuminate\View\View
     */
    public function pending()
    {
        return view($this->_config['view']);
    }

    /**
     * Request For Quote answered Status
     *
     * @return \Illuminate\View\View
     */
    public function answered()
    {
        return view($this->_config['view']);
    }

    /**
     * Request For Quote Confirmed Status
     *
     * @return \Illuminate\View\View
     */
    public function confirmed()
    {
        return view($this->_config['view']);
    }

    /**
     * Request For Quote rejected Status
     *
     * @return \Illuminate\View\View
     */
    public function rejected()
    {
        return view($this->_config['view']);
    }

    /**
     * Display the specific resource for the quote request
     *
     * @param $quoteId
     * @param $productId
     * @return \Illuminate\View\View
     */
    public function show($quoteId, $productId)
    {
        $customerQuote ='';
        $supplierQuote = '';
        $quote = '';
        $customerName = '';

        $supplier = auth()->guard('supplier')->user();

        if ( isset($supplier) ) {

            $supplierName = $supplier->first_name ." ". $supplier->last_name;
        }

        $rfq = $this->quoteRepository->with('customerQuote')->all();

        foreach ($rfq as $requestQuotes) {
            foreach ($requestQuotes['customerQuote'] as $quotes) {

                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId) {

                    $customerQuote = $quotes->where(['supplier_id'=> null, 'quote_id' => $quoteId, 'product_id' => $productId])->first();
                    $supplierQuote = $quotes->where(['supplier_id'=> $supplier->id, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                    $quote = $requestQuotes;
                    $customerName = $this->customerRepository->findOneWhere(['id' => $requestQuotes->customer_id]);
                    $customerName = $customerName->first_name . " " . $customerName->last_name;
                }
            }

        }

        return view($this->_config['view'])
        ->with('customerQuote', $customerQuote)
        ->with('supplierQuote', $supplierQuote)
        ->with('quote', $quote)
        ->with('customerName', $customerName)
        ->with('supplierName', $supplierName);
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
            session()->flash('success', trans('b2b_marketplace::app.supplier.account.rfq.not-exist', ['name' => 'Image']));
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
                $name = 'file_'. $quoteId .'.' . $getExtension[1];
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
                session()->flash('success', trans('b2b_marketplace::app.supplier.account.rfq.not-exist', ['name' => 'File']));

                return back();
            }
        } else {
            session()->flash('success', trans('b2b_marketplace::app.supplier.account.rfq.not-exist', ['name' => 'File']));

            return back();
        }
    }

    /**
     * display the specific answered request quote of the customer
     *
     * @param $productId
     * @param $quoteId
     * @return \Illuminate\View\View
     */
    public function answeredQuote($quoteId, $productId)
    {
        $customerQuote      = '';
        $supplierQuote      = '';
        $quote              = '';
        $customerName       = '';
        $supplierFirstQuote = '';
        $supplierLastQuote  = '';

        $supplier = auth()->guard('supplier')->user();

        $supplierName = $supplier->first_name ." ". $supplier->last_name;

        $rfq = $this->quoteRepository->with(['customerQuote'])->all();
        foreach ($rfq as $requestQuotes) {
            foreach ($requestQuotes['customerQuote'] as $quotes) {
                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId) {
                    $customerQuote = $quotes->with('products')->where([ 'quote_id' => $quoteId, 'product_id' => $productId])->first();
                    $quote = $requestQuotes;
                    $customerName = $this->customerRepository->findOneWhere(['id' => $requestQuotes->customer_id]);
                    $customerName = $customerName->first_name . " " . $customerName->last_name;
                }
            }

            foreach ($requestQuotes['supplierQuote'] as $quotes) {

                $supplierQuote = $quotes->where(['supplier_id'=> $supplier->id, 'quote_id' => $quoteId, 'product_id' => $productId])->get();

                $supplierFirstQuote = $quotes->with('products')->where(['supplier_id'=> $supplier->id, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                $supplierLastQuote = $quotes->with('products')->with('products')->where(['supplier_id'=> $supplier->id, 'quote_id' => $quoteId, 'product_id' => $productId])->orderby('Id', 'DESC')->first();
            }

        }
        return view($this->_config['view'])
            ->with('customerQuote', $customerQuote)
            ->with('supplierQuotes', $supplierQuote)
            ->with('supplierFirstQuote', $supplierFirstQuote)
            ->with('supplierLastQuote', $supplierLastQuote)
            ->with('quote', $quote)
            ->with('customerName', $customerName)
            ->with('supplierName', $supplierName)
            ->with('quoteMessages', $this->quoteMessage);
    }

    /**
     * Create speacific resource to create
     *
     * @param $id
     * @param $item_id
     * @return \Illuminate\View\View
     */
    public function create($id, $item_id)
    {
        $rfqItem = '';

        $rfq = $this->quoteRepository->with('customerQuote')->all();

        foreach ($rfq as $requestQuotes) {
            if ($requestQuotes->is_requested_quote == 0) {

                $customerQuote = collect($requestQuotes['customerQuote']);
                foreach ($customerQuote as $product) {

                    if($product['product_id'] == $item_id)
                        $rfqItem = $product;
                }
            }
        }

       return view($this->_config['view'])->with('id', $id)->with('rfqItem', $rfqItem);
    }

    /**
     * Reject speacific Request for quote
     *
     * @param $supplierQuoteId
     * @param $customerQuoteId
     * @return void
     */
    public function reject($supplierQuoteId, $customerQuoteId)
    {
        $this->supplierQuoteItem->update(['status'=> 'Rejected'], $supplierQuoteId);
        $this->customerQuoteItem->update(['status'=> 'Rejected'], $customerQuoteId);

        session()->flash('success', trans('b2b_marketplace::app.supplier.account.rfq.rejected'));

        return back();
    }
}
