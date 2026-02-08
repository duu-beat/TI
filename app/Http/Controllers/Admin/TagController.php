<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('tickets')
            ->latest()
            ->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Tag::create($validated);

        return back()->with('success', 'Tag criada com sucesso!');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag->update($validated);

        return back()->with('success', 'Tag atualizada com sucesso!');
    }

    public function destroy(Tag $tag)
    {
        // Remove relacionamentos antes de deletar
        $tag->tickets()->detach();
        $tag->delete();

        return back()->with('success', 'Tag removida com sucesso!');
    }

    /**
     * Adicionar/remover tags de um ticket via AJAX
     */
    public function attachToTicket(Request $request, $ticketId)
    {
        $request->validate([
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $ticket = \App\Models\Ticket::findOrFail($ticketId);
        $ticket->tags()->sync($request->tag_ids);

        return back()->with('success', 'Tags atualizadas!');
    }
}
