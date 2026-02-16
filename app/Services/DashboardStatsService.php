<?php

namespace App\Services;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Serviço de Estatísticas do Dashboard
 * 
 * Centraliza a lógica de cálculo de métricas para os dashboards administrativos.
 * Combina dados cacheados (para performance) com dados em tempo real (para precisão).
 */
class DashboardStatsService
{
    /**
     * @var SlaService
     */
    protected SlaService $slaService;

    /**
     * Construtor com injeção de dependência.
     * 
     * @param SlaService $slaService
     */
    public function __construct(SlaService $slaService)
    {
        $this->slaService = $slaService;
    }

    /**
     * Consolida todos os dados necessários para o Dashboard Administrativo.
     * 
     * @return array
     */
    public function getAdminDashboardData(): array
    {
        // 1. Dados Pesados (Cacheados por 5 minutos)
        // Inclui contagens globais, dados do gráfico e métricas de SLA.
        $cachedData = Cache::remember('admin_dashboard_stats_v5', 300, function () {
            return $this->calculateCachedStats();
        });

        // 2. Dados em Tempo Real
        // Itens que precisam de atualização instantânea para o fluxo de trabalho.
        $realTimeData = [
            'latestTickets'   => Ticket::with('user')->latest()->take(5)->get(),
            'unassignedCount' => Ticket::whereNull('assigned_to')
                ->whereIn('status', TicketStatus::openStatuses())
                ->count(),
            'agentStats'      => $this->getAgentStats(),
        ];

        return array_merge($cachedData, $realTimeData);
    }

    /**
     * Realiza os cálculos estatísticos que serão armazenados em cache.
     * 
     * @return array
     */
    protected function calculateCachedStats(): array
    {
        // Contagem rápida de status utilizando SQL puro para máxima performance
        $stats = Ticket::selectRaw("
            count(*) as total,
            sum(case when status in (?, ?, ?) then 1 else 0 end) as open,
            sum(case when status = ? then 1 else 0 end) as on_hold,
            sum(case when status = ? then 1 else 0 end) as resolved
        ", [
            TicketStatus::NEW->value, TicketStatus::IN_PROGRESS->value, TicketStatus::WAITING_CLIENT->value,
            TicketStatus::WAITING_CLIENT->value,
            TicketStatus::RESOLVED->value
        ])->first();

        // Contagem de chamados críticos (Alta Prioridade e ainda abertos)
        $highPriority = Ticket::where('priority', TicketPriority::HIGH)
            ->whereIn('status', TicketStatus::openStatuses())
            ->count();

        // Preparação dos dados para o gráfico de volume dos últimos 7 dias
        $dates = collect(range(6, 0))->map(fn($daysAgo) => now()->subDays($daysAgo)->format('d/m'));

        // Agrupamento via PHP para garantir compatibilidade entre MySQL e SQLite
        $ticketsPerDay = Ticket::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(fn($ticket) => $ticket->created_at->format('d/m'))
            ->map->count();

        $chartValues = $dates->map(fn($date) => $ticketsPerDay->get($date, 0));

        return [
            'stats'         => $stats,
            'priorityStats' => ['high' => $highPriority],
            'chartLabels'   => $dates->values(),
            'chartValues'   => $chartValues->values(),
            'slaStats'      => $this->slaService->getSlaStats(),
        ];
    }

    /**
     * Obtém o ranking de performance dos agentes (técnicos).
     * 
     * Considera apenas usuários com papel 'admin' que possuem chamados atribuídos.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAgentStats()
    {
        return User::where('role', 'admin')
            ->withCount([
                'assignedTickets',
                'assignedTickets as resolved_count' => function ($query) {
                    $query->where('status', TicketStatus::RESOLVED);
                },
            ])
            ->withAvg('assignedTickets as avg_rating', 'rating')
            ->having('assigned_tickets_count', '>', 0)
            ->orderByDesc('resolved_count')
            ->take(5)
            ->get();
    }
}
