<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use AuthorizesRequests; // ✅ ESSENCIAL

    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('client.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('client.tickets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['nullable', 'in:low,medium,high'],
        ]);

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'] ?? null,
            'status' => 'new',
        ]);

        // primeira mensagem (opcional, mas fica legal no histórico)
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'message' => $data['description'],
        ]);

        return redirect()->route('client.tickets.show', $ticket)->with('success', 'Chamado criado!');
    }

    public function show(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['messages.user']);

        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        // quando cliente responde, pode voltar pra "waiting_admin" (opcional)
        if (in_array($ticket->status, ['waiting_client', 'resolved'])) {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Mensagem enviada!');
    }
}

