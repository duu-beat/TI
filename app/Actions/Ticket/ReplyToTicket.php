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

class ReplyToTicket
{
    use HandleAttachments;

    public function execute(User $user, Ticket $ticket, array $data, $request): TicketMessage
    {
        return DB::transaction(function () use ($user, $ticket, $data, $request) {
            
            // 1. Criar a mensagem
            $message = $ticket->messages()->create([
                'user_id' => $user->id,
                'message' => $data['message'],
                'is_internal' => $data['is_internal'] ?? false, // Suporta notas internas
            ]);

            // 2. Processar Anexos
            $this->processAttachments($request, $message);

            // 3. Atualizar Status e Notificar
            // Lógica para Cliente respondendo
            if ($user->isClient()) {
                if (in_array($ticket->status, [TicketStatus::WAITING_CLIENT, TicketStatus::RESOLVED])) {
                    $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
                }
                // Notifica Admins
                Notification::send(User::admins()->get(), new TicketUpdated($ticket, 'replied'));
            } 
            // Lógica para Admin respondendo (se não for nota interna)
            elseif (!($data['is_internal'] ?? false)) {
                if ($ticket->status === TicketStatus::NEW) {
                    $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
                }
                // Notifica o Cliente
                $ticket->user->notify(new TicketUpdated($ticket, 'replied'));
            }

            return $message;
        });
    }
}