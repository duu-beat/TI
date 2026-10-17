<?php

namespace App\Services;

use App\Enums\TicketStatus;
use App\Models\Asset;
use App\Models\Branch;
use App\Models\Sector;
use Illuminate\Support\Facades\Cache;

class InventoryDashboardService
{
    public const CACHE_KEY = 'dashboard:inventory:v1';

    public function data(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(5), function (): array {
            $stats = Asset::query()->selectRaw("\n                count(*) as total,\n                sum(case when status = 'active' then 1 else 0 end) as active,\n                sum(case when status = 'maintenance' then 1 else 0 end) as maintenance,\n                sum(case when status = 'retired' then 1 else 0 end) as retired,\n                sum(case when status = 'lost' then 1 else 0 end) as lost,\n                sum(case when user_id is null then 1 else 0 end) as unassigned,\n                sum(case when branch_id is null or sector_id is null then 1 else 0 end) as without_location\n            ")->first();

            $byType = Asset::query()
                ->selectRaw('type, count(*) as total')
                ->groupBy('type')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            $branches = Branch::query()
                ->withCount(['assets', 'assets as maintenance_assets_count' => fn ($query) => $query->where('status', 'maintenance')])
                ->where('is_active', true)
                ->whereHas('assets')
                ->orderByDesc('assets_count')
                ->limit(8)
                ->get(['id', 'name']);

            $sectors = Sector::query()
                ->with('branch:id,name')
                ->withCount('assets')
                ->where('is_active', true)
                ->whereHas('assets')
                ->orderByDesc('assets_count')
                ->limit(10)
                ->get(['id', 'branch_id', 'name']);

            $problematicAssets = Asset::query()
                ->with(['branch:id,name', 'sector:id,name'])
                ->withCount([
                    'tickets',
                    'tickets as open_tickets_count' => fn ($query) => $query->whereIn('status', TicketStatus::openStatuses()),
                ])
                ->whereHas('tickets')
                ->orderByDesc('open_tickets_count')
                ->orderByDesc('tickets_count')
                ->limit(10)
                ->get(['id', 'name', 'tag', 'type', 'status', 'branch_id', 'sector_id']);

            return compact('stats', 'byType', 'branches', 'sectors', 'problematicAssets');
        });
    }
}
