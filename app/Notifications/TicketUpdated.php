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
            'sla_warning' => "⚠️ URGENTE: Chamado #{$this->ticket->id} próximo do vencimento",
            'sla_breached' => "🚨 CRÍTICO: SLA Violado no Chamado #{$this->ticket->id}",
            default => "Atualização no Chamado #{$this->ticket->id}",
        };

        $url = in_array($notifiable->role, ['admin', 'master'])
            ? route('admin.tickets.show', $this->ticket->id)
            : route('client.tickets.show', $this->ticket->id);

        $message = (new MailMessage)->subject($subject);

        if ($this->type === 'sla_warning') {
            $message->line("O chamado **#{$this->ticket->id}** está com 80% do tempo de SLA consumido.")
                    ->line("Prazo limite: **{$this->ticket->sla_due_at->format('d/m/Y H:i')}**");
        } elseif ($this->type === 'sla_breached') {
            $message->line("O SLA do chamado **#{$this->ticket->id}** foi violado.")
                    ->line("O chamado foi escalonado automaticamente para a equipe de Segurança.");
        } else {
            $message->line("Houve uma atualização no chamado: {$this->ticket->subject}");
        }

        return $message->action('Ver Chamado', $url);
    }
}