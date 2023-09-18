<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account\Messages;

use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\B2BMarketplace\Repositories\QuoteMessageRepository as QuoteMessage;
use Webkul\B2BMarketplace\Repositories\SupplierRepository as SupplierRepository;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuoteRepository;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository as CustomerQuoteRepository;

/**
*  Message's Controller
*
* @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
*/
class QuoteMessageController extends Controller
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
     * @var array
    */
    protected $supplier;

    /**
     * QuoteMessage Repository object
     *
     * @return \Illuminate\Http\Response
     */
    protected $quoteMessage;

    /**
     * Supplier Repository object
     *
     * @return \Illuminate\Http\Response
     */
    protected $supplierRepository;

    /**
     * Supplier QuoteRepository object
     *
     * @return \Illuminate\Http\Response
     */
    protected $supplierQuoteRepository;

    /**
     * Customer Quote Repository object
     *
     * @return \Illuminate\Http\Response
     */
    protected $customerQuoteRepository;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\QuoteMessageRepository $quoteMessage
     * @param  Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository $supplierQuoteRepository
     * @param  Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository $customerQuoteRepository
     * @return void
     */
    public function __construct(
        QuoteMessage $quoteMessage,
        SupplierRepository $supplierRepository,
        SupplierQuoteRepository $supplierQuoteRepository,
        CustomerQuoteRepository $customerQuoteRepository
    )
    {
        $this->_config = request('_config');

        $this->quoteMessage = $quoteMessage;

        $this->supplierRepository = $supplierRepository;

        $this->supplierQuoteRepository = $supplierQuoteRepository;

        $this->customerQuoteRepository = $customerQuoteRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param int $supplierQuoteItemId
     * @param int $customerQuoteItemId
     * @param int $customerId
     * @return void
     */
    public function store($supplierQuoteItemId, $customerQuoteItemId, $customerId)
    {
        $data = request()->all();

        $data['customer_id'] = $customerId;
        $data['customer_quote_item_id'] = $customerQuoteItemId;
        $data['supplier_quote_item_id'] = $supplierQuoteItemId;

        if(ctype_space($data['message']) || $data['message'] == null){
            session()->flash('error', trans('b2b_marketplace::app.shop.supplier.account.empty-msg'));
            return back();
        }

        $result = $this->quoteMessage->create($data);

        $supplierQuote = $this->supplierQuoteRepository->findOneWhere(['id' => $supplierQuoteItemId]);

        if ( isset($result) && $supplierQuote->status != 'Approved') {

            $this->supplierQuoteRepository->findOneWhere(['id' => $supplierQuoteItemId])->update(['status' => 'Answered']);
            $this->customerQuoteRepository->findOneWhere(['id' => $customerQuoteItemId])->update(['status' => 'Pending']);
        }

        session()->flash('success', trans('b2b_marketplace::app.shop.supplier.account.messages.success-sent'));

        return redirect()->back();
    }
}
