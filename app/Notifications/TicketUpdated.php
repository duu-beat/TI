<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Importante
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdated extends Notification implements ShouldQueue // Implementar interface
{
    use Queueable; // Usar Trait

    public $ticket;
    public $type;

    public function __construct($ticket, $type)
    {
        $this->ticket = $ticket;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = match($this->type) {
            'created' => "Novo Chamado: #{$this->ticket->id} - {$this->ticket->subject}",
            'replied' => "Nova Resposta no Chamado #{$this->ticket->id}",
            'status_updated' => "Status Atualizado: Chamado #{$this->ticket->id}",
            default => "Atualização no Chamado #{$this->ticket->id}",
        };

        return (new MailMessage)
                    ->subject($subject)
                    ->line("Houve uma atualização no chamado: {$this->ticket->subject}")
                    ->action('Ver Chamado', route('client.tickets.show', $this->ticket->id));
    }
}