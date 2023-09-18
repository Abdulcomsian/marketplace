<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Shipment as ShipmentContract;

class Shipment extends Model implements ShipmentContract
{
    protected $table = 'b2b_marketplace_shipments';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the order that belongs to the item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'b2b_marketplace_order_id');
    }

    /**
     * Get the shipment that belongs to the shipment.
     */
    public function shipment()
    {
        return $this->belongsTo(\Webkul\Sales\Models\ShipmentProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the shipment.
     */
    public function items()
    {
        return $this->hasMany(ShipmentItemProxy::modelClass(), 'b2b_marketplace_shipment_id');
    }
}