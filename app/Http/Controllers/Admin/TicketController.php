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
use App\Models\CannedResponse; // Importar
use App\Models\User;            // <--- ADICIONE ESTA LINHA


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
        // 5. Histórico do Cliente (Carregamos dados extras)
        $ticket->load(['user', 'messages.user', 'messages.attachments', 'assignee']);

        // Buscamos o histórico do cliente
        $clientHistory = [
            'total_tickets' => Ticket::where('user_id', $ticket->user_id)->count(),
            'last_ticket' => Ticket::where('user_id', $ticket->user_id)
                                ->where('id', '!=', $ticket->id)
                                ->latest()
                                ->first(),
        ];

        // Carregamos respostas prontas para a View
        $cannedResponses = CannedResponse::all();

        // Carregamos lista de admins para atribuição
        $admins = User::whereIn('role', ['admin', 'master'])->get();

        return view('admin.tickets.show', compact('ticket', 'clientHistory', 'cannedResponses', 'admins'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
{
    $request->validate(['status' => ['required', Rule::enum(TicketStatus::class)]]);

    $oldStatus = $ticket->status->label();
    $ticket->update(['status' => $request->status]);
    $newStatus = $ticket->status->label();

    // 🚀 ADIÇÃO: Registrar log na timeline
    $ticket->messages()->create([
        'user_id' => auth()->id(),
        'is_internal' => true,
        'message' => "⚡ alterou o status de **{$oldStatus}** para **{$newStatus}**."
    ]);

    // Limpa caches (se houver)
    // Cache::forget(...) 

    return back()->with('success', 'Status atualizado.');
}

    // Reutiliza a lógica de resposta, mas com suporte a notas internas se precisar
    // 1. Lógica de Resposta com Nota Interna e Time Tracking
    public function reply(Request $request, Ticket $ticket, ReplyToTicket $replier)
    {
        $validated = $request->validate([
            'message' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240'],
            'is_internal' => ['boolean'], // Checkbox de nota interna
            'time_spent' => ['nullable', 'integer', 'min:0'], // Minutos gastos
        ]);

        // Se for NOTA INTERNA, criamos manualmente para não disparar e-mail ao cliente
        if ($request->boolean('is_internal')) {
            $ticket->messages()->create([
                'user_id' => auth()->id(),
                'message' => $validated['message'],
                'is_internal' => true,
                'time_spent' => $request->input('time_spent', 0),
            ]);
            
            return back()->with('success', 'Nota interna adicionada (Cliente não vê).');
        }

        // Se for resposta normal, usa sua Action existente
        // (Você precisará passar o time_spent para sua Action se quiser salvar lá também)
        $replier->execute($request->user(), $ticket, $validated, $request);

        if ($ticket->status !== TicketStatus::RESOLVED && $ticket->status !== TicketStatus::CLOSED) {
            $ticket->update(['status' => TicketStatus::WAITING_CLIENT]);
        }

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
    $ticket->update(['is_escalated' => true]);

    // 🚀 ADIÇÃO: Registrar log na timeline
    $ticket->messages()->create([
        'user_id' => auth()->id(),
        'is_internal' => true,
        'message' => "🚨 **ESCALONOU** este chamado para a equipe de Segurança."
    ]);

    return back()->with('success', 'Escalonado com sucesso.');
}

// 3. Atribuição de Chamado
    public function assign(Request $request, Ticket $ticket)
{
    $request->validate(['assigned_to' => 'required|exists:users,id']);

    $user = User::find($request->assigned_to);
    $ticket->update(['assigned_to' => $request->assigned_to]);

    // 🚀 ADIÇÃO: Registrar log na timeline
    $ticket->messages()->create([
        'user_id' => auth()->id(),
        'is_internal' => true,
        'message' => "👤 atribuiu este chamado para **{$user->name}**."
    ]);

    return back()->with('success', 'Chamado atribuído.');
}

    // 6. Fusão de Chamados (Merge)
    public function merge(Request $request, Ticket $ticket)
    {
        $request->validate(['target_ticket_id' => 'required|exists:tickets,id']);

        $targetTicket = Ticket::find($request->target_ticket_id);

        if ($targetTicket->id === $ticket->id) {
            return back()->with('error', 'Não pode fundir o chamado com ele mesmo.');
        }

        // Move mensagens
        $ticket->messages()->update(['ticket_id' => $targetTicket->id]);
        
        // Move anexos (se tiver tabela separada, faça o update nela também)
        // TicketAttachment::where('ticket_id', $ticket->id)->update(['ticket_id' => $targetTicket->id]);

        // Adiciona nota no chamado de destino
        $targetTicket->messages()->create([
            'user_id' => auth()->id(),
            'message' => "Sistema: As mensagens do chamado #{$ticket->id} foram movidas para cá.",
            'is_internal' => true,
        ]);

        // Fecha o chamado antigo
        $ticket->update(['status' => TicketStatus::CLOSED]);

        return redirect()->route('admin.tickets.show', $targetTicket)
            ->with('success', "Chamados fundidos. O ticket #{$ticket->id} foi fechado.");
    }

}