<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use App\Notifications\TicketUpdated; // Importar Notificação
use App\Traits\HandleAttachments;    // Importar Trait
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;      // Importar Rule para validação

class TicketController extends Controller
{
    use HandleAttachments; // Usar o Trait

    public function index(Request $request)
    {
        $query = Ticket::with('user')->latest();

        if ($request->filled('search')) { // 'filled' é mais limpo que has && != ''
            $query->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('id', $request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    // MELHORIA: Validação de Enum e Notificação
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => ['required', Rule::enum(TicketStatus::class)],
        ]);

        $oldStatus = $ticket->status;
        
        $ticket->update([
            'status' => $request->status,
        ]);

        // Notificar o cliente se o status mudou
        if ($oldStatus !== $ticket->status) {
            $ticket->user->notify(new TicketUpdated($ticket, 'status_updated'));
        }

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    // MELHORIA: Uso do Trait HandleAttachments
    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240', // 10MB
        ]);

        $message = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => $request->has('is_internal'),
        ]);

        // Usa o método do Trait (substitui o código duplicado)
        $this->processAttachments($request, $message);

        // Se respondeu ao cliente (não interno), muda status
        if (!$request->has('is_internal') && $ticket->status === TicketStatus::NEW) {
            $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
            
            // Notificar cliente da resposta
            $ticket->user->notify(new TicketUpdated($ticket, 'replied'));
        }

        return back()->with('success', 'Resposta enviada!');
    }

    // MELHORIA: Dashboard Otimizado (1 Query em vez de 4)
    public function dashboard()
    {
        // 1. Stats de Status (Query Agregada)
        $rawStats = Ticket::selectRaw("
            count(*) as total,
            sum(case when status = ? then 1 else 0 end) as new,
            sum(case when status in (?, ?) then 1 else 0 end) as in_progress,
            sum(case when status in (?, ?) then 1 else 0 end) as resolved
        ", [
            TicketStatus::NEW->value,
            TicketStatus::IN_PROGRESS->value, TicketStatus::WAITING_CLIENT->value,
            TicketStatus::RESOLVED->value, TicketStatus::CLOSED->value
        ])->first();

        $stats = [
            'new' => $rawStats->new,
            'in_progress' => $rawStats->in_progress,
            'resolved' => $rawStats->resolved,
            'total' => $rawStats->total,
        ];

        // 2. Stats de Prioridade
        $priorityStats = [
            'high' => Ticket::where('priority', TicketPriority::HIGH)
                ->whereIn('status', [TicketStatus::NEW, TicketStatus::IN_PROGRESS])
                ->count(),
            'medium' => Ticket::where('priority', TicketPriority::MEDIUM)
                ->whereIn('status', [TicketStatus::NEW, TicketStatus::IN_PROGRESS])
                ->count(),
        ];

        // 3. Últimos Chamados
        $latestTickets = Ticket::with('user')->latest()->take(5)->get();

        // 4. Gráficos (Semanal)
        $dailyData = Ticket::selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartValues = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartValues[] = $dailyData[$date] ?? 0;
        }

        return view('admin.dashboard', compact('stats', 'priorityStats', 'latestTickets', 'chartLabels', 'chartValues'));
    }

    public function report()
    {
        $tickets = Ticket::with('user')->latest()->get();
        return view('admin.reports.tickets', compact('tickets'));
    }
}