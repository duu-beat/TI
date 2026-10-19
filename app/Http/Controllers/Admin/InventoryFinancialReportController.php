<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Services\InventoryFinancialReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryFinancialReportController extends Controller
{
    public function index(Request $request, InventoryFinancialReportService $report): View
    {
        $this->authorize('viewReports');
        $supplierId = $request->integer('supplier_id') ?: null;
        $supplierOptions = Supplier::query()->orderBy('name')->get(['id', 'name']);
        $data = $report->data($supplierId);
        return view('admin.reports.inventory-financial', array_merge($data, compact('supplierOptions', 'supplierId')));
    }
}
