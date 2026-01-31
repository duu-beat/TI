<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\ReplyTicketRequest;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Traits\HandleAttachments; // Importa o Trait
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Notifications\TicketUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    use AuthorizesRequests, HandleAttachments; // Usa o Trait aqui

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $ticketsQuery = Ticket::where('user_id', $user->id);

        $stats = [
            'open' => (clone $ticketsQuery)->whereIn('status', [TicketStatus::NEW, TicketStatus::IN_PROGRESS, TicketStatus::WAITING_CLIENT])->count(),
            'in_progress' => (clone $ticketsQuery)->where('status', TicketStatus::IN_PROGRESS)->count(),
            'resolved' => (clone $ticketsQuery)->whereIn('status', [TicketStatus::RESOLVED, TicketStatus::CLOSED])->count(),
        ];

        $recentTickets = (clone $ticketsQuery)->latest()->take(5)->get();

        return view('client.dashboard', compact('stats', 'recentTickets'));
    }

    public function index(Request $request)
    {
        $query = Ticket::where('user_id', $request->user()->id);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function($q) use ($term) {
                $q->where('subject', 'like', "%{$term}%")
                  ->orWhere('id', $term);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('client.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('client.tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        $ticket = DB::transaction(function () use ($request, $data) {
            $ticket = Ticket::create([
                'user_id' => $request->user()->id,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? null,
                'status' => TicketStatus::NEW,
            ]);

            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'message' => $data['description'],
            ]);

            // ✅ Usa o Trait HandleAttachments
            $this->processAttachments($request, $message);

            return $ticket;
        });

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TicketUpdated($ticket, 'created'));

        return redirect()->route('client.tickets.show', $ticket)
                         ->with('success', 'Chamado criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        // Carrega as relações necessárias para a view
        $ticket->load(['messages.user', 'messages.attachments']);
        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(ReplyTicketRequest $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        
        $data = $request->validated();

        DB::transaction(function () use ($request, $ticket, $data) {
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'message' => $data['message'],
            ]);

            // ✅ Usa o Trait HandleAttachments
            $this->processAttachments($request, $message);

            if (in_array($ticket->status, [TicketStatus::WAITING_CLIENT, TicketStatus::RESOLVED])) {
                $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
            }
        });

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TicketUpdated($ticket, 'replied'));

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

        return back()->with('success', 'Obrigado pela sua avaliação!');
    }
}