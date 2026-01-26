<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    // Recebemos o Ticket e o tipo de ação ('created', 'replied', 'status_updated')
    public function __construct(
        public Ticket $ticket, 
        public string $actionType
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Define para onde o botão vai apontar (Admin ou Cliente)
        $url = $notifiable->role === 'admin'
            ? route('admin.tickets.show', $this->ticket)
            : route('client.tickets.show', $this->ticket);

        return match($this->actionType) {
            'created' => (new MailMessage)
                ->subject('🎟️ Novo Chamado: #' . $this->ticket->id)
                ->greeting('Olá, Admin!')
                ->line('O cliente ' . $this->ticket->user->name . ' abriu um novo chamado.')
                ->line('Assunto: ' . $this->ticket->subject)
                ->action('Ver Chamado', $url),

            'replied' => (new MailMessage)
                ->subject('💬 Nova Resposta no Chamado #' . $this->ticket->id)
                ->greeting('Olá, ' . $notifiable->name)
                ->line('Houve uma nova interação no seu chamado.')
                ->action('Ver Conversa', $url),

            'status_updated' => (new MailMessage)
                ->subject('🔄 Status Atualizado: Chamado #' . $this->ticket->id)
                ->greeting('Olá, ' . $notifiable->name)
                ->line('O status do seu chamado mudou para: ' . $this->ticket->status->label())
                ->action('Acompanhar', $url),
        };
    }
}