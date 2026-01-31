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

    public function __construct(
        public Ticket $ticket,
        public string $action = 'updated' // 'created', 'replied', 'status_updated'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail']; // Podes adicionar 'database' se quiseres notificações no painel
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $notifiable->isAdmin() 
            ? route('admin.tickets.show', $this->ticket)
            : route('client.tickets.show', $this->ticket);

        return match ($this->action) {
            'created' => $this->buildCreatedMessage($url),
            'replied' => $this->buildRepliedMessage($url),
            'status_updated' => $this->buildStatusMessage($url),
            default => $this->buildDefaultMessage($url),
        };
    }

    private function buildCreatedMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject("Novo Chamado: #{$this->ticket->id} - {$this->ticket->subject}")
            ->greeting('Olá!')
            ->line('Um novo chamado foi aberto e requer atenção.')
            ->action('Ver Chamado', $url);
    }

    private function buildRepliedMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject("Nova Resposta no Chamado #{$this->ticket->id}")
            ->greeting('Olá!')
            ->line('Há uma nova resposta no seu chamado.')
            ->line("Assunto: {$this->ticket->subject}")
            ->action('Ver Resposta', $url);
    }

    private function buildStatusMessage($url): MailMessage
    {
        // Traduzir o status para algo legível se necessário, ou usar o value
        $statusName = $this->ticket->status->value ?? $this->ticket->status;

        return (new MailMessage)
            ->subject("Atualização de Status: Chamado #{$this->ticket->id}")
            ->greeting('Olá!')
            ->line("O status do seu chamado mudou para: **{$statusName}**.")
            ->action('Ver Detalhes', $url);
    }

    private function buildDefaultMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject("Atualização no Chamado #{$this->ticket->id}")
            ->line('Ocorreu uma atualização no seu chamado.')
            ->action('Acessar Chamado', $url);
    }
}