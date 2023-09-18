<?php

namespace Webkul\B2BMarketplace\Models;

use Webkul\Product\Models\ProductInventory as ProductInventoryBaseModel;
use Webkul\Product\Models\ProductProxy;
use Webkul\Inventory\Models\InventorySourceProxy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInventory extends ProductInventoryBaseModel
{
    public $timestamps = false;

    protected $fillable = ['qty', 'product_id', 'inventory_source_id', 'vendor_id', 'supplier'];

    /**
     * Get the product attribute family that owns the product.
     */
    public function inventory_source(): BelongsTo
    {
        return $this->belongsTo(InventorySourceProxy::modelClass());
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}