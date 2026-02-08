<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Exibe a página de relatórios com filtros
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignee', 'tags']);

        // Aplicar filtros
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->latest()->paginate(50)->withQueryString();

        // Estatísticas do período filtrado
        $stats = $this->calculateStats($query);

        // Lista de admins para filtro
        $admins = User::whereIn('role', ['admin', 'master'])->get();

        return view('admin.reports.index', compact('tickets', 'stats', 'admins'));
    }

    /**
     * Exportar relatório em PDF
     */
    public function exportPdf(Request $request)
    {
        $query = $this->buildQuery($request);
        $tickets = $query->get();
        $stats = $this->calculateStats($query);

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'tickets' => $tickets,
            'stats' => $stats,
            'filters' => $request->all(),
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('relatorio-chamados-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar relatório em Excel (CSV)
     */
    public function exportExcel(Request $request)
    {
        $query = $this->buildQuery($request);
        $tickets = $query->get();

        $filename = 'relatorio-chamados-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabeçalho
            fputcsv($file, [
                'ID',
                'Cliente',
                'Assunto',
                'Categoria',
                'Status',
                'Prioridade',
                'Responsável',
                'Criado em',
                'Tempo de Resposta (min)',
                'Tempo de Resolução (min)',
                'Avaliação',
            ], ';');

            // Dados
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->user->name,
                    $ticket->subject,
                    $ticket->category ?? 'N/A',
                    $ticket->status->label(),
                    $ticket->priority->label(),
                    $ticket->assignee->name ?? 'Não atribuído',
                    $ticket->created_at->format('d/m/Y H:i'),
                    $ticket->response_time_minutes ?? 'N/A',
                    $ticket->resolution_time_minutes ?? 'N/A',
                    $ticket->rating ?? 'N/A',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Construir query com filtros
     */
    private function buildQuery(Request $request)
    {
        $query = Ticket::with(['user', 'assignee', 'tags']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return $query->latest();
    }

    /**
     * Calcular estatísticas
     */
    private function calculateStats($query)
    {
        // Clone a query para não afetar a original
        $statsQuery = clone $query;

        $total = $statsQuery->count();
        
        $byStatus = (clone $query)->get()->groupBy(fn($t) => $t->status->label())->map->count();
        $byPriority = (clone $query)->get()->groupBy(fn($t) => $t->priority->label())->map->count();
        
        $avgResponseTime = (clone $query)->whereNotNull('response_time_minutes')->avg('response_time_minutes');
        $avgResolutionTime = (clone $query)->whereNotNull('resolution_time_minutes')->avg('resolution_time_minutes');
        
        $avgRating = (clone $query)->whereNotNull('rating')->avg('rating');

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_priority' => $byPriority,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'avg_resolution_time' => round($avgResolutionTime ?? 0, 2),
            'avg_rating' => round($avgRating ?? 0, 2),
        ];
    }
}
