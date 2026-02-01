<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder; // Importante para o type hint

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'description', 'status', 'priority', 
        'rating', 'rating_comment'
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->latest();
    }

    // ✅ Scope de Filtro (Limpa o Controller)
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('subject', 'like', "%{$search}%")
                     ->orWhere('id', $search);
            });
        });

        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });
    }
}