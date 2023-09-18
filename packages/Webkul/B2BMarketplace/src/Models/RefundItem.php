<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\RefundItem as RefundItemContract;

/**
 * Supplier RefundItem Model
 *
 * @author    Naresh Verma <naresh.verma327@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RefundItem extends Model implements RefundItemContract
{
    public $timestamps = false;

    protected $table = 'b2b_marketplace_refund_items';

    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    /**
     * Get the item that belongs to the item.
     */
    public function item()
    {
        return $this->belongsTo(\Webkul\Sales\Models\RefundItemProxy::modelClass(), 'refund_item_id');
    }
}