<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Visita Técnica
 * 
 * Representa um agendamento presencial vinculado a um chamado.
 */
class TechnicalVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'scheduled_at',
        'status',
        'address',
        'notes',
        'client_feedback',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Chamado vinculado a esta visita.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Técnico responsável pela visita.
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Verifica se a visita está atrasada.
     */
    public function isOverdue()
    {
        return $this->scheduled_at->isPast() && $this->status === 'scheduled';
    }

    /**
     * Retorna a cor do status para a UI.
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'in_transit' => 'yellow',
            'in_service' => 'purple',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Retorna o label amigável do status.
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'scheduled' => 'Agendada',
            'in_transit' => 'Em Deslocamento',
            'in_service' => 'Em Atendimento',
            'completed' => 'Concluída',
            'cancelled' => 'Cancelada',
            default => 'Desconhecido',
        };
    }
}
