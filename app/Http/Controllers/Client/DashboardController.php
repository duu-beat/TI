<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Faq; // ✅ Importar o Modelo FAQ
use App\Enums\TicketStatus; // ✅ Importar o Enum

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Stats calculados com o Enum (Mais seguro que strings manuais)
        $stats = [
            'open' => $user->tickets()->where('status', TicketStatus::NEW)->count(),
            
            'in_progress' => $user->tickets()->whereIn('status', [
                TicketStatus::IN_PROGRESS, 
                TicketStatus::WAITING_CLIENT
            ])->count(),
            
            'resolved' => $user->tickets()->whereIn('status', [
                TicketStatus::RESOLVED, 
                TicketStatus::CLOSED
            ])->count(),
        ];

        // 2. Verificar pendências do utilizador (Usado no banner de alerta)
        $waitingForUser = $user->tickets()
            ->where('status', TicketStatus::WAITING_CLIENT)
            ->count();

        // 3. Tickets recentes
        $recentTickets = $user->tickets()->latest()->take(5)->get();

        // 4. FAQs (Aqui estava o erro! Precisamos enviar esta variável)
        // Se ainda não tiveres FAQs na base de dados, isto retorna uma coleção vazia e não dá erro.
        $faqs = Faq::take(3)->get(); 

        return view('client.dashboard', compact('stats', 'recentTickets', 'waitingForUser', 'faqs'));
    }
}