<?php

namespace App\Services;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Support\DashboardCache;

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
        // 1. Dados Pesados (Cacheados por 15 minutos)
        // Inclui contagens globais, dados do gráfico e métricas de SLA.
        $cachedData = Cache::remember(DashboardCache::ADMIN_STATS, 900, function () {
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

        // 📧 Métricas de NPS
        $npsStats = \App\Models\NpsSurvey::selectRaw("
            avg(score) as avg_score,
            count(*) as total_responses,
            sum(case when score >= 9 then 1 else 0 end) as promoters,
            sum(case when score <= 6 then 1 else 0 end) as detractors
        ")->first();

        $npsScore = 0;
        if ($npsStats->total_responses > 0) {
            $npsScore = (($npsStats->promoters - $npsStats->detractors) / $npsStats->total_responses) * 100;
        }

        // 📦 Métricas de Inventário
        $assetStats = \App\Models\Asset::selectRaw("
            count(*) as total_assets,
            sum(case when status = 'active' then 1 else 0 end) as active_assets,
            sum(case when status = 'maintenance' then 1 else 0 end) as maintenance_assets
        ")->first();

        // Contagem de chamados críticos (Alta Prioridade e ainda abertos)
        $highPriority = Ticket::where('priority', TicketPriority::HIGH)
            ->whereIn('status', TicketStatus::openStatuses())
            ->count();

        // Preparação dos dados para o gráfico de volume dos últimos 7 dias
        $dates = collect(range(6, 0))->map(fn($daysAgo) => now()->subDays($daysAgo)->format('d/m'));

        // Agregação no banco para não carregar todos os tickets da semana em memória.
        $dateExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%d/%m', created_at)"
            : "DATE_FORMAT(created_at, '%d/%m')";

        $ticketsPerDay = Ticket::query()
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw("{$dateExpression} as day, count(*) as total")
            ->groupBy('day')
            ->pluck('total', 'day');

        $chartValues = $dates->map(fn($date) => $ticketsPerDay->get($date, 0));

        // 📊 Volume por Categoria (Top 5)
        $categoryStats = Ticket::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // ⏱️ Tempo Médio de Resolução (SLA Real)
        $avgResolutionTime = (Ticket::whereNotNull('resolution_time_minutes')
            ->avg('resolution_time_minutes') ?? 0) / 60;

        return [
            'stats'         => $stats,
            'priorityStats' => ['high' => $highPriority],
            'chartLabels'   => $dates->values(),
            'chartValues'   => $chartValues->values(),
            'categoryLabels' => $categoryStats->pluck('category'),
            'categoryValues' => $categoryStats->pluck('total'),
            'avgResolution' => round($avgResolutionTime, 1),
            'slaStats'      => $this->slaService->getSlaStats(),
            'npsStats'      => [
                'score' => round($npsScore, 1),
                'avg' => round($npsStats->avg_score, 1),
                'total' => $npsStats->total_responses
            ],
            'assetStats'    => $assetStats,
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
            ->has('assignedTickets')
            ->orderByDesc('resolved_count')
            ->take(5)
            ->get();
    }
}
