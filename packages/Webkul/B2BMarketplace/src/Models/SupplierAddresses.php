<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webkul\B2BMarketplace\Contracts\SupplierAddresses as SupplierAddressesContract;

class SupplierAddresses extends Authenticatable implements SupplierAddressesContract
{
    protected $table = 'b2b_marketplace_supplier_addresses';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = false;

    /**
     * Get logo image url.
     */
    public function logo_url()
    {
        if (! $this->logo)
            return;

        return Storage::url($this->logo);
    }

    /**
     * Get profile image url.
     */
    public function profile_url()
    {
        if (! $this->profile)
            return;

        return Storage::url($this->profile);
    }


    /**
     * Get logo image url.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_url();
    }

    /**
     * Get banner image url.
     */
    public function banner_url()
    {
        if (! $this->banner)
            return;

        return Storage::url($this->banner);
    }

    /**
     * Get banner image url.
     */
    public function getBannerUrlAttribute()
    {
        return $this->banner_url();
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}