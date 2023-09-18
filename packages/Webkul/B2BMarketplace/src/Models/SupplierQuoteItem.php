<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\SupplierQuoteItem as SupplierQuoteItemContract;

class SupplierQuoteItem extends Model implements SupplierQuoteItemContract
{
    protected $table = 'b2b_marketplace_supplier_quote_items';

    protected $fillable = [
        'status', 'quantity', 'description', 'is_requested_quote', 'product_id', 'quote_id', 'price_per_quantity', 'is_sample', 'sample_unit', 'is_sample_price', 'sample_price', 'note', 'supplier_id', 'customer_id', 'shipping_time', 'is_approve', 'is_ordered'
    ];

    public function products()
    {
        return $this->belongsTo(ProductFlag::class, 'product_id');
    }
}
