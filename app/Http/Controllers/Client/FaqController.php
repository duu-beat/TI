<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Portal de Base de Conhecimento para clientes.
 *
 * Exibe somente artigos publicados, mantendo a gestão editorial centralizada
 * na Wiki administrativa e sem expor rascunhos ao usuário final.
 */
class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $articles = $this->publishedArticlesQuery($search, $category)
            ->with('author:id,name')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = KnowledgeBase::query()
            ->where('is_published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $popularArticles = KnowledgeBase::query()
            ->where('is_published', true)
            ->orderByDesc('views_count')
            ->latest('updated_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'category', 'views_count']);

        return view('client.knowledge.index', compact('articles', 'categories', 'popularArticles', 'search', 'category'));
    }

    public function show(KnowledgeBase $article): View
    {
        abort_unless($article->is_published, 404);

        $article->increment('views_count');
        $article->load('author:id,name');

        $relatedArticles = KnowledgeBase::query()
            ->where('is_published', true)
            ->whereKeyNot($article->id)
            ->when($article->category, fn ($query) => $query->where('category', $article->category))
            ->latest('updated_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'category']);

        return view('client.knowledge.show', compact('article', 'relatedArticles'));
    }

    /**
     * Busca enxuta para as sugestões exibidas durante a abertura do chamado.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json([]);
        }

        $articles = $this->publishedArticlesQuery($query)
            ->latest('updated_at')
            ->limit(4)
            ->get(['id', 'title', 'slug', 'category', 'content']);

        return response()->json($articles->map(fn (KnowledgeBase $article) => [
            'id' => $article->id,
            'title' => $article->title,
            'category' => $article->category,
            'excerpt' => str($article->content)->stripTags()->squish()->limit(150)->toString(),
            'url' => route('client.knowledge.show', $article),
        ]));
    }

    private function publishedArticlesQuery(?string $search = null, ?string $category = null)
    {
        return KnowledgeBase::query()
            ->where('is_published', true)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category));
    }
}
