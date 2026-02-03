<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Enums\TicketStatus;
use App\Notifications\TicketUpdated;
use App\Traits\HandleAttachments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CreateTicket
{
    use HandleAttachments;

    public function execute(User $user, array $data, $request): Ticket
    {
        return DB::transaction(function () use ($user, $data, $request) {
            // 1. Criar o Ticket
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'category' => $data['category'],
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? null,
                'status' => TicketStatus::NEW,
            ]);

            // 2. Criar a mensagem inicial
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $data['description'],
            ]);

            // 3. Processar Anexos
            $this->processAttachments($request, $message);

            // 4. Notificar Admins (Usa o scope criado no User.php)
            $admins = User::admins()->get();
            Notification::send($admins, new TicketUpdated($ticket, 'created'));

            return $ticket;
        });
    }
}