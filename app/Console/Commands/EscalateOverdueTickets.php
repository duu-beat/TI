<?php

namespace App\Console\Commands;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EscalateOverdueTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:escalate-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifica chamados com SLA vencido e realiza o escalonamento automático.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->processOverdueTickets();
        $this->processSlaWarnings();
    }

    /**
     * Processa chamados que já venceram o SLA
     */
    private function processOverdueTickets()
    {
        $overdueTickets = Ticket::query()
            ->whereIn('status', TicketStatus::openStatuses())
            ->whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->where(function ($query) {
                $query->where('is_escalated', false)
                    ->orWhere('priority', '!=', TicketPriority::HIGH);
            })
            ->get();

        if ($overdueTickets->isEmpty()) {
            $this->info('Nenhum chamado recém-vencido encontrado.');
            return;
        }

        $this->info("Escalonando {$overdueTickets->count()} chamados vencidos...");

        foreach ($overdueTickets as $ticket) {
            DB::transaction(function () use ($ticket) {
                $oldPriority = $ticket->priority->label();
                
                $ticket->update([
                    'is_escalated' => true,
                    'priority' => TicketPriority::HIGH,
                ]);

                $ticket->messages()->create([
                    'user_id' => null,
                    'is_internal' => true,
                    'message' => "🚨 **SISTEMA**: SLA violado em {$ticket->sla_due_at->format('d/m/Y H:i')}. Chamado escalonado automaticamente para Segurança."
                ]);

                Notification::send(User::admins()->get(), new TicketUpdated($ticket, 'sla_breached'));
            });
            $this->line("- Chamado #{$ticket->id} escalonado.");
        }
    }

    /**
     * Processa avisos preventivos para chamados próximos do vencimento
     */
    private function processSlaWarnings()
    {
        $slaService = app(\App\Services\SlaService::class);
        
        // Busca chamados que ainda não venceram, mas estão em status de 'warning'
        $openTickets = Ticket::query()
            ->whereIn('status', TicketStatus::openStatuses())
            ->whereNotNull('sla_due_at')
            ->where('sla_due_at', '>', now())
            ->where('sla_warning_sent', false)
            ->get();

        $warningCount = 0;
        foreach ($openTickets as $ticket) {
            if ($slaService->getSlaStatus($ticket) === 'warning') {
                $ticket->update(['sla_warning_sent' => true]);
                
                // Notificar responsável (se houver) ou todos os admins
                $notifiables = $ticket->assigned_to 
                    ? collect([$ticket->assignee]) 
                    : User::admins()->get();

                Notification::send($notifiables, new TicketUpdated($ticket, 'sla_warning'));
                $warningCount++;
            }
        }

        if ($warningCount > 0) {
            $this->info("Enviados {$warningCount} avisos preventivos de SLA.");
        }
    }
}
