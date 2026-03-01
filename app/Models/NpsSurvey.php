<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Representa uma pesquisa de satisfação (NPS) respondida pelo cliente após o fechamento de um chamado
 */
class NpsSurvey extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'score',
        'comment',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Chamado ao qual esta pesquisa de satisfação pertence
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Cliente que respondeu à pesquisa de satisfação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna a cor do score NPS para exibição na interface
     */
    public function getScoreColor(): string
    {
        return match(true) {
            $this->score >= 9 => 'emerald', // Promotores
            $this->score >= 7 => 'amber',   // Neutros
            default => 'red',               // Detratores
        };
    }

    /**
     * Retorna a categoria NPS do score
     */
    public function getCategory(): string
    {
        return match(true) {
            $this->score >= 9 => 'Promotor',
            $this->score >= 7 => 'Neutro',
            default => 'Detrator',
        };
    }
}
