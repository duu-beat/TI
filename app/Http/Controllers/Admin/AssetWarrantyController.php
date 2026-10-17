<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssetWarrantyRequest;
use App\Models\Asset;
use App\Models\AssetWarranty;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetWarrantyController extends Controller
{
    public function create(Asset $asset): View
    {
        $this->authorize('view', $asset);
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.assets.warranties.create', compact('asset', 'suppliers'));
    }

    public function store(AssetWarrantyRequest $request, Asset $asset): RedirectResponse
    {
        $this->authorize('update', $asset);
        $asset->warranties()->create($request->validated());
        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.warranties.created'));
    }

    public function edit(Asset $asset, AssetWarranty $warranty): View
    {
        $this->ensureBelongsToAsset($asset, $warranty);
        $suppliers = Supplier::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        return view('admin.assets.warranties.edit', compact('asset', 'warranty', 'suppliers'));
    }

    public function update(AssetWarrantyRequest $request, Asset $asset, AssetWarranty $warranty): RedirectResponse
    {
        $this->ensureBelongsToAsset($asset, $warranty);
        $warranty->update($request->validated());
        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.warranties.updated'));
    }

    public function destroy(Asset $asset, AssetWarranty $warranty): RedirectResponse
    {
        $this->ensureBelongsToAsset($asset, $warranty);
        $warranty->delete();
        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.warranties.deleted'));
    }

    private function ensureBelongsToAsset(Asset $asset, AssetWarranty $warranty): void
    {
        $this->authorize('update', $asset);
        abort_unless($warranty->asset_id === $asset->id, 404);
    }
}
