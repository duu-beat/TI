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
        'category',
        'subject', 
        'description', 
        'status', 
        'priority', 
        'rating', 
        'rating_comment',
        'is_escalated',
        'assigned_to', // ✅ ADICIONADO: Necessário para a atribuição funcionar
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'is_escalated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ ADICIONADO: A relação que estava faltando e gerando o erro 500
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->latest();
    }

    // 🔍 SCOPES (Filtros e Queries)

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

    /**
     * Scope para pegar as estatísticas do dashboard de forma limpa.
     */
    public function scopeWithDashboardStats(Builder $query)
    {
        return $query->selectRaw("
            count(*) as total,
            sum(case when status in (?, ?, ?) then 1 else 0 end) as open,
            sum(case when status = ? then 1 else 0 end) as in_progress,
            sum(case when status in (?, ?) then 1 else 0 end) as resolved
        ", [
            TicketStatus::NEW->value, TicketStatus::IN_PROGRESS->value, TicketStatus::WAITING_CLIENT->value,
            TicketStatus::IN_PROGRESS->value,
            TicketStatus::RESOLVED->value, TicketStatus::CLOSED->value
        ]);
    }
}