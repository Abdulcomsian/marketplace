<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account\RequestedQuote;

use Webkul\B2BMarketplace\Http\Controllers\Shop\Controller;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\Category\Repositories\CategoryRepository as CategoryRepository;
use Webkul\B2BMarketplace\Repositories\ProductRepository as SupplierProduct;
use Webkul\B2BMarketplace\Repositories\QuoteRepository as RequestQuote;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository as CustomerQuoteItem;
use Webkul\Customer\Repositories\CustomerRepository as CustomerRepository;
use Webkul\B2BMarketplace\Repositories\QuoteMessageRepository as QuoteMessage;


class QuoteResponseController extends Controller
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
     * @param  \Webkul\Category\Repositories\CategoryRepository                $categoryRepository
     * @param  \Webkul\B2BMarketplace\Repositories\QuoteRepository             $quoteRepository
     * @param  \Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository $customerQuoteItem
     * @param  \Webkul\Customer\Repositories\CustomerRepository                $customerRepository
     * @param  \Webkul\B2BMarketplace\Repositories\QuoteMessageRepository      $quoteMessage
     * @return void
     */
    public function __construct(
        protected SupplierRepository $supplierRepository,
        protected CategoryRepository $categoryRepository,
        protected SupplierProduct    $supplierProduct,
        protected RequestQuote       $quoteRepository,
        protected CustomerQuoteItem  $customerQuoteItem,
        protected CustomerRepository $customerRepository,
        protected QuoteMessage       $quoteMessage
        )
    {
        $this->_config = request('_config');
    }

    /**
     * Method to populate quote Request page.
     *
     * @param int $quoteId
     * @param int $productId
     * @return \Illuminate\View\View
     */
    public function index($status, $quoteId, $productId )
    {
        $supplierQuote = '';
        $customerQuote = '';
        $quote = '';

        $customerName = auth()->guard('customer')->user();
        $customerName = $customerName->first_name . " " . $customerName->last_name;

        $rfq = $this->quoteRepository->with('supplierQuote')->all();

        foreach ($rfq as $requestQuotes)
        {
            foreach ($requestQuotes['supplierQuote'] as $quotes) {

                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId && $quotes->supplier_id != null) {
                    $supplierQuotes = $quotes->where(['quote_id' => $quoteId, 'product_id' => $productId ]);

                    $supplierQuote = $supplierQuotes->where('supplier_id', '!=', null)->first();

                    $customerQuote = $quotes->where(['supplier_id'=> null, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                    $quote = $requestQuotes;

                    if (isset($supplierQuote)) {

                        $supplierName = $this->supplierRepository->findOneWhere(['id' => $supplierQuote->supplier_id]);
                        $supplierName = $supplierName->first_name ."". $supplierName->last_name;
                    }
                } else {

                    if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId && $quotes->supplier_id == null) {

                        $newStatus = $quotes->where(['supplier_id'=> null, 'quote_id' => $quoteId, 'product_id' => $productId])->first();
                        $customerQuote = $quotes->where(['supplier_id'=> null, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                        $quote = $requestQuotes;

                        if (isset($newStatus)) {

                            $supplierQuote = $newStatus;
                            $supplierName = null;
                        }
                    }
                }
            }
        }

        return view($this->_config['view'])
        ->with('supplierQuote', $supplierQuote)
        ->with('customerQuote', $customerQuote)
        ->with('quote', $quote)
        ->with('customerName', $customerName)
        ->with('supplierName', $customerName)
        ->with('status', $status);
    }

    /**
     * Populate The Quote Requests
     *
     * @param int $quoteId
     * @param int $productId
     * @param int $supplierId
     *
     * @return \Illuminate\View\View
     */
    public function show($quoteId, $supplierId, $productId)
    {
        $customerQuote ='';
        $supplierQuote = '';
        $quote = '';
        $customerName = '';
        $supplierFirstQuote = '';
        $supplierLastQuote = '';
        $supplierName = '';

        $supplier = $this->supplierRepository->findOneWhere(['id' => $supplierId]);

        if (isset($supplier)) {
            $supplierName = $supplier->first_name ." ". $supplier->last_name;
        }

        $supplierRfq = $this->quoteRepository->with('supplierQuote')->all();
        $customerRfq = $this->quoteRepository->with('customerQuote')->all();

        foreach ($supplierRfq as $requestQuotes)
        {
            foreach ($requestQuotes['supplierQuote'] as $quotes) {
                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId && $quotes->supplier_id == $supplierId) {

                    $supplierQuote = $quotes->where(['supplier_id' => $supplierId, 'quote_id' => $quoteId, 'product_id' => $productId])->get();

                    $supplierFirstQuote = $quotes->where(['supplier_id' => $supplierId, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                    $supplierLastQuote = $quotes->where(['supplier_id' => $supplierId, 'quote_id' => $quoteId, 'product_id' => $productId])->orderby('Id', 'DESC')->first();

                    $quote = $requestQuotes;
                }
            }
        }

        foreach ($supplierRfq as $requestQuotes)
        {
            foreach ($requestQuotes['customerQuote'] as $quotes) {
                if ($quotes->quote_id == $quoteId && $quotes->product_id == $productId && $quotes->supplier_id == $supplierId) {

                    $customerQuote = $quotes->where(['supplier_id' => $supplierId, 'quote_id' => $quoteId, 'product_id' => $productId])->first();

                    $customerName = $this->customerRepository->findOneWhere(['id' => $requestQuotes->customer_id]);
                    $customerName = $customerName->first_name . " " . $customerName->last_name;
                }
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
     * Populate The New Quote Requests
     *
     * @return \Illuminate\View\View
     */
    public function new()
    {
        return view($this->_config['view']);
    }

    /**
     * Populate The Pending Quote Requests
     *
     * @return \Illuminate\View\View
     */
    public function pending()
    {
        return view($this->_config['view']);
    }

    /**
     * Populate The Answered Quote Requests
     *
     * @return \Illuminate\View\View
     */
    public function answered()
    {
        return view($this->_config['view']);
    }
}