<?php

namespace App\Services;

use App\Models\Ticket;
use App\Enums\TicketPriority;
use Carbon\Carbon;

class SlaService
{
    /**
     * Tempos de SLA em horas por prioridade
     */
    private const SLA_TIMES = [
        'HIGH' => 4,      // 4 horas
        'MEDIUM' => 24,   // 24 horas (1 dia)
        'LOW' => 72,      // 72 horas (3 dias)
    ];

    /**
     * Calcula e define o prazo de SLA para um ticket
     */
    public function calculateSla(Ticket $ticket): Carbon
    {
        $hours = self::SLA_TIMES[$ticket->priority->name] ?? 24;
        
        return now()->addHours($hours);
    }

    /**
     * Atualiza o SLA de um ticket ao ser criado
     */
    public function setSlaForTicket(Ticket $ticket): void
    {
        $ticket->update([
            'sla_due_at' => $this->calculateSla($ticket)
        ]);
    }

    /**
     * Verifica se o SLA está vencido
     */
    public function isSlaOverdue(Ticket $ticket): bool
    {
        if (!$ticket->sla_due_at) {
            return false;
        }

        // Se já foi resolvido, não considera vencido
        if ($ticket->resolved_at) {
            return false;
        }

        return now()->isAfter($ticket->sla_due_at);
    }

    /**
     * Retorna o tempo restante do SLA em formato legível
     */
    public function getSlaTimeRemaining(Ticket $ticket): ?string
    {
        if (!$ticket->sla_due_at || $ticket->resolved_at) {
            return null;
        }

        $now = now();
        $due = $ticket->sla_due_at;

        if ($now->isAfter($due)) {
            $diff = $now->diffInMinutes($due);
            return "Vencido há " . $this->formatMinutes($diff);
        }

        $diff = $now->diffInMinutes($due);
        return $this->formatMinutes($diff);
    }

    /**
     * Calcula o tempo de primeira resposta
     */
    public function calculateFirstResponseTime(Ticket $ticket): void
    {
        if ($ticket->first_response_at) {
            return; // Já foi calculado
        }

        $firstAdminMessage = $ticket->messages()
            ->whereHas('user', function($q) {
                $q->whereIn('role', ['admin', 'master']);
            })
            ->where('is_internal', false)
            ->oldest()
            ->first();

        if ($firstAdminMessage) {
            $responseTime = $ticket->created_at->diffInMinutes($firstAdminMessage->created_at);
            
            $ticket->update([
                'first_response_at' => $firstAdminMessage->created_at,
                'response_time_minutes' => $responseTime,
            ]);
        }
    }

    /**
     * Calcula o tempo de resolução
     */
    public function calculateResolutionTime(Ticket $ticket): void
    {
        if ($ticket->resolved_at) {
            $resolutionTime = $ticket->created_at->diffInMinutes($ticket->resolved_at);
            
            $ticket->update([
                'resolution_time_minutes' => $resolutionTime,
            ]);
        }
    }

    /**
     * Formata minutos em formato legível
     */
    private function formatMinutes(int $minutes): string
    {
        if ($minutes < 60) {
            return "{$minutes} minutos";
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours < 24) {
            return $remainingMinutes > 0 
                ? "{$hours}h {$remainingMinutes}min" 
                : "{$hours} horas";
        }

        $days = floor($hours / 24);
        $remainingHours = $hours % 24;

        return $remainingHours > 0 
            ? "{$days}d {$remainingHours}h" 
            : "{$days} dias";
    }

    /**
     * Retorna estatísticas de SLA para o dashboard
     */
    public function getSlaStats(): array
    {
        $openTickets = Ticket::whereNotIn('status', ['resolved', 'closed'])->get();

        $overdue = $openTickets->filter(fn($t) => $this->isSlaOverdue($t))->count();
        $dueToday = $openTickets->filter(function($t) {
            return $t->sla_due_at && $t->sla_due_at->isToday();
        })->count();

        $avgResponseTime = Ticket::whereNotNull('response_time_minutes')
            ->avg('response_time_minutes');

        $avgResolutionTime = Ticket::whereNotNull('resolution_time_minutes')
            ->avg('resolution_time_minutes');

        return [
            'overdue' => $overdue,
            'due_today' => $dueToday,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'avg_resolution_time' => round($avgResolutionTime ?? 0, 2),
        ];
    }
}
