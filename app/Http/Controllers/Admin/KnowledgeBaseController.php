<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\KnowledgeBaseRequest;

class KnowledgeBaseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(KnowledgeBase::class, 'article');
    }

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

    public function store(KnowledgeBaseRequest $request)
    {
        $validated = $request->validated();

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

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $articles = KnowledgeBase::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'title', 'slug', 'category']);

        return response()->json($articles->map(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'category' => $article->category,
                'url' => route('faq', ['search' => $article->title]),
            ];
        }));
    }

    public function edit(KnowledgeBase $article)
    {
        return view('admin.wiki.edit', compact('article'));
    }

    public function update(KnowledgeBaseRequest $request, KnowledgeBase $article)
    {
        $validated = $request->validated();

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
