<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 
        'asset_id', // ✅ ADICIONADO: Vínculo com equipamento
        'category',
        'subject', 
        'description', 
        'status', 
        'priority', 
        'rating', 
        'rating_comment',
        'nps_score', // ✅ ADICIONADO: Score NPS
        'is_escalated',
        'assigned_to', // ✅ ADICIONADO: Necessário para a atribuição funcionar
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'is_escalated' => 'boolean',
        'sla_due_at' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
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

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Visitas técnicas vinculadas a este chamado.
     */
    public function technicalVisits(): HasMany
    {
        return $this->hasMany(TechnicalVisit::class);
    }

    /**
     * Equipamento vinculado a este chamado.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Itens de checklist deste chamado.
     */
    public function checklists(): HasMany
    {
        return $this->hasMany(TicketChecklist::class)->orderBy('order');
    }

    /**
     * Pesquisa de satisfação vinculada a este chamado.
     */
    public function npsSurvey(): HasMany
    {
        return $this->hasMany(NpsSurvey::class);
    }

    // Relacionamento com Tags (polimórfico)
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // 🔍 SCOPES (Filtros e Queries)

    public function scopeFilter(Builder $query, array $filters): void
    {
        // Busca avançada: ID, subject, description, mensagens
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('subject', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
                
                if (is_numeric($search)) {
                    $subQ->orWhere('id', $search);
                }
                
                // Busca em mensagens
                $subQ->orWhereHas('messages', function($msgQ) use ($search) {
                    $msgQ->where('message', 'like', "%{$search}%");
                });
            });
        });

        // Filtro por status
        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });

        // Filtro por prioridade
        $query->when($filters['priority'] ?? null, function ($q, $priority) {
            $q->where('priority', $priority);
        });

        // Filtro por categoria
        $query->when($filters['category'] ?? null, function ($q, $category) {
            $q->where('category', $category);
        });

        // Filtro por responsável
        $query->when($filters['assigned_to'] ?? null, function ($q, $assignedTo) {
            $q->where('assigned_to', $assignedTo);
        });

        // Filtro por tags
        $query->when($filters['tag'] ?? null, function ($q, $tagId) {
            $q->whereHas('tags', function($tagQ) use ($tagId) {
                $tagQ->where('tags.id', $tagId);
            });
        });

        // Filtro por data de criação
        $query->when($filters['date_from'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '>=', $date);
        });

        $query->when($filters['date_to'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '<=', $date);
        });

        // Filtro por SLA vencido
        $query->when($filters['sla_overdue'] ?? false, function ($q) {
            $q->where('sla_due_at', '<', now())
              ->whereNotIn('status', [TicketStatus::RESOLVED->value, TicketStatus::CLOSED->value]);
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