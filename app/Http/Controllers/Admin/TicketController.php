<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Http\Requests\ReplyTicketRequest;
use App\Notifications\TicketUpdated;
use App\Actions\Ticket\ReplyToTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Enums\TicketPriority;
use App\Models\CannedResponse;
use App\Models\User;
use App\Services\SlaService;

class TicketController extends Controller
{
    public function dashboard()
    {
        $slaService = app(SlaService::class);

        // Dashboard com Cache de 5 min
        $dashboardData = Cache::remember('admin_dashboard_stats_v5', 300, function () use ($slaService) {
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

            $highPriority = Ticket::where('priority', TicketPriority::HIGH)
                ->whereIn('status', TicketStatus::openStatuses())
                ->count();

            // Gráfico últimos 7 dias
            $dates = collect(range(6, 0))->map(function ($daysAgo) {
                return now()->subDays($daysAgo)->format('d/m');
            });

            $ticketsPerDay = Ticket::where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->get()
                ->groupBy(fn($ticket) => $ticket->created_at->format('d/m'))
                ->map->count();

            $chartValues = $dates->map(fn($date) => $ticketsPerDay->get($date, 0));

            $slaStats = $slaService->getSlaStats();

            return [
                'stats' => $stats,
                'priorityStats' => ['high' => $highPriority],
                'chartLabels' => $dates->values(),
                'chartValues' => $chartValues->values(),
                'slaStats' => $slaStats,
            ];
        });

        $latestTickets = Ticket::with('user')->latest()->take(5)->get();

        $agentStats = User::where('role', 'admin')
            ->withCount([
                'assignedTickets',
                'assignedTickets as resolved_count' => function ($query) {
                    $query->where('status', TicketStatus::RESOLVED);
                },
            ])
            ->withAvg('assignedTickets as avg_rating', 'rating')
            ->having('assigned_tickets_count', '>', 0)
            ->orderByDesc('resolved_count')
            ->take(5)
            ->get();

        $unassignedCount = Ticket::whereNull('assigned_to')
            ->whereIn('status', TicketStatus::openStatuses())
            ->count();

        return view('admin.dashboard', [
            'stats' => $dashboardData['stats'],
            'priorityStats' => $dashboardData['priorityStats'],
            'chartLabels' => $dashboardData['chartLabels'],
            'chartValues' => $dashboardData['chartValues'],
            'slaStats' => $dashboardData['slaStats'],
            'latestTickets' => $latestTickets,
            'agentStats' => $agentStats,
            'unassignedCount' => $unassignedCount,
        ]);
    }

    public function index(Request $request)
    {
        $tickets = Ticket::with(['user', 'assignee', 'tags'])
            ->filter($request->only([
                'search', 'status', 'priority', 'category', 
                'assigned_to', 'tag', 'date_from', 'date_to', 'sla_overdue'
            ]))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tags = \App\Models\Tag::all();
        $admins = User::whereIn('role', ['admin', 'master'])->get();
        $statuses = TicketStatus::cases();
        $priorities = TicketPriority::cases();

        return view('admin.tickets.index', compact('tickets', 'tags', 'admins', 'statuses', 'priorities'));
    }

    // ✅ MÉTODO NOVO: KANBAN
    public function kanban()
    {
        // 1. Busca tickets abertos com relacionamentos
        $tickets = Ticket::with(['user', 'assignee', 'tags']) 
            ->where('status', '!=', TicketStatus::CLOSED)
            ->latest()
            ->get();

        // 2. Agrupa por status
        $groupedTickets = $tickets->groupBy(fn($ticket) => $ticket->status->value);

        // 3. Status possíveis
        $statuses = TicketStatus::cases();

        return view('admin.tickets.kanban', compact('groupedTickets', 'statuses'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'messages.user', 'messages.attachments', 'assignee']);

        $clientHistory = [
            'total_tickets' => Ticket::where('user_id', $ticket->user_id)->count(),
            'last_ticket' => Ticket::where('user_id', $ticket->user_id)
                ->where('id', '!=', $ticket->id)
                ->latest()
                ->first(),
        ];

        $cannedResponses = CannedResponse::all();
        $admins = User::whereIn('role', ['admin', 'master'])->get();

        return view('admin.tickets.show', compact('ticket', 'clientHistory', 'cannedResponses', 'admins'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => ['required', Rule::enum(TicketStatus::class)]]);

        $oldStatus = $ticket->status->label();
        $ticket->update(['status' => $request->status]);
        $newStatus = $ticket->status->label();

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'is_internal' => true,
            'message' => "⚡ alterou o status de **{$oldStatus}** para **{$newStatus}**."
        ]);

        return back()->with('success', 'Status atualizado.');
    }

    public function reply(Request $request, Ticket $ticket, ReplyToTicket $replier)
    {
        $validated = $request->validate([
            'message' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240'],
            'is_internal' => ['boolean'],
            'time_spent' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->boolean('is_internal')) {
            $ticket->messages()->create([
                'user_id' => auth()->id(),
                'message' => $validated['message'],
                'is_internal' => true,
                'time_spent' => $request->input('time_spent', 0),
            ]);
            
            return back()->with('success', 'Nota interna adicionada.');
        }

        $replier->execute($request->user(), $ticket, $validated, $request);

        if ($ticket->status !== TicketStatus::RESOLVED && $ticket->status !== TicketStatus::CLOSED) {
            $ticket->update(['status' => TicketStatus::WAITING_CLIENT]);
        }

        return back()->with('success', 'Resposta enviada!');
    }

    public function report()
    {
        $tickets = Ticket::with('user')->latest()->limit(500)->get();
        return view('admin.reports.tickets', compact('tickets'));
    }

    public function escalate(Ticket $ticket)
    {
        $ticket->update(['is_escalated' => true]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'is_internal' => true,
            'message' => "🚨 **ESCALONOU** este chamado para a equipe de Segurança."
        ]);

        return back()->with('success', 'Escalonado com sucesso.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        $user = User::find($request->assigned_to);
        $ticket->update(['assigned_to' => $request->assigned_to]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'is_internal' => true,
            'message' => "👤 atribuiu este chamado para **{$user->name}**."
        ]);

        return back()->with('success', 'Chamado atribuído.');
    }

    public function merge(Request $request, Ticket $ticket)
    {
        $request->validate(['target_ticket_id' => 'required|exists:tickets,id']);

        $targetTicket = Ticket::find($request->target_ticket_id);

        if ($targetTicket->id === $ticket->id) {
            return back()->with('error', 'Não pode fundir o chamado com ele mesmo.');
        }

        $ticket->messages()->update(['ticket_id' => $targetTicket->id]);
        
        $targetTicket->messages()->create([
            'user_id' => auth()->id(),
            'message' => "Sistema: As mensagens do chamado #{$ticket->id} foram movidas para cá.",
            'is_internal' => true,
        ]);

        $ticket->update(['status' => TicketStatus::CLOSED]);

        return redirect()->route('admin.tickets.show', $targetTicket)
            ->with('success', "Chamados fundidos.");
    }

    // Busca Global para o Spotlight
    public function globalSearch(Request $request)
    {
        $query = $request->get('query');
        
        if (strlen($query) < 2) return response()->json([]);

        // Busca Tickets
        $tickets = Ticket::where('id', 'like', "%{$query}%")
            ->orWhere('subject', 'like', "%{$query}%")
            ->with('user')
            ->take(5)
            ->get()
            ->map(function($ticket) {
                return [
                    'type' => 'ticket',
                    'id' => $ticket->id,
                    'title' => "#{$ticket->id} - {$ticket->subject}",
                    'subtitle' => $ticket->user->name . ' • ' . $ticket->status->label(),
                    'url' => route('admin.tickets.show', $ticket),
                    'icon' => 'ticket'
                ];
            });

        // Busca Usuários
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user',
                    'id' => $user->id,
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'url' => route('master.users.edit', $user), // Ajuste se a rota for diferente
                    'icon' => 'user'
                ];
            });

        return response()->json($tickets->merge($users));
    }
}