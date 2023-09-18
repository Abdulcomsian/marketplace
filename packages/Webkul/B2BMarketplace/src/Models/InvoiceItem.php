<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\InvoiceItem as InvoiceItemContract;

class InvoiceItem extends Model implements InvoiceItemContract
{
    public $timestamps = false;

    protected $table = 'b2b_marketplace_invoice_items';

    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    /**
     * Get the item that belongs to the invoice.
     */
    public function item()
    {
        return $this->belongsTo(\Webkul\Sales\Models\InvoiceItemProxy::modelClass(), 'invoice_item_id');
    }
}