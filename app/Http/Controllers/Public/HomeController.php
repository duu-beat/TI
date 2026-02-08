<?php

namespace App\Http\Controllers\Public;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return view('public.home');
        }

        if ($user->isMaster()) {
            $data = Cache::remember('home:master:stats:v1', 120, function () {
                return [
                    'escalatedCount' => Ticket::query()
                        ->where('is_escalated', true)
                        ->where('status', '!=', TicketStatus::RESOLVED->value)
                        ->count(),
                    'adminsCount' => User::query()
                        ->where('role', User::ROLE_ADMIN)
                        ->count(),
                ];
            });

            return view('public.home', $data);
        }

        if ($user->isAdmin()) {
            $data = Cache::remember('home:admin:stats:v1', 120, function () {
                return [
                    'urgentCount' => Ticket::query()
                        ->where('priority', TicketPriority::HIGH->value)
                        ->whereIn('status', [TicketStatus::NEW->value, TicketStatus::IN_PROGRESS->value])
                        ->count(),
                    'openTickets' => Ticket::query()
                        ->whereIn('status', [TicketStatus::NEW->value, TicketStatus::IN_PROGRESS->value])
                        ->count(),
                ];
            });

            return view('public.home', $data);
        }

        $data = Cache::remember("home:client:{$user->id}:stats:v1", 120, function () use ($user) {
            return [
                'recentTickets' => $user->tickets()
                    ->latest()
                    ->take(3)
                    ->get(),
                'ticketsMonth' => $user->tickets()
                    ->whereMonth('created_at', now()->month)
                    ->count(),
            ];
        });

        return view('public.home', $data);
    }
}
