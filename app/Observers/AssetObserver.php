<?php

namespace App\Observers;

use App\Models\Asset;
use App\Support\DashboardCache;

class AssetObserver
{
    public function saved(Asset $asset): void
    {
        DashboardCache::forgetAdminData();
    }

    public function deleted(Asset $asset): void
    {
        DashboardCache::forgetAdminData();
    }

    public function restored(Asset $asset): void
    {
        DashboardCache::forgetAdminData();
    }
}
