<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Http\Requests\ReplyTicketRequest;
use App\Notifications\TicketUpdated;
use App\Actions\Ticket\ReplyToTicket; // Reutilizando a Action
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Enums\TicketPriority;

class TicketController extends Controller
{
    public function dashboard()
    {
        // 👇 Mudei para '_v4' para ignorar qualquer cache antigo ou bugado
        $dashboardData = Cache::remember('admin_dashboard_stats_v4', 300, function () {
            
            // 1. Estatísticas Gerais (Compatível com MySQL e SQLite)
            $stats = Ticket::selectRaw("
                count(*) as total,
                sum(case when status in (?, ?, ?) then 1 else 0 end) as open,
                sum(case when status = ? then 1 else 0 end) as on_hold,
                sum(case when status = ? then 1 else 0 end) as resolved
            ", [
                TicketStatus::NEW->value, TicketStatus::IN_PROGRESS->value, TicketStatus::WAITING_CLIENT->value,
                TicketStatus::WAITING_CLIENT->value,
                TicketStatus::RESOLVED->value
            ])->first();

            // 2. Alerta de Prioridade Alta
            $highPriority = Ticket::where('priority', \App\Enums\TicketPriority::HIGH)
                ->whereIn('status', TicketStatus::openStatuses())
                ->count();

            // 3. Dados do Gráfico (Universal: Funciona em MySQL, SQLite, Postgres) 📊
            // Pega os últimos 7 dias
            $dates = collect(range(6, 0))->map(function ($daysAgo) {
                return now()->subDays($daysAgo)->format('d/m');
            });

            // ✅ CORREÇÃO: Busca tudo dos últimos 7 dias e agrupa via PHP
            // Isso evita o erro de 'DATE_FORMAT' no SQLite
            $ticketsPerDay = Ticket::where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->get() // Traz os dados para a memória (são leves, só os últimos 7 dias)
                ->groupBy(fn($ticket) => $ticket->created_at->format('d/m'))
                ->map->count();

            // Garante que dias sem chamados mostrem "0"
            $chartValues = $dates->map(fn($date) => $ticketsPerDay->get($date, 0));

            return [
                'stats' => $stats,
                'priorityStats' => ['high' => $highPriority],
                'chartLabels' => $dates->values(),
                'chartValues' => $chartValues->values(),
            ];
        });

        // Lista de recentes (sempre real-time)
        $latestTickets = Ticket::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $dashboardData['stats'],
            'priorityStats' => $dashboardData['priorityStats'],
            'chartLabels' => $dashboardData['chartLabels'],
            'chartValues' => $dashboardData['chartValues'],
            'latestTickets' => $latestTickets
        ]);
    }

    public function index(Request $request)
    {
        // ✅ EAGER LOADING: ->with('user') evita fazer uma query extra para cada linha
        $tickets = Ticket::with('user')
            ->filter($request->only(['search', 'status']))
            ->latest()
            ->paginate(15) // Paginação um pouco maior para admin
            ->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        // Carrega mensagens, anexos e quem enviou
        $ticket->load(['user', 'messages.user', 'messages.attachments']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => ['required', Rule::enum(TicketStatus::class)],
        ]);

        $ticket->update(['status' => $request->status]);

        // Notifica o cliente sobre a mudança (opcional, mas recomendado)
        // $ticket->user->notify(new TicketStatusChanged($ticket));

        // Limpa o cache do dashboard do admin e do cliente específico
        Cache::forget('admin_dashboard_stats');
        Cache::forget("dashboard_stats_{$ticket->user_id}");

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    // Reutiliza a lógica de resposta, mas com suporte a notas internas se precisar
    public function reply(ReplyTicketRequest $request, Ticket $ticket, ReplyToTicket $replier)
    {
        // Se você tiver campo de "nota interna" no form, valide aqui
        // Mas por padrão, vamos usar a Action principal
        $replier->execute($request->user(), $ticket, $request->validated(), $request);

        // Se o admin respondeu, geralmente muda o status para "Aguardando Cliente"
        if ($ticket->status !== TicketStatus::RESOLVED && $ticket->status !== TicketStatus::CLOSED) {
            $ticket->update(['status' => TicketStatus::WAITING_CLIENT]);
        }

        Cache::forget('admin_dashboard_stats');
        Cache::forget("dashboard_stats_{$ticket->user_id}");

        return back()->with('success', 'Resposta enviada!');
    }

    public function report()
    {
        // Exemplo simples de relatório sem cache (geralmente é em tempo real)
        $tickets = Ticket::with('user')->latest()->limit(500)->get();
        
        // Se usar PDF (DomPDF ou Snappy), a lógica viria aqui
        // return Pdf::loadView('admin.reports.tickets', compact('tickets'))->stream();
        
        return view('admin.reports.tickets', compact('tickets'));
    }

    // Adicione este método dentro da classe TicketController
public function escalate(Ticket $ticket)
{
    // Marca como escalonado
    $ticket->update([
        'is_escalated' => true,
        // Opcional: Adicionar uma nota interna automática
        // 'internal_notes' => $ticket->internal_notes . "\n[SISTEMA] Escalado para Segurança."
    ]);

    return back()->with('success', 'Chamado repassado para análise da equipe de Segurança (Master).');
}

}