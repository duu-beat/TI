<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHistory extends Model
{
    protected $fillable = [
        'asset_id',
        'user_id',
        'action',
        'description',
        'old_status',
        'new_status',
        'old_user_id',
        'new_user_id',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oldUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'old_user_id');
    }

    public function newUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'new_user_id');
    }
}
