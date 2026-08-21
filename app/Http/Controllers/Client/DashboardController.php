<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Ticket;
use App\Models\KnowledgeBase;
use App\Enums\TicketStatus; // ✅ Importar o Enum

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // ✅ SÊNIOR: Cache de 10 minutos para as estatísticas do dashboard
        // Isso reduz drasticamente a carga no banco de dados em acessos frequentes
        $dashboardData = Cache::remember("client_dashboard_stats_{$user->id}", 600, function () use ($user) {
            return [
                'stats' => [
                    'open' => $user->tickets()->where('status', TicketStatus::NEW)->count(),
                    'in_progress' => $user->tickets()->whereIn('status', [
                        TicketStatus::IN_PROGRESS, 
                        TicketStatus::WAITING_CLIENT
                    ])->count(),
                    'resolved' => $user->tickets()->whereIn('status', [
                        TicketStatus::RESOLVED, 
                        TicketStatus::CLOSED
                    ])->count(),
                ],
                'waitingForUser' => $user->tickets()
                    ->where('status', TicketStatus::WAITING_CLIENT)
                    ->count(),
            ];
        });

        $stats = $dashboardData['stats'];
        $waitingForUser = $dashboardData['waitingForUser'];

        // Tickets recentes não são cacheados para garantir que o usuário veja o status atualizado
        $recentTickets = $user->tickets()->latest()->take(5)->get();

        // Os artigos publicados oferecem ajuda contextual sem expor rascunhos da Wiki.
        $quickArticles = Cache::remember('client_dashboard_quick_articles', 3600, function () {
            return KnowledgeBase::query()
                ->where('is_published', true)
                ->orderByDesc('views_count')
                ->latest('updated_at')
                ->limit(3)
                ->get(['id', 'title', 'slug', 'category', 'views_count']);
        });

        return view('client.dashboard', compact('stats', 'recentTickets', 'waitingForUser', 'quickArticles'));
    }
}