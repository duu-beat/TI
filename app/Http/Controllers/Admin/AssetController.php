<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\User;
use App\Models\AssetHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Gerencia o inventário de equipamentos (Assets)
 */
class AssetController extends Controller
{
    public function export(Request $request)
    {
        $assets = Asset::with('user')->latest()->get();
        
        $filename = "inventario_ti_" . date('Y-m-d_H-i') . ".csv";
        $handle = fopen('php://output', 'w');
        
        // Header
        fputcsv($handle, [
            'ID', 'Tag/Patrimonio', 'Nome', 'Tipo', 'Marca', 'Modelo', 
            'Serial', 'Status', 'Responsavel', 'Data Compra', 'Garantia'
        ]);

        foreach ($assets as $asset) {
            fputcsv($handle, [
                $asset->id,
                $asset->tag,
                $asset->name,
                $asset->type,
                $asset->brand,
                $asset->model,
                $asset->serial_number,
                $asset->getStatusLabel(),
                $asset->user->name ?? 'Em Estoque',
                $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-',
                $asset->warranty_expiration ? $asset->warranty_expiration->format('d/m/Y') : '-',
            ]);
        }

        return response()->stream(
            function () use ($handle) {
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }

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

        $asset = Asset::create($validated);

        // Registrar criação no histórico
        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Equipamento cadastrado no sistema.',
            'new_status' => $asset->status,
            'new_user_id' => $asset->user_id,
        ]);

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

        $oldStatus = $asset->status;
        $oldUserId = $asset->user_id;

        $asset->update($validated);

        // Verificar se houve mudança de status ou dono para registrar no histórico
        if ($oldStatus !== $asset->status || $oldUserId !== $asset->user_id) {
            $description = [];
            if ($oldStatus !== $asset->status) $description[] = "Status alterado de {$oldStatus} para {$asset->status}";
            if ($oldUserId !== $asset->user_id) {
                $oldUserName = $oldUserId ? User::find($oldUserId)?->name : 'Ninguém';
                $newUserName = $asset->user_id ? User::find($asset->user_id)?->name : 'Ninguém';
                $description[] = "Responsável alterado de {$oldUserName} para {$newUserName}";
            }

            AssetHistory::create([
                'asset_id' => $asset->id,
                'user_id' => Auth::id(),
                'action' => 'update',
                'description' => implode('. ', $description),
                'old_status' => $oldStatus,
                'new_status' => $asset->status,
                'old_user_id' => $oldUserId,
                'new_user_id' => $asset->user_id,
            ]);
        }

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
