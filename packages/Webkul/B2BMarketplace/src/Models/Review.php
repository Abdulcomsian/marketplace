<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Review as ReviewContract;
use Webkul\Customer\Models\Customer;

class Review extends Model implements ReviewContract
{
    protected $table = 'b2b_marketplace_supplier_reviews';

    protected $guarded = ['_token'];

    /**
     * Get the supplier that belongs to the review.
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierProxy::modelClass(), 'supplier_id');
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}