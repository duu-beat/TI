<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketAttachment; // Importado
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Notifications\TicketUpdated;
use Illuminate\Support\Facades\DB; // Importado para Transactions
use Illuminate\Support\Facades\Notification; // Importado para envio em massa

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

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        $ticket = DB::transaction(function () use ($request, $data) {
            
            // 1. Cria o Ticket
            $ticket = Ticket::create([
                'user_id' => $request->user()->id,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? null,
                'status' => TicketStatus::NEW,
            ]);

            // 2. Cria a Mensagem Inicial
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'message' => $data['description'],
            ]);

            // 3. ✨ UPLOAD MÚLTIPLO (Loop)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');

                    TicketAttachment::create([
                        'ticket_message_id' => $message->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            return $ticket;
        });

        // Notificar Admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TicketUpdated($ticket, 'created'));

        return redirect()->route('client.tickets.show', $ticket)
                         ->with('success', 'Chamado criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load(['messages.user', 'messages.attachments']); // Carregar anexos também, se necessário na view
        return view('client.tickets.show', compact('ticket'));
    }

    // ... imports existentes ...
    // Certifica-te de ter: use App\Models\TicketAttachment;
    // Certifica-te de ter: use Illuminate\Support\Facades\DB;

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        
        $data = $request->validate([
            'message' => ['required', 'string'],
            'attachments' => ['nullable', 'array'],           // Aceita array
            'attachments.*' => ['file', 'max:5120'],          // Cada arquivo máx 5MB
        ]);

        DB::transaction(function () use ($request, $ticket, $data) {
            // 1. Cria a mensagem
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'message' => $data['message'],
            ]);

            // 2. Upload Múltiplo
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    
                    TicketAttachment::create([
                        'ticket_message_id' => $message->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            // 3. Atualiza Status
            if (in_array($ticket->status, [TicketStatus::WAITING_CLIENT, TicketStatus::RESOLVED])) {
                $ticket->update(['status' => TicketStatus::IN_PROGRESS]);
            }
        });

        // Notificar Admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TicketUpdated($ticket, 'replied'));

        return back()->with('success', 'Mensagem enviada!');
    }
}