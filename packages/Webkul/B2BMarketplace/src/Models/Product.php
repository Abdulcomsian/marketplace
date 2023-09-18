<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Product as ProductContract;
use Webkul\Product\Models\Product as BaseProduct;
use Webkul\Product\Models\ProductVideoProxy as CoreVideoProxy;

class Product extends Model implements ProductContract
{
    protected $table = 'b2b_marketplace_products';

    protected $fillable = ['condition', 'description', 'price', 'supplier_id', 'parent_id', 'product_id', 'is_owner', 'is_approved', 'quote_product_id'];

    /**
     * Get the product that belongs to the product.
     */
    public function product()
    {
        return $this->belongsTo(BaseProduct::class);
    }

    /**
     * Get the product that belongs to the seller.
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierProxy::modelClass(), 'supplier_id');
    }

    /**
     * The images that belong to the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImageProxy::modelClass(), 'b2b_marketplace_product_id');
    }

    /**
     * The videos that belong to the product.
     */
    public function videos()
    {
        return $this->hasMany(CoreVideoProxy::modelClass(), 'product_id');
    }

    /**
     * The videos that belong to the product.
     */
    public function assignVideos()
    {
        return $this->hasMany(ProductVideoProxy::modelClass(), 'b2b_marketplace_product_id');
    }


    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSaleable()
    {
        if (! $this->product->status && $this->is_approved)
            return false;

        if ($this->haveSufficientQuantity(1))
            return true;

        return false;
    }

    /**
     * @param integer $qty
     *
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('status', 1)
                ->pluck('id');


        foreach ($this->product->inventories as $inventory) {

            if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id)) && $this->supplier->id == $inventory->vendor_id) {
                $total += $inventory->qty;
            }
        }

        if (!$total) {
            return false;
        }

        $orderedInventory = $this->product->ordered_inventories()
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->first();

        if ($orderedInventory) {
            $total -= $orderedInventory->qty;
        }

        $backorders = core()->getConfigData('catalog.inventory.stock_options.backorders');

        $backorders = ! is_null ($backorders) ? $backorders : false;

        return $qty <= $total ? true :  $backorders;
    }

    /**
     * Get the product that owns the product.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}