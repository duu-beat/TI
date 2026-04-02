<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Gerencia os modelos de checklist por categoria
 */
class ChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = ChecklistTemplate::withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $templates = $query->latest()->get();
        return view('admin.checklists.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.checklists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $template = ChecklistTemplate::create([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            foreach ($validated['items'] as $index => $itemText) {
                $template->items()->create([
                    'content' => $itemText,
                    'order' => $index,
                ]);
            }
        });

        return redirect()->route('admin.checklists.index')
            ->with('success', 'Modelo de checklist criado com sucesso!');
    }

    public function edit(ChecklistTemplate $checklist)
    {
        $checklist->load('items');
        return view('admin.checklists.edit', compact('checklist'));
    }

    public function update(Request $request, ChecklistTemplate $checklist)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $checklist) {
            $checklist->update([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Simplificação: remove todos e recria (em produção, o ideal é sincronizar IDs)
            $checklist->items()->delete();

            foreach ($validated['items'] as $index => $itemText) {
                $checklist->items()->create([
                    'content' => $itemText,
                    'order' => $index,
                ]);
            }
        });

        return redirect()->route('admin.checklists.index')
            ->with('success', 'Modelo de checklist atualizado!');
    }

    public function destroy(ChecklistTemplate $checklist)
    {
        $checklist->delete();
        return redirect()->route('admin.checklists.index')
            ->with('success', 'Modelo de checklist removido.');
    }
}
