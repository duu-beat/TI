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
            $this->info('Nenhum chamado vencido encontrado.');
            return;
        }

        $this->info("Processando {$overdueTickets->count()} chamados vencidos...");

        foreach ($overdueTickets as $ticket) {
            DB::transaction(function () use ($ticket) {
                $oldPriority = $ticket->priority->label();
                
                // Escalonar e aumentar prioridade
                $ticket->update([
                    'is_escalated' => true,
                    'priority' => TicketPriority::HIGH,
                ]);

                // Registrar no histórico
                $ticket->messages()->create([
                    'user_id' => null, // Sistema
                    'is_internal' => true,
                    'message' => "🤖 **SISTEMA**: SLA vencido em {$ticket->sla_due_at->format('d/m/Y H:i')}. Chamado escalonado automaticamente e prioridade alterada de **{$oldPriority}** para **Alta**."
                ]);

                // Notificar Admins
                Notification::send(User::admins()->get(), new TicketUpdated($ticket, 'status_updated'));
            });

            $this->line("- Chamado #{$ticket->id} escalonado.");
        }

        $this->info('Escalonamento concluído.');
    }
}
