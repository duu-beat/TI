<?php

namespace App\Observers;

use App\Models\NpsSurvey;
use App\Support\DashboardCache;

class NpsSurveyObserver
{
    public function saved(NpsSurvey $npsSurvey): void
    {
        DashboardCache::forgetAdminData();
    }

    public function deleted(NpsSurvey $npsSurvey): void
    {
        DashboardCache::forgetAdminData();
    }
}
