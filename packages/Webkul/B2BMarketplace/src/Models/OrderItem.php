<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\OrderItem as OrderItemContract;

class OrderItem extends Model implements OrderItemContract
{
    public $timestamps = false;

    protected $table = 'b2b_marketplace_order_items';

    protected $guarded = ['_token'];

    /**
     * Get the item that belongs to the item.
     */
    public function item()
    {
        return $this->belongsTo(\Webkul\Sales\Models\OrderItemProxy::modelClass(), 'order_item_id');
    }

    /**
     * Get the order that belongs to the item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'b2b_marketplace_order_id');
    }

    /**
     * Get the product that belongs to the item.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'b2b_marketplace_product_id');
    }

    /**
     * Get the child item record associated with the order item.
     */
    public function child()
    {
        return $this->hasOne(OrderItemProxy::modelClass(), 'parent_id');
    }
}