<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'template_listing_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function isThumbsUp(): bool
    {
        return $this->rating >= 4;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function templateListing(): BelongsTo
    {
        return $this->belongsTo(TemplateListing::class);
    }
}
