<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Representa um equipamento ou ativo de TI (Notebook, Monitor, Impressora, etc.)
 */
class Asset extends Model
{
    use HasFactory, SoftDeletes, Searchable, Auditable;
    protected $fillable = [
        'user_id',
        'name',
        'tag',
        'serial_number',
        'type',
        'model',
        'brand',
        'purchase_date',
        'warranty_expiration',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiration' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Asset $asset): void {
            $asset->qr_token ??= (string) Str::uuid();
        });
    }

    /**
     * URL interna codificada no QR Code do ativo.
     */
    public function qrCodeUrl(): string
    {
        return route('admin.assets.scan', ['asset' => $this->qr_token]);
    }

    /**
     * Usuário ao qual este ativo está vinculado (ex: dono do notebook)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Chamados vinculados a este equipamento específico
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Histórico de movimentações deste equipamento
     */
    public function history(): HasMany
    {
        return $this->hasMany(AssetHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Retorna a cor do status para exibição na interface
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'active' => 'emerald',
            'maintenance' => 'amber',
            'retired' => 'slate',
            'lost' => 'red',
            default => 'slate',
        };
    }

    /**
     * Retorna o rótulo legível do status
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'active' => 'Ativo',
            'maintenance' => 'Em Manutenção',
            'retired' => 'Aposentado',
            'lost' => 'Extraviado',
            default => 'Desconhecido',
        };
    }
}
