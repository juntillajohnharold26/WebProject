<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceNotification extends Model
{
    protected $fillable = [
        'user_id',
        'template_listing_id',
        'type',
        'title',
        'body',
        'badge',
        'badge_class',
        'quantity',
        'total',
        'read_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total' => 'decimal:2',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function templateListing(): BelongsTo
    {
        return $this->belongsTo(TemplateListing::class);
    }
}
