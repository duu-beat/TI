<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\InventoryDashboardService;
use Illuminate\View\View;

class InventoryDashboardController extends Controller
{
    public function index(InventoryDashboardService $dashboard): View
    {
        $this->authorize('viewAny', Asset::class);

        return view('admin.assets.dashboard', $dashboard->data());
    }
}
