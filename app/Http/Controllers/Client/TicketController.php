<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest; // Importar o Request
use App\Enums\TicketStatus;               // Importar o Enum
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Notifications\TicketUpdated;

class TicketController extends Controller
{
    use AuthorizesRequests;

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

    // ✅ Lógica limpa: Injetamos o StoreTicketRequest
    public function store(StoreTicketRequest $request)
{
    $data = $request->validated();

    // 1. Cria o Ticket
    $ticket = Ticket::create([
        'user_id' => $request->user()->id,
        'subject' => $data['subject'],
        'description' => $data['description'],
        'priority' => $data['priority'] ?? null,
        'status' => \App\Enums\TicketStatus::NEW,
    ]);

    // 2. Cria a Mensagem Inicial
    $message = \App\Models\TicketMessage::create([
        'ticket_id' => $ticket->id,
        'user_id' => $request->user()->id,
        'message' => $data['description'],
    ]);

    // 3. Upload do Arquivo (Se existir)
    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public'); // Salva na pasta 'public/attachments'

        \App\Models\TicketAttachment::create([
            'ticket_message_id' => $message->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        // 🔔 NOVO: Notificar todos os Admins
        User::where('role', 'admin')->get()->each(function($admin) use ($ticket) {
            $admin->notify(new TicketUpdated($ticket, 'created'));
        });

        return redirect()->route('client.tickets.show', $ticket)
                         ->with('success', 'Chamado criado!');
    }

    return redirect()->route('client.tickets.show', $ticket)
                     ->with('success', 'Chamado criado com anexo!');
}

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load(['messages.user']);
        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        
        // Validação inline simples para resposta
        $data = $request->validate(['message' => ['required', 'string']]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'message' => $data['message'],
        ]);

        // Se o ticket estava em espera ou resolvido, volta para "Em Progresso"
        if (in_array($ticket->status, [TicketStatus::WAITING_CLIENT, TicketStatus::RESOLVED])) {
            $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
        }

    // 🔔 NOVO: Notificar Admins sobre a resposta
    User::where('role', 'admin')->get()->each(function($admin) use ($ticket) {
        $admin->notify(new TicketUpdated($ticket, 'replied'));
    });

    return back()->with('success', 'Mensagem enviada!');
    }
}