<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AssetWarranty extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_MANUFACTURER = 'manufacturer';
    public const TYPE_SUPPLIER = 'supplier';
    public const TYPE_EXTENDED = 'extended';

    protected $fillable = [
        'asset_id', 'supplier_id', 'type', 'contract_number', 'invoice_number',
        'starts_at', 'expires_at', 'notes',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'date', 'expires_at' => 'date'];
    }

    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }

    public function status(): string
    {
        $today = Carbon::today();
        if ($this->expires_at->lt($today)) return 'expired';
        if ($this->expires_at->lte($today->copy()->addDays(90))) return 'expiring';
        return 'active';
    }

    public function statusLabel(): string
    {
        return __('assets.warranties.statuses.' . $this->status());
    }
}
