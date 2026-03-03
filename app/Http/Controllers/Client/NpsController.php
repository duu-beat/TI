<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\NpsSurvey;
use Illuminate\Http\Request;
use App\Enums\TicketStatus;

/**
 * Gerencia as pesquisas de satisfação (NPS) dos clientes
 */
class NpsController extends Controller
{
    /**
     * Exibe o formulário de pesquisa de satisfação para um chamado fechado
     */
    public function show(Ticket $ticket)
    {
        // Verifica se o chamado pertence ao usuário e está fechado
        if ($ticket->user_id !== auth()->id() || $ticket->status !== TicketStatus::CLOSED) {
            abort(403, 'Acesso negado ou chamado não está fechado.');
        }

        // Verifica se já respondeu
        if ($ticket->npsSurvey) {
            return redirect()->route('client.tickets.show', $ticket)
                ->with('info', 'Você já respondeu a esta pesquisa de satisfação. Obrigado!');
        }

        return view('client.tickets.nps', compact('ticket'));
    }

    /**
     * Salva a resposta da pesquisa de satisfação
     */
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Verifica se o chamado pertence ao usuário e está fechado
        if ($ticket->user_id !== auth()->id() || $ticket->status !== TicketStatus::CLOSED) {
            abort(403);
        }

        // Verifica se já respondeu
        if ($ticket->npsSurvey) {
            return redirect()->route('client.tickets.show', $ticket);
        }

        NpsSurvey::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'score' => $validated['score'],
            'comment' => $validated['comment'],
            'responded_at' => now(),
        ]);

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Obrigado pelo seu feedback! Sua avaliação nos ajuda a melhorar.');
    }
}
