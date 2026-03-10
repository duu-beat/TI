<?php

namespace App\Observers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;

class TicketObserver
{
    /**
     * Manipula o evento "created" (executado apenas ao criar um novo chamado).
     */
    public function created(Ticket $ticket): void
    {
        // Lógica de Checklist Automático movida do TicketController
        $template = \App\Models\ChecklistTemplate::where('category', $ticket->category)
            ->where('is_active', true)
            ->with('items')
            ->first();

        if ($template) {
            $checklists = $template->items->map(function ($item) {
                return [
                    'task' => $item->content,
                    'order' => $item->order,
                    'is_completed' => false,
                ];
            });
            
            $ticket->checklists()->createMany($checklists->toArray());
        }
    }

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