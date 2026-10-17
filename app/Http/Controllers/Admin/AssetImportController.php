<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportAssetRequest;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Services\AssetCsvImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssetImportController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', Asset::class);
        return view('admin.assets.import');
    }

    public function store(ImportAssetRequest $request, AssetCsvImportService $importer): RedirectResponse
    {
        $this->authorize('create', Asset::class);
        $result = $importer->validate($request->file('file'));

        if ($result['errors'] !== [] || $result['rows'] === []) {
            return back()->withInput()->with('import_errors', $result['errors'] ?: ['O arquivo não contém registros válidos.']);
        }

        DB::transaction(function () use ($result): void {
            foreach ($result['rows'] as $row) {
                $asset = Asset::create($row);
                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'user_id' => auth()->id(),
                    'action' => 'import',
                    'description' => 'Equipamento importado por CSV.',
                    'new_status' => $asset->status,
                    'new_user_id' => $asset->user_id,
                    'new_branch_id' => $asset->branch_id,
                    'new_sector_id' => $asset->sector_id,
                ]);
            }
        });

        return redirect()->route('admin.assets.index')->with('success', __('assets.import.completed', ['count' => count($result['rows'])]));
    }
}
