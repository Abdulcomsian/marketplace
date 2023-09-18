<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\SupplierFlagReason as SupplierFlagReasonContract;

class SupplierFlagReason extends Model implements SupplierFlagReasonContract
{
    protected $table = 'b2b_marketplace_supplier_flag_reasons';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}