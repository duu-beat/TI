<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\ReplyTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use App\Actions\Ticket\CreateTicket;
use App\Actions\Ticket\ReplyToTicket;
use App\Actions\Ticket\RateTicket; // 👈 Nova Action

class TicketController extends Controller
{
    use AuthorizesRequests; // Nota: 'HandleAttachments' geralmente vai na Action, não no Controller

    public function dashboard(Request $request)
    {
        $user = $request->user();

        // ✅ OTIMIZADO: Usa o Scope do Model e o Observer limpa o cache automaticamente
        $stats = Cache::remember("dashboard_stats_{$user->id}", 600, function () use ($user) {
            return Ticket::where('user_id', $user->id)
                ->withDashboardStats() // 👈 Usa o novo scope
                ->first();
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
        // O Observer vai limpar o cache automaticamente ao criar
        $ticket = $creator->execute($request->user(), $request->validated(), $request);

        return redirect()->route('client.tickets.show', $ticket)
                         ->with('success', 'Chamado criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        
        // Carrega relações para evitar N+1
        $ticket->load(['messages.user', 'messages.attachments']);
        
        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(ReplyTicketRequest $request, Ticket $ticket, ReplyToTicket $replier)
    {
        $replier->execute($request->user(), $ticket, $request->validated(), $request);

        return back()->with('success', 'Mensagem enviada!');
    }

    public function rate(Request $request, Ticket $ticket, RateTicket $rater)
    {
        $this->authorize('view', $ticket); // Confirma permissão básica
        
        // Pequena validação local antes de chamar a Action
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_comment' => ['nullable', 'string', 'max:500'],
        ]);

        // Executa a Action (O Observer limpará o cache pois o status muda para Closed)
        $rater->execute($request->user(), $ticket, $data);

        return back()->with('success', 'Obrigado pela sua avaliação!');
    }
}