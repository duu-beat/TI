<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Representa um item de checklist que deve ser marcado pelo técnico durante o atendimento de um chamado
 */
class TicketChecklist extends Model
{
    protected $fillable = [
        'ticket_id',
        'task',
        'is_completed',
        'completed_at',
        'completed_by',
        'order',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Chamado ao qual este item de checklist pertence
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Técnico que marcou este item como concluído
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
