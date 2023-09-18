<?php

namespace Webkul\B2BMarketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\B2BMarketplace\Contracts\Message as MessageContract;

class Message extends Model implements MessageContract
{
    protected $table = 'b2b_marketplace_messages';

    /**
     * Summary of fillable
     * Define fillable property here
     * @var mixed
     */
    protected $fillable = [
        'message', 
        'msg_type',
        'extension',
        'message_id', 
        'is_new',
        'role'
    ];

    /**
     * Get the Invoice that belongs to the Invoice.
     */
    public function messageThread()
    {
        return $this->belongsTo(MessageMappingProxy::modelClass(), 'id');
    }
}