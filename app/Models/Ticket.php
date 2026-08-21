<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;
use App\Traits\Auditable;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, Searchable, Auditable;
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
        'sla_due_at',
        'sla_warning_sent',
        'first_response_at',
        'resolved_at',
        'response_time_minutes',
        'resolution_time_minutes',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'is_escalated' => 'boolean',
        'sla_due_at' => 'datetime',
        'sla_warning_sent' => 'boolean',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    protected $appends = ['sla_status', 'sla_remaining'];

    public function getSlaStatusAttribute()
    {
        return app(\App\Services\SlaService::class)->getSlaStatus($this);
    }

    public function getSlaRemainingAttribute()
    {
        return app(\App\Services\SlaService::class)->getSlaTimeRemaining($this);
    }

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
        // Busca avançada usando a Trait Searchable
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $columns = ['subject', 'description', 'messages.message'];
            
            if (is_numeric($search)) {
                $q->where('id', $search)->orSearch($search, $columns);
            } else {
                $q->search($search, $columns);
            }
        });

        // Filtro por status
        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });

        // Filtro por prioridade
        $query->when($filters['priority'] ?? null, function ($q, $priority) {
            $q->where('priority', $priority);
        });

        // Mantém alertas operacionais restritos aos chamados ainda abertos.
        $query->when($filters['open_only'] ?? false, function ($q) {
            $q->whereIn('status', TicketStatus::openStatuses());
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