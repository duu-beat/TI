<?php

namespace App\Services;

use App\Models\AssetContract;
use App\Models\Supplier;
use Illuminate\Support\Carbon;

class InventoryFinancialReportService
{
    public function data(?int $supplierId = null): array
    {
        $today = Carbon::today();
        $cutoff = $today->copy()->addDays(90);
        $supplierQuery = Supplier::query()
            ->withCount(['assets', 'warranties', 'contracts'])
            ->withSum('contracts', 'amount')
            ->withCount([
                'contracts as active_contracts_count' => fn ($query) => $query->whereDate('starts_at', '<=', $today)->whereDate('expires_at', '>=', $today),
                'contracts as expiring_contracts_count' => fn ($query) => $query->whereDate('expires_at', '>', $today)->whereDate('expires_at', '<=', $cutoff),
                'contracts as expired_contracts_count' => fn ($query) => $query->whereDate('expires_at', '<', $today),
            ])
            ->orderByDesc('contracts_sum_amount')
            ->orderBy('name');
        if ($supplierId) $supplierQuery->whereKey($supplierId);
        $suppliers = $supplierQuery->get(['id', 'name', 'document', 'is_active']);

        $contractQuery = AssetContract::query();
        if ($supplierId) $contractQuery->where('supplier_id', $supplierId);
        $summary = [
            'suppliers' => $suppliers->count(),
            'assets' => $suppliers->sum('assets_count'),
            'warranties' => $suppliers->sum('warranties_count'),
            'contracts' => $suppliers->sum('contracts_count'),
            'amount' => (float) $suppliers->sum(fn ($supplier) => (float) ($supplier->contracts_sum_amount ?? 0)),
            'active_contracts' => (clone $contractQuery)->whereDate('starts_at', '<=', $today)->whereDate('expires_at', '>=', $today)->count(),
            'expiring_contracts' => (clone $contractQuery)->whereDate('expires_at', '>', $today)->whereDate('expires_at', '<=', $cutoff)->count(),
            'expired_contracts' => (clone $contractQuery)->whereDate('expires_at', '<', $today)->count(),
        ];

        return compact('suppliers', 'summary', 'today', 'cutoff');
    }
}
