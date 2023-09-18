<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\ProductFlagReason as ProductFlagReasonContract;

class ProductFlagReason extends Model implements ProductFlagReasonContract
{
    protected $table = 'b2b_marketplace_product_flag_reasons';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}