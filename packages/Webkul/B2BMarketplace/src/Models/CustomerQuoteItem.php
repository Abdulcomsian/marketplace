<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\CustomerQuoteItem as QuoteItemContract;
use Webkul\Product\Models\ProductFlat;

class CustomerQuoteItem extends Model implements QuoteItemContract
{
    protected $table = 'b2b_marketplace_customer_quote_items';

    protected $fillable = [
        'status', 'quote_status', 'quantity', 'description', 'is_requested_quote', 'product_id', 'quote_id', 'price_per_quantity', 'is_sample', 'sample_unit', 'is_sample_price', 'sample_price', 'product_name', 'note', 'supplier_id', 'customer_id', 'shipping_time', 'is_approve', 'categories'
    ];

    public function products()
    {
        return $this->belongsTo(ProductFlat::class, 'product_id');
    }
}
