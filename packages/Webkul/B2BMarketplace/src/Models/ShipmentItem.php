<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\ShipmentItem as ShipmentItemContract;

class ShipmentItem extends Model implements ShipmentItemContract
{
    public $timestamps = false;

    protected $table = 'b2b_marketplace_shipment_items';

    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Get the item that belongs to the item.
     */
    public function item()
    {
        return $this->belongsTo(\Webkul\Sales\Models\ShipmentItemProxy::modelClass(), 'b2b_shipment_item_id');
    }

    /**
     * Returns configurable option html
     */
    public function getOptionDetailHtml()
    {
        if ($this->type == 'configurable' && isset($this->additional['attributes'])) {
            $labels = [];

            foreach ($this->additional['attributes'] as $attribute) {
                $labels[] = $attribute['attribute_name'] . ' : ' . $attribute['option_label'];
            }

            return implode(', ', $labels);
        }
    }
}