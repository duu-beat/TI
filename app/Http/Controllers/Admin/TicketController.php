<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority; // ⚠️ IMPORTANTE: Não te esqueças disto!
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('user')->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('id', $request->search);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => ['required', 'string'],
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        // Notificar cliente aqui se necessário

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240', // 10MB
        ]);

        $message = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => $request->has('is_internal'), // Checkbox do form
        ]);

        // Upload de Anexos
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $message->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Se respondeu ao cliente, muda status para "Em Andamento" ou "Aguardando Cliente"
        if (!$request->has('is_internal') && $ticket->status === TicketStatus::NEW) {
            $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
        }

        return back()->with('success', 'Resposta enviada!');
    }

    // 📊 NOVO MÉTODO DASHBOARD (CORRIGIDO)
    public function dashboard()
    {
        // 1. Stats de Status
        $stats = [
            'new' => Ticket::where('status', TicketStatus::NEW)->count(),
            'in_progress' => Ticket::whereIn('status', [TicketStatus::IN_PROGRESS, TicketStatus::WAITING_CLIENT])->count(),
            'resolved' => Ticket::whereIn('status', [TicketStatus::RESOLVED, TicketStatus::CLOSED])->count(),
            'total' => Ticket::count(),
        ];

        // 2. Stats de Prioridade (CORREÇÃO DO ERRO)
        // Certifica-te que tens a coluna 'priority' na DB e o Enum importado
        $priorityStats = [
            'high' => Ticket::where('priority', TicketPriority::HIGH)
                ->whereIn('status', [TicketStatus::NEW, TicketStatus::IN_PROGRESS])
                ->count(),
            'medium' => Ticket::where('priority', TicketPriority::MEDIUM)
                ->whereIn('status', [TicketStatus::NEW, TicketStatus::IN_PROGRESS])
                ->count(),
        ];

        // 3. Últimos Chamados para a lista rápida
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
        // Lógica simples de relatório
        $tickets = Ticket::with('user')->latest()->get();
        return view('admin.reports.tickets', compact('tickets'));
    }
}