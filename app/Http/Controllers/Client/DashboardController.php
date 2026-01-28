<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    
    // Consulta base para reutilizar
    $baseQuery = \App\Models\Ticket::where('user_id', $user->id);

    // Stats
    $stats = [
        'open' => (clone $baseQuery)->whereIn('status', ['new', 'in_progress', 'waiting_client'])->count(),
        'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
        'resolved' => (clone $baseQuery)->whereIn('status', ['resolved', 'closed'])->count(),
    ];

    // Tickets recentes
    $recentTickets = (clone $baseQuery)->latest()->take(5)->get();

    return view('client.dashboard', compact('stats', 'recentTickets'));
}
}
