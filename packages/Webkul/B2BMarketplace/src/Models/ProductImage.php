<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\B2BMarketplace\Contracts\ProductImage as ProductImageContract;

class ProductImage extends Model implements ProductImageContract
{
    protected $table = 'b2b_marketplace_product_images';

    public $timestamps = false;

    protected $fillable = ['path', 'b2b_marketplace_product_id'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'b2b_marketplace_product_id');
    }

    /**
     * Get image url for the product image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the product image.
     */
    public function getUrlAttribute()
    {
        return $this->url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['url'] = $this->url;

        return $array;
    }
}