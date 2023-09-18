<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Messages;

use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\B2BMarketplace\Repositories\QuoteMessageRepository as QuoteMessage;
use Webkul\B2BMarketplace\Repositories\SupplierRepository as SupplierRepository;
use Webkul\B2BMarketplace\Repositories\SupplierQuoteItemRepository as SupplierQuoteRepository;
use Webkul\B2BMarketplace\Repositories\CustomerQuoteItemRepository as CustomerQuoteRepository;


/**
* Supplier Quote Message's Controller
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
     * Quote Message Repository object
     *
     * @var object
    */
    protected $quoteMessage;

    /**
     * Supplier Repository object
     *
     * @var object
    */
    protected $supplierRepository;

    /**
     * Supplier Quote Repository object
     *
     * @var object
    */
    protected $supplierQuoteRepository;

    /**
     * Customer Quote Repository object
     *
     * @var object
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
     * @return \Illuminate\Http\Response
     */
    public function store($supplierQuoteItemId, $supplierId, $customerQuoteItemId)
    {
        $data = request()->all();

        $data['supplier_id'] = $supplierId;
        $data['customer_quote_item_id'] = $customerQuoteItemId;
        $data['supplier_quote_item_id'] = $supplierQuoteItemId;

        if(ctype_space($data['message']) || $data['message'] == null){
            session()->flash('error', trans('b2b_marketplace::app.supplier.account.message.empty-msg'));
            return back();
        }

        $result = $this->quoteMessage->create($data);

        $supplierQuote = $this->supplierQuoteRepository->findOneWhere(['id' => $supplierQuoteItemId]);

        if ( isset($result) && $supplierQuote->status != 'Approved') {

            $this->supplierQuoteRepository->findOneWhere(['id' => $supplierQuoteItemId])->update(['status' => 'Pending']);

            $this->customerQuoteRepository->findOneWhere(['id' => $customerQuoteItemId])->update(['status' => 'Answered']);
        }

        session()->flash('success', trans('b2b_marketplace::app.supplier.account.message.create-success'));

        return redirect()->back();
    }
}
