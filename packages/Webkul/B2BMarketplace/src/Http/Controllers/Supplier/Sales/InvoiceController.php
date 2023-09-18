<?php

namespace Webkul\B2BMarketplace\Http\Controllers\Supplier\Sales;

use PDF;
use Illuminate\Http\Request;
use Webkul\B2BMarketplace\Repositories\OrderRepository;
use Webkul\B2BMarketplace\Repositories\InvoiceRepository;
use Webkul\B2BMarketplace\Repositories\SupplierRepository;
use Webkul\B2BMarketplace\Http\Controllers\Supplier\Controller;
use Webkul\Sales\Repositories\InvoiceRepository as BaseInvoiceRepository;

/**
 * Invoice controller
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InvoiceController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var object
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $order;

    /**
     * InvoiceRepository object
     *
     * @var mixed
     */
    protected $invoice;

    /**
     * SupplierRepository object
     *
     * @var mixed
     */
    protected $supplier;

    /**
     * InvoiceRepository object
     *
     * @var mixed
     */
    protected $baseInvoice;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\B2BMarketplace\Repositories\OrderRepository   $order
     * @param  Webkul\B2BMarketplace\Repositories\InvoiceRepository $invoice
     * @param  Webkul\B2BMarketplace\Repositories\SupplierRepository  $supplier
     * @param  Webkul\Sales\Repositories\InvoiceRepository       $baseInvoice
     * @return void
     */
    public function __construct(
        OrderRepository $order,
        InvoiceRepository $invoice,
        SupplierRepository $supplier,
        BaseInvoiceRepository $baseInvoice
    )
    {
        $this->order = $order;

        $this->invoice = $invoice;

        $this->supplier = $supplier;

        $this->baseInvoice = $baseInvoice;

        $this->_config = request('_config');
    }

    /**
    * Show the view for the specified resource.
    *
    * @param  int  $orderId
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view($this->_config(['view']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
    public function create($orderId)
    {
        if (! core()->getConfigData('b2b_marketplace.settings.general.can_create_invoice')) {

            abort(404);
        }

        $supplierId = auth()->guard('supplier')->user()->id;

        $supplierOrder = $this->order->findOneWhere([
            'order_id' => $orderId,
            'supplier_id' => $supplierId
        ]);

        return view($this->_config['view'], compact('supplierOrder'));
    }

    /**
     * store invoice order
     *
      * @param int $orderId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $orderId)
    {
        $supplierId = auth()->guard('supplier')->user()->id;

        $supplierOrder = $this->order->findOneWhere([
            'order_id' => $orderId,
            'supplier_id' => $supplierId
        ]);

        if (! $supplierOrder->canInvoice()) {
            session()->flash('error', 'Order invoice creation is not allowed.');

            return redirect()->back();
        }

        $this->validate(request(), [
            'invoice.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        $haveProductToInvoice = false;
        foreach ($data['invoice']['items'] as $itemId => $qty) {
            if ($qty) {
                $haveProductToInvoice = true;
                break;
            }
        }

        if (! $haveProductToInvoice) {
            session()->flash('error', 'Invoice can not be created without products.');

            return redirect()->back();
        }

        $this->baseInvoice->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', 'Invoice created successfully.');

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $invoice = $this->baseInvoice->findOrFail($id);

        return view($this->_config['view'], compact('invoice'));
    }

    /**
     * Print and download the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $supplierInvoice = $this->invoice->findOneByField('invoice_id', $id);

        $pdf = PDF::loadView('b2b_marketplace::supplier.sales.invoices.pdf', compact('supplierInvoice'))->setPaper('a4');

        return $pdf->download('invoice-' . $supplierInvoice->invoice->created_at->format('d-m-Y') . '.pdf');
    }
}
