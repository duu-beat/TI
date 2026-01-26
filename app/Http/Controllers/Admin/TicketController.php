<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Notifications\TicketUpdated;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // ✅ Método refatorado
    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        // O 'status' aqui já é validado e convertido para o Enum
        $ticket->update([
            'status' => $request->validated()['status']
        ]);

        return back()->with('success', 'Status atualizado!');

        // 🔔 NOVO: Notificar o Cliente sobre a mudança
        $ticket->user->notify(new TicketUpdated($ticket, 'status_updated'));

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

        $ticket->user->notify(new TicketUpdated($ticket, 'replied'));

        return back()->with('success', 'Resposta enviada!');
    }

    public function report()
    {
        // Pega todos os tickets (pode filtrar só os resolvidos se quiser)
        $tickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.reports.tickets', compact('tickets'));

        // Faz o download do arquivo 'relatorio-chamados.pdf'
        return $pdf->download('relatorio-chamados.pdf');
    }
}

