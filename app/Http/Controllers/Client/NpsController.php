<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\NpsSurvey;
use Illuminate\Http\Request;
use App\Enums\TicketStatus;

class NpsController extends Controller
{
    public function show(Ticket $ticket)
    {
        // Permite que o cliente avalie se o chamado estiver Fechado OU Resolvido
        if ($ticket->user_id != auth()->id() || !in_array($ticket->status, [TicketStatus::CLOSED, TicketStatus::RESOLVED])) {
            abort(403, 'Acesso negado ou o chamado ainda não foi finalizado.');
        }

        // Verifica se já respondeu usando exists() para não carregar a relation toda à toa
        if ($ticket->npsSurvey()->exists()) {
            return redirect()->route('client.tickets.show', $ticket)
                ->with('info', 'Você já respondeu a esta pesquisa de satisfação. Obrigado!');
        }

        return view('client.tickets.nps', compact('ticket'));
    }

    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($ticket->user_id != auth()->id() || !in_array($ticket->status, [TicketStatus::CLOSED, TicketStatus::RESOLVED])) {
            abort(403);
        }

        if (!$ticket->npsSurvey()->exists()) {
            NpsSurvey::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'score' => $validated['score'],
                'comment' => $validated['comment'],
                'responded_at' => now(),
            ]);

            // Se o chamado estava apenas resolvido, a avaliação o encerra definitivamente
            if ($ticket->status === TicketStatus::RESOLVED) {
                $ticket->update(['status' => TicketStatus::CLOSED]);
            }
        }

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Obrigado pelo seu feedback! Sua avaliação nos ajuda a melhorar.');
    }
}