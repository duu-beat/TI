<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AssetContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['asset_id', 'supplier_id', 'number', 'type', 'starts_at', 'expires_at', 'amount', 'auto_renew', 'invoice_number', 'notes'];
    protected function casts(): array
    {
        return ['starts_at' => 'date', 'expires_at' => 'date', 'amount' => 'decimal:2', 'auto_renew' => 'boolean'];
    }
    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function status(): string
    {
        if ($this->expires_at->lt(Carbon::today())) return 'expired';
        if ($this->expires_at->lte(Carbon::today()->addDays(90))) return 'expiring';
        return 'active';
    }
    public function statusLabel(): string { return __('assets.contracts.statuses.' . $this->status()); }
}
