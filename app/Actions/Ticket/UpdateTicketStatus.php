<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Notifications\TicketUpdated;
use App\Services\SlaService;

class UpdateTicketStatus
{
    public function execute(Ticket $ticket, TicketStatus $newStatus): void
    {
        if ($ticket->status === $newStatus) {
            return;
        }

        $slaService = app(SlaService::class);

        // Se está sendo marcado como resolvido, registrar o timestamp
        if ($newStatus === TicketStatus::RESOLVED && !$ticket->resolved_at) {
            $ticket->update([
                'status' => $newStatus,
                'resolved_at' => now(),
            ]);
            
            // Calcular tempo de resolução
            $slaService->calculateResolutionTime($ticket);
        } else {
            $ticket->update(['status' => $newStatus]);
        }

        // Notificar o cliente sobre a mudança
        $ticket->user->notify(new TicketUpdated($ticket, 'status_updated'));
    }
}