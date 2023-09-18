<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Role as RoleContract;
use Webkul\B2BMarketplace\Models\SupplierProxy;

class Role extends Model implements RoleContract
{

    protected $table = 'b2b_marketplace_supplier_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'permission_type',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get the admins.
     */
    public function admins()
    {
        return $this->hasMany(SupplierProxy::modelClass());
    }
}
