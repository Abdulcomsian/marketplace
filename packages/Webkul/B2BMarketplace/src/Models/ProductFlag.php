<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\ProductFlag as ProductFlagContract;

class ProductFlag extends Model implements ProductFlagContract
{
    protected $table = 'b2b_marketplace_product_flags';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
