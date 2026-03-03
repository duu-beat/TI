<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Gerencia o inventário de equipamentos (Assets)
 */
class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('user');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->latest()->paginate(15);
        
        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.assets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:50|unique:assets',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'status' => 'required|in:active,maintenance,retired,lost',
            'purchase_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Equipamento cadastrado com sucesso!');
    }

    public function edit(Asset $asset)
    {
        $users = User::orderBy('name')->get();
        return view('admin.assets.edit', compact('asset', 'users'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:50|unique:assets,tag,' . $asset->id,
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'status' => 'required|in:active,maintenance,retired,lost',
            'purchase_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Equipamento atualizado com sucesso!');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('admin.assets.index')
            ->with('success', 'Equipamento removido do inventário.');
    }
}
