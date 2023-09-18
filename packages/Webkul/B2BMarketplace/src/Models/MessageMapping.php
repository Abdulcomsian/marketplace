<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\MessageMapping as MessageMappingContract;
use Webkul\Customer\Models\CustomerProxy;

class MessageMapping extends Model implements MessageMappingContract
{
    protected $table = 'b2b_marketplace_message_mappings';

    protected $fillable = ['supplier_id', 'customer_id', 'created_at', 'updated_at'];

    /**
     * Messages belongs to threads.
     */
    public function messages()
    {
        return $this->hasMany(MessageProxy::modelClass(), 'message_id');
    }

    /**
     * Messages belongs to Customer.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'customer_id');
    }

    /**
     * Messages belongs to Customer.
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierProxy::modelClass(), 'supplier_id');
    }

}