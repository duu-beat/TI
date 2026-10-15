<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Http\Requests\Admin\SectorRequest;
use App\Models\Branch;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryLocationController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Branch::class);

        $branches = Branch::query()
            ->withCount(['assets', 'sectors'])
            ->with(['sectors' => fn ($query) => $query->withCount('assets')->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin/inventory/locations/index', compact('branches'));
    }

    public function storeBranch(BranchRequest $request): RedirectResponse
    {
        $this->authorize('create', Branch::class);

        Branch::create($request->validated());

        return back()->with('success', __('assets.locations.branch_created'));
    }

    public function updateBranch(BranchRequest $request, Branch $branch): RedirectResponse
    {
        $this->authorize('update', $branch);

        $branch->update($request->validated());

        return back()->with('success', __('assets.locations.branch_updated'));
    }

    public function destroyBranch(Branch $branch): RedirectResponse
    {
        $this->authorize('delete', $branch);

        $branch->delete();

        return back()->with('success', __('assets.locations.branch_deleted'));
    }

    public function storeSector(SectorRequest $request): RedirectResponse
    {
        $this->authorize('create', Sector::class);

        Sector::create($request->validated());

        return back()->with('success', __('assets.locations.sector_created'));
    }

    public function updateSector(SectorRequest $request, Sector $sector): RedirectResponse
    {
        $this->authorize('update', $sector);

        $sector->update($request->validated());

        return back()->with('success', __('assets.locations.sector_updated'));
    }

    public function destroySector(Sector $sector): RedirectResponse
    {
        $this->authorize('delete', $sector);

        $sector->delete();

        return back()->with('success', __('assets.locations.sector_deleted'));
    }
}
