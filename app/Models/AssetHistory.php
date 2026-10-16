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
        'old_branch_id',
        'new_branch_id',
        'old_sector_id',
        'new_sector_id',
        'signature', // Base64 da assinatura digital
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

    public function oldBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'old_branch_id');
    }

    public function newBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'new_branch_id');
    }

    public function oldSector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'old_sector_id');
    }

    public function newSector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'new_sector_id');
    }
}
