<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Quote as QuoteContract;
use Webkul\Product\Models\ProductFlat;

class Quote extends Model implements QuoteContract
{
    protected $table = 'b2b_marketplace_customer_quotes';

    protected $fillable = ['customer_id', 'quote_title', 'quote_brief', 'name', 'company_name', 'address', 'phone'];

    public function customerQuote()
    {
        return $this->hasMany(CustomerQuoteItemProxy::modelClass(), 'quote_id');
    }

    public function supplierQuote()
    {
        return $this->hasMany(SupplierQuoteItemProxy::modelClass(), 'quote_id');
    }

    /**
     * The images that belong to the Quote.
     */
    public function images()
    {
        return $this->hasMany(QuoteImageProxy::modelClass(), 'customer_quote_id');
    }

    /**
     * The Attachment that belong to the Quote.
     */
    public function attachments()
    {
        return $this->hasMany(QuoteAttachmentProxy::modelClass(), 'customer_quote_id');
    }


    public function products(){
        return $this->hasOne(ProductFlag::class, 'product_id', 'id');
    }

}