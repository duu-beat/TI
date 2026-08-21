<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetResponsibilityTerm extends Model
{
    use HasFactory;

    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_RETURN = 'return';

    public const STATUS_PENDING = 'pending';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'asset_id',
        'recipient_id',
        'issued_by',
        'type',
        'status',
        'terms_text',
        'signature_path',
        'signature_hash',
        'pdf_path',
        'signed_at',
        'signed_ip',
        'signed_user_agent',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSigned(): bool
    {
        return $this->status === self::STATUS_SIGNED;
    }

    public function typeLabel(): string
    {
        return $this->type === self::TYPE_RETURN ? 'Devolução de ativo' : 'Entrega de ativo';
    }
}
