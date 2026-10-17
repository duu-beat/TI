<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Supplier::class);

        $suppliers = Supplier::query()
            ->withCount('assets')
            ->orderBy('name')
            ->get();

        return view('admin.inventory.suppliers.index', compact('suppliers'));
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $this->authorize('create', Supplier::class);
        Supplier::create($request->validated());

        return back()->with('success', __('assets.suppliers.created'));
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->authorize('update', $supplier);
        $supplier->update($request->validated());

        return back()->with('success', __('assets.suppliers.updated'));
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->authorize('delete', $supplier);
        $supplier->delete();

        return back()->with('success', __('assets.suppliers.deleted'));
    }
}
