<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Notifications\TicketUpdated;

class UpdateTicketStatus
{
    public function execute(Ticket $ticket, TicketStatus $newStatus): void
    {
        if ($ticket->status === $newStatus) {
            return;
        }

        $ticket->update(['status' => $newStatus]);

        // Notificar o cliente sobre a mudança
        $ticket->user->notify(new TicketUpdated($ticket, 'status_updated'));
    }
}