<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Invoice as InvoiceContract;

class Invoice extends Model implements InvoiceContract
{
    protected $table = 'b2b_marketplace_invoices';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the Invoice that belongs to the Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(\Webkul\Sales\Models\InvoiceProxy::modelClass(), 'invoice_id');
    }

    /**
     * Get the Invoice items record associated with the Invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItemProxy::modelClass(), 'b2b_marketplace_invoice_id');
    }

    /**
     * Get the order that belongs to the invocie.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'b2b_marketplace_order_id');
    }
}