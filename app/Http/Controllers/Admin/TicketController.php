<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Notifications\TicketUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    // ✅ MÉTODO INDEX ATUALIZADO COM FILTROS PODEROSOS
    public function index(Request $request)
    {
        $query = Ticket::with('user'); // Carrega usuário para evitar N+1

        // 1. Filtro de Busca (ID, Assunto, Nome do Cliente ou Email)
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function($q) use ($term) {
                $q->where('id', $term)
                  ->orWhere('subject', 'like', "%{$term}%")
                  ->orWhereHas('user', function($userQuery) use ($term) {
                      $userQuery->where('name', 'like', "%{$term}%")
                                ->orWhere('email', 'like', "%{$term}%");
                  });
            });
        }

        // 2. Filtro de Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load(['user', 'messages.user', 'messages.attachments']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->update([
            'status' => $request->validated()['status']
        ]);

        $ticket->user->notify(new TicketUpdated($ticket, 'status_updated'));

        return back()->with('success', 'Status atualizado!');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $data = $request->validate([
            'message' => ['required', 'string'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:5120'],
            'is_internal' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $ticket, $data) {
            
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $request->user()->id,
                'message' => $data['message'],
                'is_internal' => $request->boolean('is_internal'),
            ]);

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

            // Apenas atualiza status se NÃO for nota interna
            if (!$request->boolean('is_internal')) {
                if ($ticket->status === \App\Enums\TicketStatus::NEW) {
                    $ticket->update(['status' => \App\Enums\TicketStatus::IN_PROGRESS]);
                } else {
                    $ticket->update(['status' => \App\Enums\TicketStatus::WAITING_CLIENT]);
                }
                
                // Notificar o Cliente apenas se não for interno
                $ticket->user->notify(new TicketUpdated($ticket, 'replied'));
            }
        });

        return back()->with('success', 'Resposta enviada!');
    }

    public function report()
    {
        $tickets = Ticket::with('user')
            ->latest()
            ->take(500) 
            ->get();

        $pdf = Pdf::loadView('admin.reports.tickets', compact('tickets'));

        return $pdf->download('relatorio-chamados.pdf');
    }
}