<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::query()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['user', 'messages.user']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,waiting_client,resolved,closed'],
        ]);

        $ticket->update(['status' => $data['status']]);

        return back()->with('success', 'Status atualizado!');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        // ao responder, normalmente vira "waiting_client"
        if ($ticket->status === 'new') {
            $ticket->update(['status' => 'in_progress']);
        } else {
            $ticket->update(['status' => 'waiting_client']);
        }

        return back()->with('success', 'Resposta enviada!');
    }
}

