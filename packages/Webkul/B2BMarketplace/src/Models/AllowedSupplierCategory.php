<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\AllowedSupplierCategory as AllowedSupplierCategoryContract;

class AllowedSupplierCategory extends Model implements AllowedSupplierCategoryContract
{
    use HasFactory;

    protected $table = 'b2b_marketplace_supplier_allowed_categories';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
