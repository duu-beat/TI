<?php

namespace App\Services;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    protected SlaService $slaService;

    // Injeção de dependência automática do Laravel
    public function __construct(SlaService $slaService)
    {
        $this->slaService = $slaService;
    }

    public function getAdminDashboardData(): array
    {
        // 1. Dados Pesados (Cacheados)
        $cachedData = Cache::remember('admin_dashboard_stats_v5', 300, function () {
            return $this->calculateCachedStats();
        });

        // 2. Dados em Tempo Real (Não cacheados para ver atualizações instantâneas)
        // Se preferir tudo em cache, mova para dentro do método calculateCachedStats
        $realTimeData = [
            'latestTickets'   => Ticket::with('user')->latest()->take(5)->get(),
            'unassignedCount' => Ticket::whereNull('assigned_to')
                ->whereIn('status', TicketStatus::openStatuses())
                ->count(),
            'agentStats'      => $this->getAgentStats(),
        ];

        return array_merge($cachedData, $realTimeData);
    }

    protected function calculateCachedStats(): array
    {
        // Contagem rápida via SQL puro
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

        // Contagem de Alta Prioridade
        $highPriority = Ticket::where('priority', TicketPriority::HIGH)
            ->whereIn('status', TicketStatus::openStatuses())
            ->count();

        // Gráfico dos últimos 7 dias
        $dates = collect(range(6, 0))->map(fn($daysAgo) => now()->subDays($daysAgo)->format('d/m'));

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