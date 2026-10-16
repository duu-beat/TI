<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TransferAssetRequest;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Branch;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssetTransferController extends Controller
{
    public function create(Asset $asset): View
    {
        $this->authorize('update', $asset);

        $users = User::query()->where('role', User::ROLE_CLIENT)->orderBy('name')->get(['id', 'name', 'email']);
        $branches = Branch::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $sectors = Sector::query()->where('is_active', true)->orderBy('name')->get(['id', 'branch_id', 'name']);

        return view('admin.assets.transfer', compact('asset', 'users', 'branches', 'sectors'));
    }

    public function store(TransferAssetRequest $request, Asset $asset): RedirectResponse
    {
        $this->authorize('update', $asset);
        $data = $request->validated();

        DB::transaction(function () use ($asset, $data): void {
            $lockedAsset = Asset::query()->whereKey($asset->getKey())->lockForUpdate()->firstOrFail();
            $oldUserId = $lockedAsset->user_id;
            $oldBranchId = $lockedAsset->branch_id;
            $oldSectorId = $lockedAsset->sector_id;

            $lockedAsset->update([
                'user_id' => $data['user_id'] ?? null,
                'branch_id' => $data['branch_id'] ?? null,
                'sector_id' => $data['sector_id'] ?? null,
            ]);

            $oldUser = $oldUserId ? User::find($oldUserId)?->name : __('assets.ui.available_in_stock');
            $newUser = !empty($data['user_id']) ? User::find($data['user_id'])?->name : __('assets.ui.available_in_stock');
            $oldBranch = $oldBranchId ? Branch::withTrashed()->find($oldBranchId)?->name : __('assets.ui.without_branch');
            $newBranch = !empty($data['branch_id']) ? Branch::find($data['branch_id'])?->name : __('assets.ui.without_branch');
            $oldSector = $oldSectorId ? Sector::withTrashed()->find($oldSectorId)?->name : __('assets.ui.without_sector');
            $newSector = !empty($data['sector_id']) ? Sector::find($data['sector_id'])?->name : __('assets.ui.without_sector');

            AssetHistory::create([
                'asset_id' => $lockedAsset->id,
                'user_id' => auth()->id(),
                'action' => 'transfer',
                'description' => __('assets.transfer.history_description', [
                    'reason' => $data['reason'],
                    'old_user' => $oldUser,
                    'new_user' => $newUser,
                    'old_branch' => $oldBranch,
                    'new_branch' => $newBranch,
                    'old_sector' => $oldSector,
                    'new_sector' => $newSector,
                ]),
                'old_user_id' => $oldUserId,
                'new_user_id' => $lockedAsset->user_id,
                'old_branch_id' => $oldBranchId,
                'new_branch_id' => $lockedAsset->branch_id,
                'old_sector_id' => $oldSectorId,
                'new_sector_id' => $lockedAsset->sector_id,
            ]);
        });

        return redirect()->route('admin.assets.show', $asset)->with('success', __('assets.transfer.completed'));
    }
}
