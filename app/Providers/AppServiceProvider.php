<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // 👈 Importante
use Illuminate\Support\Carbon;
use App\Models\Ticket;               // 👈 Importante
use App\Policies\TicketPolicy;       // 👈 Importante

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forçar datas em Português
        Carbon::setLocale('pt_BR');

        // 👇 REGISTRO OBRIGATÓRIO DA POLICY
        Gate::policy(Ticket::class, TicketPolicy::class);
    }
}