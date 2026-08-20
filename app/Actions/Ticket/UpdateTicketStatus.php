<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Notifications\TicketUpdated;
use App\Services\SlaService;
use Illuminate\Support\Facades\DB;

class UpdateTicketStatus
{
    public function execute(Ticket $ticket, TicketStatus $newStatus): void
    {
        if ($ticket->status === $newStatus) {
            return;
        }

        $slaService = app(SlaService::class);

        DB::transaction(function () use ($ticket, $newStatus, $slaService) {
            // Se está sendo marcado como resolvido ou fechado, registrar o timestamp
            if (in_array($newStatus, [TicketStatus::RESOLVED, TicketStatus::CLOSED]) && !$ticket->resolved_at) {
                $ticket->update([
                    'status' => $newStatus,
                    'resolved_at' => now(),
                ]);
                
                // Calcular tempo de resolução
                $slaService->calculateResolutionTime($ticket);
            } elseif (!in_array($newStatus, [TicketStatus::RESOLVED, TicketStatus::CLOSED])) {
                $ticket->update([
                    'status' => $newStatus,
                    'resolved_at' => null,
                ]);
            } else {
                $ticket->update(['status' => $newStatus]);
            }

            // Notificar o cliente sobre a mudança
            $ticket->user->notify(new \App\Notifications\TicketUpdated($ticket, 'status_updated'));
        });
    }
}