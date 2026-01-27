<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketAttachment; // Não esqueça de importar
use App\Models\User;             // Importar User
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Notifications\TicketUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;           // Importar DB
use Illuminate\Support\Facades\Notification; // Importar Notification

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        // Carregar anexos nas mensagens
        $ticket->load(['user', 'messages.user', 'messages.attachments']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->update([
            'status' => $request->validated()['status']
        ]);

        // 🐛 CORREÇÃO DO BUG: Removemos o 'return' que existia aqui antes da notificação
        
        // Notificar o Cliente
        $ticket->user->notify(new TicketUpdated($ticket, 'status_updated'));

        return back()->with('success', 'Status atualizado!');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        // Validação igual à do cliente
        $data = $request->validate([
            'message' => ['required', 'string'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:5120'], 
        ]);

        DB::transaction(function () use ($request, $ticket, $data) {
            
            // 1. Criar Mensagem
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

            // Atualizar status se necessário
            if ($ticket->status === \App\Enums\TicketStatus::NEW) {
                $ticket->update(['status' => \App\Enums\TicketStatus::IN_PROGRESS]);
            } else {
                $ticket->update(['status' => \App\Enums\TicketStatus::WAITING_CLIENT]);
            }
        });

        // Notificar o Cliente
        $ticket->user->notify(new TicketUpdated($ticket, 'replied'));

        return back()->with('success', 'Resposta enviada!');
    }

    public function report()
    {
        // ⚠️ Otimização para não estourar memória: Limitar aos últimos 500 ou filtrar por data
        $tickets = Ticket::with('user')
            ->latest()
            ->take(500) 
            ->get();

        $pdf = Pdf::loadView('admin.reports.tickets', compact('tickets'));

        return $pdf->download('relatorio-chamados.pdf');
    }
}