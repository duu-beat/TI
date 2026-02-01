<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\ReplyTicketRequest;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Traits\HandleAttachments;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Notifications\TicketUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache; // ✅ Importado o Cache
use App\Actions\Ticket\CreateTicket;
use App\Actions\Ticket\ReplyToTicket;

class TicketController extends Controller
{
    use AuthorizesRequests, HandleAttachments;

    public function dashboard(Request $request)
    {
        $user = $request->user();

        // ✅ OTIMIZAÇÃO: Cache de 10 minutos (600s) para as estatísticas
        $stats = Cache::remember("dashboard_stats_{$user->id}", 600, function () use ($user) {
            return Ticket::where('user_id', $user->id)
                ->selectRaw("
                    count(*) as total,
                    sum(case when status in (?, ?, ?) then 1 else 0 end) as open,
                    sum(case when status = ? then 1 else 0 end) as in_progress,
                    sum(case when status in (?, ?) then 1 else 0 end) as resolved
                ", [
                    TicketStatus::NEW->value, TicketStatus::IN_PROGRESS->value, TicketStatus::WAITING_CLIENT->value,
                    TicketStatus::IN_PROGRESS->value,
                    TicketStatus::RESOLVED->value, TicketStatus::CLOSED->value
                ])->first();
        });

        $recentTickets = Ticket::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact('stats', 'recentTickets'));
    }

    public function index(Request $request)
    {
        $tickets = Ticket::where('user_id', $request->user()->id)
            ->filter($request->only(['search', 'status']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('client.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('client.tickets.create');
    }

    public function store(StoreTicketRequest $request, CreateTicket $creator)
    {
        $ticket = $creator->execute($request->user(), $request->validated(), $request);

        // ✅ LIMPEZA: Força a atualização do dashboard
        Cache::forget("dashboard_stats_{$request->user()->id}");

        return redirect()->route('client.tickets.show', $ticket)
                         ->with('success', 'Chamado criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load(['messages.user', 'messages.attachments']);
        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(ReplyTicketRequest $request, Ticket $ticket, ReplyToTicket $replier)
    {
        $replier->execute($request->user(), $ticket, $request->validated(), $request);

        return back()->with('success', 'Mensagem enviada!');
    }

    public function rate(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_comment' => ['nullable', 'string', 'max:500'],
        ]);

        $ticket->update([
            'rating' => $data['rating'],
            'rating_comment' => $data['rating_comment'],
            'status' => TicketStatus::CLOSED,
        ]);

        // Atualiza dashboard se necessário (pois alterou status para closed)
        Cache::forget("dashboard_stats_{$request->user()->id}");

        return back()->with('success', 'Obrigado pela sua avaliação!');
    }
}