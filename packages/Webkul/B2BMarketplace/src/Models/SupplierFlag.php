<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\SupplierFlag as SupplierFlagContract;

class SupplierFlag extends Model implements SupplierFlagContract
{
    protected $table = 'b2b_marketplace_supplier_flags';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
