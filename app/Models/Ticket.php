<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 
        'subject', 
        'description', 
        'status', 
        'priority', 
        'rating', 
        'rating_comment',
        'is_escalated', // 👈 ADICIONE ISSO AQUI
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'is_escalated' => 'boolean', // Opcional, mas recomendado para garantir que venha como true/false
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->latest();
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('subject', 'like', "%{$search}%");
                
                if (is_numeric($search)) {
                    $subQ->orWhere('id', $search);
                }
            });
        });

        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });
    }
}