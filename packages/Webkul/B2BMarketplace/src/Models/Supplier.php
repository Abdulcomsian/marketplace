<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webkul\B2BMarketplace\Contracts\Supplier as SupplierContract;
use Webkul\B2BMarketplace\Notifications\SupplierResetPassword;
use Storage;

class Supplier extends Authenticatable implements SupplierContract
{
    use Notifiable;

    protected $table = 'b2b_marketplace_suppliers';

    protected $fillable = [ 'channel_id', 'first_name','last_name', 'email','password','url', 'is_approved',  'is_verified', 'company_name', 'token', 'date_of_birth', 'gender', 'role_id'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the customer full name.
     */
    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function addresses()
    {
        return $this->hasOne(SupplierAddressesProxy::modelClass(),'supplier_id');
    }

    /**
     * Get the order reviews record associated with the seller.
     */
    public function reviews()
    {
        return $this->hasMany(ReviewProxy::modelClass(), 'supplier_id');
    }

    /**
     * Get the order products record associated with the seller.
     */
    public function products()
    {
        return $this->hasMany(ProductProxy::modelClass(), 'supplier_id');
    }

    /**
     * Get the order orders record associated with the seller.
     */
    public function orders()
    {
        return $this->hasMany(OrderProxy::modelClass(), 'supplier_id');
    }

    /**
     * Get the supplier QuoteMessages.
     */
    public function getSupplierQuoteMessages()
    {
        return $this->hasMany(QuoteMessageProxy::modelClass(), 'supplier_id');
    }

    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SupplierResetPassword($token));
    }

    /**
     * Get logo image url.
     */
    public function logo_url()
    {
        if ($this->logo == null)
            return;

        return Storage::url($this->logo);
    }

     /**
     * Get logo image url.
     */
    public function profile_url()
    {
        if ($this->profile == null)
            return;

        return Storage::url($this->profile);
    }


    /**
     * Get the role that owns the admin.
     */
    public function role()
    {
        return $this->belongsTo(RoleProxy::modelClass());
    }

    /**
     * Checks if admin has permission to perform certain action.
     *
     * @param  String  $permission
     * @return Boolean
     */
    public function hasPermission($permission)
    {
        if ($this->role->permission_type == 'custom' && ! $this->role->permissions) {
            return false;
        }

        return in_array($permission, $this->role->permissions);
    }
}