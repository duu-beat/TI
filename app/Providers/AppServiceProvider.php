<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Observers\TicketObserver;
use App\Policies\TicketPolicy;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Carbon::setLocale('pt_BR');

        Gate::policy(Ticket::class, TicketPolicy::class);

        Ticket::observe(TicketObserver::class);

        View::composer('layouts.app', function ($view) {
            $bannerData = Cache::remember('ui:global_banner:v1', 60, function () {
                if (!Schema::hasTable('system_settings')) {
                    return [
                        'globalMsg' => null,
                        'bannerClasses' => 'bg-blue-500/10 border-blue-500/50 text-blue-400',
                        'icon' => 'i',
                    ];
                }

                $settings = DB::table('system_settings')
                    ->whereIn('key', ['global_message', 'global_message_style'])
                    ->pluck('value', 'key');

                $globalMsg = $settings['global_message'] ?? null;
                $globalStyle = $settings['global_message_style'] ?? 'info';

                $bannerClasses = match ($globalStyle) {
                    'warning' => 'bg-orange-500/10 border-orange-500/50 text-orange-400',
                    'danger' => 'bg-red-500/10 border-red-500/50 text-red-400',
                    'success' => 'bg-emerald-500/10 border-emerald-500/50 text-emerald-400',
                    default => 'bg-blue-500/10 border-blue-500/50 text-blue-400',
                };

                $icon = match ($globalStyle) {
                    'warning' => '⚠️',
                    'danger' => '🚨',
                    'success' => '✅',
                    default => 'ℹ️',
                };

                return compact('globalMsg', 'bannerClasses', 'icon');
            });

            $view->with($bannerData);
        });
    }
}
