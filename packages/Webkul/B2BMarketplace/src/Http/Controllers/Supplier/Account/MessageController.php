<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Account;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\B2BMarketplace\Repositories\MessageRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Models\MessageMapping;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\B2BMarketplace\Mail\NewSupplierMessageNotification;

/**
 * Supplier Message's Controller
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
    protected $supplier;

    /**
     * CustomerRepository object
     *
     * @var object
     */
    protected $customer;

    /**
     * Message Repository object
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
        MessageRepository $messages,
        SupplierRepository $supplier,
        CustomerRepository $customer,
        MessageMapping $messageMapping
    ) {
        $this->messages = $messages;

        $this->supplier = $supplier;

        $this->customer = $customer;

        $this->messageMapping = $messageMapping;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = auth()->guard('supplier')->user()->id;

        $messages = $this->messageMapping->with('messages')->where('supplier_id', $supplier)->orderBy('id', 'DESC')->get();

        foreach ($messages as $messageToCustomer) {
            $messageData = $messageToCustomer['messages']->last();
            $totalMessages = $messageToCustomer['messages']
                ->where('role', 'customer')
                ->where('is_new', '1');

            $msgCount = count($totalMessages);

            $customer = $this->customer->findOneWhere(['id' =>
            $messageToCustomer->customer_id]);

            $customerName = $customer->first_name . ' ' . $customer->last_name;

            $createdAt = date('M-d-Y', strtotime($messageData->created_at));

            $messageToCustomer->msgCount = $msgCount;
            $messageToCustomer->customerName = $customerName;
            $messageToCustomer->createdAt = $createdAt;
            $messageToCustomer->message = $messageData->message;
            $messageToCustomer->role = $messageData->role;
        }

        return view($this->_config['view'])->with('sendedMessages', $messages);
    }

    /**
     * display the verification page resource.
     *
     * @return $requestedQuote
     */
    public function show()
    {
        $messageThread = request()->input('messageId');

        $allMessage = $this->messageMapping->with('messages')->where('id', $messageThread)->first();

        foreach ($allMessage['messages'] as $message) {

            if ($message->role == 'customer')
                $this->messages->update(['is_new' => 0], $message->id);
        }

        $messages = $allMessage['messages'];

        $customer = $this->customer->findOneWhere(['id' => $allMessage->customer_id]);
        $customerName = $customer->first_name . ' ' . $customer->last_name;

        $supplierData['customerName'] = $customerName;
        $supplierData['messages'] = $messages;

        return response()->json($supplierData);
    }

    /**
     * store the new supplier message.
     *
     * @return $requestedQuote
     */
    public function store()
    {
        $data = request()->all();
        if (ctype_space($data['newMessage']) || $data['newMessage'] == null) {

            return response()->json('error');
        }

        $supplier = auth()->guard('supplier')->user()->id;
        $messages = $this->messageMapping->with('messages')->where(['id' => $data['messageId'], 'supplier_id' => $supplier])->first();
        if (!$messages) {
            return response()->json('error');
        }

        $this->messageMapping->where('id', $data['messageId'])->update([
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        $message['message_id']  = $data['messageId'];
        $message['message']     = $data['newMessage'];
        $message['role']        = 'supplier';
        $message['is_new']      = 1;
        $message['msg_type']    = 'text';

        $newData = $this->messages->create($message);

        $thread = $this->messageMapping->where('id', $newData->message_id)->first();

        if (core()->getConfigData('b2b_marketplace.settings.general.chat_notification')) {

            try {
                Mail::send(new NewSupplierMessageNotification($newData, $thread));
            } catch (\Exception $e) {
            }
        }

        return response()->json($newData);
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
                    'sender_id' => auth()->guard('supplier')->user()->id,
                    'message' => $path,
                    'role' => 'supplier',
                    'is_new' => 1,
                    'msg_type' => $extension,
                    'extension' => $extension,
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
     * store the new supplier message.
     *
     * @return $requestedQuote
     */
    public function search()
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        if (request()->all()) {
            $messages = [];

            foreach ($this->messages->searchCustomerMsg(request()->input('query'), $supplierId) as $row) {

                $message = $this->messageMapping->where(['customer_id' => $row->customer_id, 'supplier_id' => $row->supplier_id])->first();

                $messages[] = $message;
            }

            foreach ($messages as $messageToCustomer) {
                $messageData = $messageToCustomer['messages']->last();
                $totalMessages = $messageToCustomer['messages']
                    ->where('role', 'customer')
                    ->where('is_new', '1');

                $msgCount = count($totalMessages);

                $customer = $this->customer->findOneWhere(['id' =>
                $messageToCustomer->customer_id]);

                $customerName = $customer->first_name . ' ' . $customer->last_name;

                $createdAt = date('M-d-Y', strtotime($messageData->created_at));

                $messageToCustomer->msgCount = $msgCount;
                $messageToCustomer->customerName = $customerName;
                $messageToCustomer->createdAt = $createdAt;
                $messageToCustomer->message = $messageData->message;
            }
        }

        return response()->json($messages);
    }
}
