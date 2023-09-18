<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\B2BMarketplace\Contracts\QuoteAttachment as QuoteAttachmentContract;

class QuoteAttachment extends Model implements QuoteAttachmentContract
{
    protected $table = 'b2b_marketplace_quote_attachments';

    public $timestamps = false;

    protected $fillable = ['path', 'type', 'customer_quote_id'];


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