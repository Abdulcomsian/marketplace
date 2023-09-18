<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Transaction as TransactionContract;

class Transaction extends Model implements TransactionContract
{
    protected $table = 'b2b_marketplace_transactions';

    protected $fillable = ['type', 'transaction_id', 'method', 'comment', 'total', 'base_total', 'supplier_id', 'b2b_marketplace_order_id'];

    /**
     * The orders that belong to the transaction.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'b2b_marketplace_order_id');
    }
}