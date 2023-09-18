<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Webkul\B2BMarketplace\Models\MessageMapping;
use Webkul\Sales\Repositories\ShipmentRepository;
use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\B2BMarketplace\Repositories\MessageRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\B2BMarketplace\Mail\NewSupplierMessageNotification;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\Sales\Repositories\OrderItemRepository as BaseOrderItem;
use Webkul\B2BMarketplace\Repositories\QuoteRepository as QuoteRepository;
use Webkul\Customer\Repositories\CustomerRepository as CustomerRepository;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository  as CustomerQuoteItemRepository;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository  as SupplierQuoteItemRepository;
use Webkul\B2BMarketplace\Repositories\CategoryRepository as SupplierCategories;
use Webkul\Product\Models\ProductFlat;

/**
 * BuyingLead controller
 *
 * @copyright 2020 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BuyingLeadController extends Controller
{
    /*
    * Contains route related configuration
    *
    * @var array
    */
    protected $_config;

    /**
    * Create a new controller instance.
    *
    * @param  \Webkul\B2BMarketplace\Repositories\OrderRepository    $order
    * @param  \Webkul\B2BMarketplace\Repositories\SupplierRepository   $supplier
    * @param  \Webkul\Sales\Repositories\OrderItemRepository      $baseOrderItem
    * @param  \Webkul\B2BMarketplace\Repositories\ShipmentRepository $shipment
    * @param  \Webkul\B2BMarketplace\Repositories\QuoteRepository $rfqRepository
    * @param  \Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository  $customerQuoteItemRepository
    * @param  \Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository  $supplierQuoteItemRepository
    * @param  \Webkul\B2BMarketplace\Models\MessageMapping $messageMapping
    * @return void
    */
   public function __construct(
       protected OrderRepository $order,
       protected SupplierRepository $supplier,
       protected BaseOrderItem $baseOrderItem,
       protected ShipmentRepository $shipment,
       protected QuoteRepository $quoteRepository,
       protected Product $product,
       protected CustomerRepository $customerRepository,
       protected CustomerQuoteItemRepository  $customerQuoteItemRepository,
       protected SupplierQuoteItemRepository $supplierQuoteItemRepository,
       protected MessageRepository $messages,
       protected SupplierCategories $supplierCategories,
       protected MessageMapping $messageMapping,
       protected ProductFlat $productFlatRepository,
    )
    {
       $this->_config = request('_config');
    }

    /**
    * Show the view for the specified resource.
    *
    * @return $requestedQuote
    */
    public function index()
    {
        $requestedQuote = [];
        $count = 0;

        $supplierId = auth()->guard('supplier')->user()->id;
        $rfq = $this->quoteRepository->with('customerQuote')->get();

        foreach ($rfq as $requestQuotes) {

            foreach ($requestQuotes->customerQuote()->get() as $customerQuotes) {
            
                if ($customerQuotes->is_requested_quote == 1) {
                    $customer = $this->customerRepository->findOneWhere(['id'=>$requestQuotes['customer_id']]);
                    $requestQuotes['postedBy'] = $customer->first_name. " ". $customer->last_name;
                    $requestQuotes['postedOn'] = $requestQuotes['created_at']->format('F d,Y');
                    $requestQuotes['productName'] = $this->productFlatRepository->find($customerQuotes['product_id']);
                    $count = 0;
                    //Get the Supplier selected categories
                    foreach (json_decode($customerQuotes['categories']) as $category) {

                        $supplierCategory = $this->supplierCategories->findWhere([
                            'supplier_id' => $supplierId, 'status' => '1', 'category_id' => $category
                        ]);

                        if (! empty($supplierCategory) && count($supplierCategory) > 0) {
                            $count +=1;
                        } else if($category == 1) {
                            $rootCategory = $this->supplierCategories->findWhere([
                                'supplier_id' => $supplierId,
                                'status' => '1',
                                'category_id' => '1'
                            ]);

                            if (count($rootCategory) != 0) {
                                $count +=1;
                            }

                        }
                    }

                    if ($count > 0) {
                        $requestedQuote[$requestQuotes->id] = collect($requestQuotes);
                    }
                }
            }
        }

        return view($this->_config['view'])->with('requestedQuote', $requestedQuote);
    }

    /**
    * Show the view for the specified resource.
    *
    * @param $id
    * @return $item_id
    */
    public function show($id, $item_id)
    {
        $rfqItem = '';

        $rfq = $this->quoteRepository->with('customerQuote')->all();

        foreach ($rfq as $requestQuotes)
        {
            if ($requestQuotes->is_requested_quote == 0) {

                $customerQuote = collect($requestQuotes['customerQuote']);

                foreach ($customerQuote as $product) {
                    if ($product['product_id'] == $item_id) {

                        $rfqItem = $product;
                    }
                }
            }
        }

       return view($this->_config['view'])->with('id', $id)->with('rfqItem', $rfqItem);
    }

    /**
    * Send The quote Requeset for the Customer.
    *
    * @param $id
    * @param $quote_id
    */
    public function sendQuote($id,$quote_id)
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $data  = request()->all();

        $this->validate(request(), [
            'quantity' => 'required',
            'price_per_quantity' => 'required',
            'shipping_time' => 'required',
            'note' => 'required',
        ]);

        $data['customer_id'] = $id;
        $data['supplier_id'] = $supplierId;
        $data['status'] = 'New';
        $data['quote_id'] = $quote_id;
        $data['is_requested_quote'] = 0;

        $quoteItemId = $this->customerQuoteItemRepository->findOneByField(['product_id'=> (integer)$data['product_id'], 'quote_id' => $data['quote_id']])->id;

        $this->customerQuoteItemRepository->findOrFail($quoteItemId)->update(['supplier_id' => $supplierId , 'status' => 'Answered', 'quote_status' => 'Processing', 'is_requested_quote' => 0]);

        $this->supplierQuoteItemRepository->create($data);

        return response()->json([
            'success' => true,
            'message' => trans('b2b_marketplace::app.supplier.account.lead.quote-success')
        ]);
    }

    /**
    * Store the messages send to customer
    *
    * @return $requestedQuote
    */
    public function store()
    {
        $data = request()->all();
        $supplierId = auth()->guard('supplier')->user()->id;
        $data['supplier_id'] = $supplierId;

        $isThread = $this->messageMapping->Where([
            'customer_id' => $data['customer_id'],
            'supplier_id' =>  $data['supplier_id']
        ])->get();

        if (empty($isThread) || count($isThread) <1 ) {

            //No thread Available
            $newThread = $this->messageMapping->create($data);

            if (isset($newThread)) {
                $message['message_id'] = $newThread->id;
                $message['message'] = $data['message'];
                $message['role'] = 'supplier';
                $message['is_new'] = 1;
            }

            $newData = $this->messages->create($message);

            if (core()->getConfigData('b2b_marketplace.settings.general.chat_notification')) {
                try {
                    Mail::send(new NewSupplierMessageNotification($newData, $newThread));
                } catch (\Exception $e) {}
            }

        } else {
            $isThread = $isThread->first();

            //thread available
            $message['message_id'] = $isThread->id;
            $message['message'] = $data['message'];
            $message['role'] = 'supplier';
            $message['is_new'] = 1;

            $newData = $this->messages->create($message);

            if (isset($newData)) {
                $this->messageMapping->where('id', $isThread->id)->update([
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
            }

            if (core()->getConfigData('b2b_marketplace.settings.general.chat_notification')) {
                try {
                    Mail::send(new NewSupplierMessageNotification($newData, $isThread));
                } catch (\Exception $e) {}
            }
        }

        return response()->json([
            'success' => true,
            'message' => trans('b2b_marketplace::app.supplier.account.lead.msg-success')
        ]);
    }
}
