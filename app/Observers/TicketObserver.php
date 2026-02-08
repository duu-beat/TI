<?php

namespace App\Observers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;

class TicketObserver
{
    /**
     * Manipula o evento "saved" (executado ao criar ou atualizar).
     */
    public function saved(Ticket $ticket): void
    {
        $this->clearDashboardCache($ticket->user_id);
    }

    /**
     * Manipula o evento "deleted".
     */
    public function deleted(Ticket $ticket): void
    {
        $this->clearDashboardCache($ticket->user_id);
    }

    /**
     * Método auxiliar para limpar o cache.
     */
    protected function clearDashboardCache($userId): void
    {
        Cache::forget("dashboard_stats_{$userId}");
        Cache::forget("home:client:{$userId}:stats:v1");
        Cache::forget('home:admin:stats:v1');
        Cache::forget('home:master:stats:v1');
        Cache::forget('admin_dashboard_stats_v5');
    }
}
