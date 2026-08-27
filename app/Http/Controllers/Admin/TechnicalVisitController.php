<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicalVisit;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\TechnicalVisitRequest;
use App\Http\Requests\Admin\UpdateTechnicalVisitStatusRequest;

/**
 * Controller de Visitas Técnicas (Admin)
 * 
 * Gerencia o agendamento e o ciclo de vida das visitas presenciais.
 */
class TechnicalVisitController extends Controller
{
    /**
     * Lista todas as visitas agendadas.
     */
    public function index()
    {
        $visits = TechnicalVisit::with(['ticket', 'technician'])
            ->orderBy('scheduled_at', 'asc')
            ->paginate(15);

        return view('admin.visits.index', compact('visits'));
    }

    /**
     * Exibe o formulário de agendamento para um chamado específico.
     */
    public function create(Ticket $ticket)
    {
        return view('admin.visits.create', compact('ticket'));
    }

    /**
     * Salva um novo agendamento.
     */
    public function store(TechnicalVisitRequest $request)
    {
        $validated = $request->validated();

        $visit = TechnicalVisit::create([
            'ticket_id' => $validated['ticket_id'],
            'user_id' => Auth::id(),
            'scheduled_at' => $validated['scheduled_at'],
            'address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
        ]);

        // Adiciona nota interna no chamado informando o agendamento
        $visit->ticket->messages()->create([
            'user_id' => Auth::id(),
            'is_internal' => true,
            'message' => "📅 **VISITA TÉCNICA AGENDADA** para o dia " . $visit->scheduled_at->format('d/m/Y \à\s H:i') . " no endereço: " . $visit->address
        ]);

        return redirect()->route('admin.tickets.show', $visit->ticket_id)
            ->with('success', 'Visita técnica agendada com sucesso!');
    }

    /**
     * Atualiza o status da visita (Check-in, Check-out, etc).
     */
    public function updateStatus(UpdateTechnicalVisitStatusRequest $request, TechnicalVisit $visit)
    {

        $oldStatus = $visit->getStatusLabel();
        $status = $request->validated()['status'];
        $visit->update(['status' => $status]);
        $newStatus = $visit->getStatusLabel();

        if ($status === 'in_service' && !$visit->started_at) {
            $visit->update(['started_at' => now()]);
        }

        if ($status === 'completed' && !$visit->completed_at) {
            $visit->update(['completed_at' => now()]);
        }

        // Log no chamado
        $visit->ticket->messages()->create([
            'user_id' => Auth::id(),
            'is_internal' => true,
            'message' => "🔄 Status da visita técnica alterado de **{$oldStatus}** para **{$newStatus}**."
        ]);

        return back()->with('success', 'Status da visita atualizado.');
    }
}
