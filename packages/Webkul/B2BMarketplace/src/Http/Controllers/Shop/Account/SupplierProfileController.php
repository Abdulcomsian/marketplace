<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account;

use Webkul\B2BMarketplace\Http\Controllers\Shop\Controller;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository as SupplierAddress;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\B2BMarketplace\Repositories\QuoteRepository;
use Webkul\B2BMarketplace\Repositories\QuoteImageRepository as QuoteImage;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository as CustomerQuoteItem;
use Webkul\B2BMarketplace\Repositories\QuoteAttachmentRepository as QuoteAttachment;

/**
 * SupplierProfile Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SupplierProfileController extends Controller
{
    /**
     * Supplier Repository Object
     *
     * @var object
     */
    protected $supplierRepository;

    /**
     * Customer Repository Object
     *
     * @var object
     */
    protected $customerRepository;

    /**
     * Customer Repository Object
     *
     * @var object
     */
    protected $customerGroup;

    /**
     * Quote Repository Object
     *
     * @var object
     */
    protected $quoteRepository;

    /**
     * Customer Quote Repository Object
     *
     * @var object
     */
    protected $customerQuoteItem;

    /**
     * Quote Image Repository Object
     *
     * @var object
     */
    protected $quoteImage;

    /**
     * quoteAttachment Repository
     *
     * @var object
     */
    protected $quoteAttachment;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplierRepository
     * @param  Webkul\Customer\Repositories\CustomerRepository        $customerRepository
     * @param  Webkul\Customer\Repositories\CustomerGroupRepository   $customerGroup
     * @param  Webkul\B2BMarketplace\Repositories\SupplierAddressesRepository   $supplierAddress
     * @param  Webkul\B2BMarketplace\Repositories\QuoteRepository     $quoteRepository
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        CustomerRepository $customerRepository,
        CustomerGroupRepository $customerGroup,
        SupplierAddress $supplierAddress,
        QuoteRepository $quoteRepository,
        QuoteImage $quoteImage,
        CustomerQuoteItem $customerQuoteItem,
        QuoteAttachment $quoteAttachment
    )
    {
        $this->middleware('customer', ['except' => ['show', 'isCustomer']]);

        $this->_config = request('_config');

        $this->supplierRepository = $supplierRepository;

        $this->customerRepository = $customerRepository;

        $this->customerGroup = $customerGroup;

        $this->supplierAddress = $supplierAddress;

        $this->quoteImage = $quoteImage;

        $this->quoteRepository = $quoteRepository;

        $this->customerQuoteItem = $customerQuoteItem;

        $this->quoteAttachment = $quoteAttachment;

    }

    /**
     * Store the RFQ from supplier profile
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $customerId = auth()->guard('customer')->user()->id;

        $data = request()->all();

        $this->validate(request(), [
            'quote_title' => 'required',
            'quote_brief' => 'required',
            'name' => 'required',
            'company_name' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
        ]);

        if (isset($data['products'])) {
            $addedProducts = $data['products'];

            foreach ($addedProducts as $product) {
                $requestData[]= json_decode($product);
            }

            $requestedQuote = collect($requestData);

            $RFQData['customer_id'] = $customerId;
            $RFQData['quote_title'] = $data['quote_title'];
            $RFQData['quote_brief'] = $data['quote_brief'];
            $RFQData['name'] = $data['name'];
            $RFQData['company_name'] = $data['company_name'];
            $RFQData['address'] = $data['address'];
            $RFQData['phone'] = $data['contact_number'];

            $requestQuote = $this->quoteRepository->create($RFQData);

            $this->quoteImage->uploadImages($data, $requestQuote);

            $this->quoteAttachment->uploadFiles($data, $requestQuote);

            if ($requestedQuote) {
                foreach ($requestedQuote as $key=>$quote) {

                    $requestedProduct['product_id'] = $quote->product_id;
                    $requestedProduct['quantity'] = $quote->quantity;
                    $requestedProduct['description'] = $quote->description;
                    $requestedProduct['product_name'] =$quote->product_name;
                    $requestedProduct['price_per_quantity'] = $quote->priceperqty;
                    $requestedProduct['is_sample'] = $quote->is_sample;
                    $requestedProduct['quote_id'] = $requestQuote->id;
                    $requestedProduct['customer_id'] = $customerId;
                    $requestedProduct['status'] = 'New';
                    $requestedProduct['quote_status'] = 'New';
                    $requestedProduct['is_requested_quote'] = 1;
                    $requestedProduct['categories'] = json_encode($quote->category_id);
                    $requestedProduct['supplier_id'] = $data['supplier_id'];

                    $this->customerQuoteItem->create($requestedProduct);
                }
            }

            session()->flash('success', trans('b2b_marketplace::app.shop.account.rfq.success-create'));

        } else {

            session()->flash('error', trans('b2b_marketplace::app.shop.account.rfq.add'));

            return back();
        }

        return redirect()->route($this->_config['redirect']);
    }
}