<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\User;
use App\Enums\TicketStatus;
use Illuminate\Support\Facades\DB;

class RateTicket
{
    public function execute(User $user, Ticket $ticket, array $data): Ticket
    {
        return DB::transaction(function () use ($user, $ticket, $data) {
            $ticket->update([
                'rating' => $data['rating'],
                'rating_comment' => $data['rating_comment'] ?? null,
                'status' => TicketStatus::CLOSED, // Fecha o ticket ao avaliar
            ]);

            // Se quiseres disparar um evento ou notificação de "TicketAvaliado", seria aqui.
            
            return $ticket;
        });
    }
}