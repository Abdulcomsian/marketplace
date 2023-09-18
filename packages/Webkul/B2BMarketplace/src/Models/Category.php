<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Category as CategoryContract;

class Category extends Model implements CategoryContract
{
    protected $table = 'b2b_marketplace_supplier_categories';

    protected $fillable = ['supplier_id', 'category_id', 'status'];

    /**
     * Get the Category that belongs to the Supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierProxy::modelClass(), 'supplier_id');
    }
}