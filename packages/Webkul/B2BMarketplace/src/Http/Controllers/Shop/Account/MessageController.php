<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Shop\Account;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Webkul\B2BMarketplace\Http\Controllers\Shop\Controller;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Repositories\MessageRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\B2BMarketplace\Repositories\MessageMappingRepository;
use Webkul\B2BMarketplace\Mail\NewCustomerMessageNotification;

/**
 * Customer's Messages Controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MessageController extends Controller
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
    protected $supplierRepository;

    /**
     * CustomerRepository object
     *
     * @var object
     */
    protected $customer;

    /**
     * MessageRepository object
     *
     * @var object
     */
    protected $messages;

    /**
     * MessageMappingRepository object
     *
     * @var object
     */
    protected $messageMapping;

    /**
     * Create a new Repository instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository     $supplierRepository
     * @param  Webkul\B2BMarketplace\Repositories\MessageRepository      $messages
     * @param  Webkul\B2BMarketplace\Repositories\MessageMappingRepository      $messageMapping
     * @param  Webkul\Customer\Repositories\CustomerRepository      $customer
     * @return void
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        CustomerRepository $customer,
        MessageRepository $messages,
        MessageMappingRepository $messageMapping
    ) {
        $this->_config = request('_config');

        $this->supplierRepository = $supplierRepository;

        $this->customer = $customer;

        $this->messages = $messages;

        $this->messageMapping = $messageMapping;
    }

    /**
     * Method to populate Messages Page.
     *
     * @return void
     */
    public function index()
    {
        $customer = auth()->guard('customer')->user()->id;

        $messages = $this->messageMapping->with('messages')->where('customer_id', $customer)->orderBy('updated_at', 'DESC')->get();

        foreach ($messages as $messageToSupplier) {
            $messageData = $messageToSupplier['messages']->last();

            $totalMessages = $messageToSupplier['messages']
                ->where('role', 'supplier')
                ->where('is_new', '1');

            $msgCount = count($totalMessages);

            $supplier = app('Webkul\B2BMarketplace\Repositories\SupplierRepository')->findOneWhere(['id' =>
            $messageToSupplier->supplier_id]);

            $supplierName = $supplier->first_name . ' ' . $supplier->last_name;

            $createdAt = date('M-d-Y', strtotime($messageData->created_at));

            $messageToSupplier->msgCount = $msgCount;
            $messageToSupplier->customerName = $supplierName;
            $messageToSupplier->createdAt = $createdAt;
            $messageToSupplier->message = $messageData->message;
            $messageToSupplier->role = $messageData->role;
        }

        return view($this->_config['view'])->with('sendedMessages', $messages);
    }

    /**
     * display the verification page resource.
     *
     * @return oject
     */
    public function show()
    {
        $messageThread = request()->input('messageId');

        $allMessage = $this->messageMapping->with('messages')->where('id', $messageThread)->first();

        foreach ($allMessage['messages'] as $message) {

            if ($message->role == 'supplier')
                $this->messages->update(['is_new' => 0], $message->id);
        }

        $messages = $allMessage['messages'];

        $customer = $this->customer->findOneWhere(['id' => $allMessage->customer_id]);
        $supplier = $this->supplierRepository->findOneWhere(['id' => $allMessage->supplier_id]);

        $supplierName = $supplier->first_name . ' ' . $supplier->last_name;
        $customerName = $customer->first_name . ' ' . $customer->last_name;

        $messages->supplierName = $supplierName;

        $supplierData['supplierName'] = $supplierName;
        $supplierData['messages'] = $messages;

        return response()->json($supplierData);
    }

    /**
     * store the new supplier message.
     *
     * @return object
     */
    public function store()
    {
        $data = request()->all();

        $this->messageMapping->where('id', $data['messageId'])->update([
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        $message['message_id'] = $data['messageId'];
        $message['message']    = $data['newMessage'];
        $message['msg_type']   = 'text';
        $message['role']       = 'customer';
        $message['is_new']     = 1;

        $newMessage = $this->messages->create($message);

        $thread = $this->messageMapping->where('id', $newMessage->message_id)->first();

        if (core()->getConfigData('b2b_marketplace.settings.general.chat_notification')) {

            try {
                Mail::send(new NewCustomerMessageNotification($newMessage, $thread));
            } catch (\Exception $e) {
            }
        }

        return response()->json($newMessage);
    }

    /**
     * @return array
     */
    public function uploadFiles()
    {

        if (!empty(request()->file)) {
            $file = request()->file('file');
            $fileSize = $file->getSize();
            $fileSizeInMb = number_format($fileSize / 1048576, 2);
            if ($fileSizeInMb <= 1) {
                $extension = $file->getClientOriginalExtension();
                $path = $file->store('chat/files');
                $data = request()->all();

                $this->messageMapping->where('id', $data['messageId'])->update([
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);

                $msgData = [
                    'message_id' => request()->messageId,
                    'sender_id'  => auth()->guard('customer')->user()->id,
                    'message'    => $path,
                    'role'       => 'customer',
                    'is_new'     => 1,
                    'msg_type'   => $extension,
                    'extension'  => $extension,
                ];
                $saveFileResponse = $this->messages->create($msgData);
                if ($saveFileResponse) {
                    return response()->json($saveFileResponse);
                } else {
                    return ['status' => 100, 'msg' => trans('velocity::app.error.something_went_wrong')];
                }
            } else {
                return ['status' => 100, 'msg' => trans('velocity::app.error.something_went_wrong')];
            }
        } else {
            return ['status' => 100, 'msg' => trans('velocity::app.error.something_went_wrong')];
        }
    }


    /**
     * store the Message from shop
     *
     * @return void
     */
    public function storeProductMsg()
    {
        $data = request()->all();

        if (auth()->guard('customer')->check()) {

            $isThread = $this->messageMapping->Where(['supplier_id' => $data['supplier_id'], 'customer_id' => $data['customer_id']])->get();

            if (empty($isThread) || count($isThread) < 1) {

                //No thread Available
                $newThread = $this->messageMapping->create($data);

                if (isset($newThread)) {
                    $message['message_id'] = $newThread->id;
                    $message['message'] = $data['message'];
                    $message['role'] = 'customer';
                    $message['is_new'] = 1;
                    $message['msg_type']   = 'text';
                    $newData = $this->messages->create($message);
                }

                if (core()->getConfigData('b2b_marketplace.settings.general.chat_notification')) {
                    try {
                        Mail::send(new NewCustomerMessageNotification($newData, $newThread));
                    } catch (\Exception $e) {
                    }
                }
            } else {
                $isThread = $isThread->first();

                //thread available
                $message['message_id'] = $isThread->id;
                $message['message'] = $data['message'];
                $message['role'] = 'customer';
                $message['is_new'] = 1;
                $message['msg_type']   = 'text';
                $newData = $this->messages->create($message);

                if (isset($newData)) {
                    $this->messageMapping->where('id', $isThread->id)->update([
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }

                if (core()->getConfigData('b2b_marketplace.settings.general.chat_notification')) {
                    try {
                        Mail::send(new NewCustomerMessageNotification($newData, $isThread));
                    } catch (\Exception $e) {
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => trans('b2b_marketplace::app.shop.account.message.success-sent')
            ]);
        } else {

            return response()->json([
                'success' => false,
                'message' => trans('b2b_marketplace::app.shop.account.message.login-to-sent')
            ]);
        }
    }

    /**
     * store the new supplier message.
     *
     * @return $requestedQuote
     */
    public function search()
    {
        $customerId = auth()->guard('customer')->user()->id;

        if (request()->all()) {
            $messages = [];

            foreach ($this->messages->searchSupplierMsg(request()->input('query'), $customerId) as $row) {

                $message = $this->messageMapping->where(['customer_id' => $row->customer_id, 'supplier_id' => $row->supplier_id])->first();

                $messages[] = $message;
            }

            foreach ($messages as $messageToSupplier) {
                $messageData = $messageToSupplier['messages']->last();

                $totalMessages = $messageToSupplier['messages']
                    ->where('role', 'supplier')
                    ->where('is_new', '1');

                $msgCount = count($totalMessages);

                $supplier = app('Webkul\B2BMarketplace\Repositories\SupplierRepository')->findOneWhere(['id' =>
                $messageToSupplier->supplier_id]);

                $supplierName = $supplier->first_name . ' ' . $supplier->last_name;

                $createdAt = date('M-d-Y', strtotime($messageData->created_at));

                $messageToSupplier->msgCount = $msgCount;
                $messageToSupplier->customerName = $supplierName;
                $messageToSupplier->createdAt = $createdAt;
                $messageToSupplier->message = $messageData->message;
            }
        }

        return response()->json($messages);
    }
}
