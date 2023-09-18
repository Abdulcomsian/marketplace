<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\QuoteMessage as QuoteMessageContract;

class QuoteMessage extends Model implements QuoteMessageContract
{
    protected $table = 'b2b_marketplace_quote_messages';

    protected $fillable = ['message', 'supplier_id', 'customer_quote_item_id', 'supplier_quote_item_id','customer_id'];


    /**
     * Get Customer Messages.
     */
    public function customerMessage()
    {
        return $this->belognsTo(CustomerProxy::modelClass(), 'customer_id');
    }

}

