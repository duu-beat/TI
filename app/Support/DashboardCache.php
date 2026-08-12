<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class DashboardCache
{
    public const ADMIN_STATS = 'admin_dashboard_stats_v6';

    public static function forgetTicketDataForUser(int $userId): void
    {
        Cache::forget("dashboard_stats_{$userId}");
        Cache::forget("client_dashboard_stats_{$userId}");
        Cache::forget("home:client:{$userId}:stats:v1");
    }

    public static function forgetAdminData(): void
    {
        Cache::forget(self::ADMIN_STATS);
        Cache::forget('home:admin:stats:v1');
        Cache::forget('home:master:stats:v1');
    }
}
