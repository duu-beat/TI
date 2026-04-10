<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeBase::with('author');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(12);
        $categories = KnowledgeBase::distinct()->pluck('category');

        return view('admin.wiki.index', compact('articles', 'categories'));
    }

    public function create()
    {
        return view('admin.wiki.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'is_published' => 'boolean',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        KnowledgeBase::create($validated);

        return redirect()->route('admin.wiki.index')->with('success', 'Artigo criado com sucesso!');
    }

    public function show(KnowledgeBase $article)
    {
        $article->increment('views_count');
        return view('admin.wiki.show', compact('article'));
    }

    public function edit(KnowledgeBase $article)
    {
        return view('admin.wiki.edit', compact('article'));
    }

    public function update(Request $request, KnowledgeBase $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $article->update($validated);

        return redirect()->route('admin.wiki.index')->with('success', 'Artigo atualizado!');
    }

    public function destroy(KnowledgeBase $article)
    {
        $article->delete();
        return redirect()->route('admin.wiki.index')->with('success', 'Artigo removido!');
    }
}
