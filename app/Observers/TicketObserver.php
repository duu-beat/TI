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
    }
}