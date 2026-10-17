<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssetContractRequest;
use App\Models\Asset;
use App\Models\AssetContract;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetContractController extends Controller
{
    public function create(Asset $asset): View
    {
        $this->authorize('view', $asset);
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.assets.contracts.create', compact('asset', 'suppliers'));
    }
    public function store(AssetContractRequest $request, Asset $asset): RedirectResponse
    {
        $this->authorize('update', $asset);
        $asset->contracts()->create($request->validated());
        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.contracts.created'));
    }
    public function edit(Asset $asset, AssetContract $contract): View
    {
        $this->ensureBelongsToAsset($asset, $contract);
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.assets.contracts.edit', compact('asset', 'contract', 'suppliers'));
    }
    public function update(AssetContractRequest $request, Asset $asset, AssetContract $contract): RedirectResponse
    {
        $this->ensureBelongsToAsset($asset, $contract);
        $contract->update($request->validated());
        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.contracts.updated'));
    }
    public function destroy(Asset $asset, AssetContract $contract): RedirectResponse
    {
        $this->ensureBelongsToAsset($asset, $contract);
        $contract->delete();
        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.contracts.deleted'));
    }
    private function ensureBelongsToAsset(Asset $asset, AssetContract $contract): void
    {
        $this->authorize('update', $asset);
        abort_unless($contract->asset_id === $asset->id, 404);
    }
}
