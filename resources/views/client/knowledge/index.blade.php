<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-cyan-400/20 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.25C7.7 4.15 4.55 5.18 3 6.16v11.16c1.55-.98 4.7-2.01 9 0 4.3-2.01 7.45-0.98 9 0V6.16c-1.55-.98-4.7-2.01-9 0Zm0 0v11.07" /></svg>
                </span>
                <div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-300">Autoatendimento</p><h2 class="truncate text-lg font-bold text-white sm:text-xl">Base de Conhecimento</h2></div>
            </div>
            <a href="{{ route('client.tickets.create') }}" class="inline-flex w-fit items-center justify-center gap-2 rounded-xl border border-indigo-400/25 bg-indigo-500/10 px-3 py-2 text-xs font-semibold text-indigo-200 transition hover:bg-indigo-500 hover:text-white"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" /></svg>Abrir chamado</a>
        </div>
    </x-slot>

    <main class="min-h-screen py-6 sm:py-8" aria-labelledby="knowledge-title">
        <div class="mx-auto max-w-7xl space-y-7 px-4 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl border border-cyan-400/15 bg-slate-900/70 px-6 py-7 shadow-2xl shadow-slate-950/25 backdrop-blur-xl sm:px-8 sm:py-9">
                <div class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full bg-cyan-500/12 blur-3xl"></div>
                <div class="relative max-w-3xl"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-cyan-300">Encontre antes de abrir</p><h1 id="knowledge-title" class="mt-3 text-2xl font-black tracking-tight text-white sm:text-3xl">Talvez a solução já esteja aqui.</h1><p class="mt-3 text-sm leading-6 text-slate-400">Pesquise procedimentos publicados pela equipe de TI e resolva dúvidas comuns sem esperar atendimento.</p></div>

                <form method="GET" action="{{ route('client.knowledge.index') }}" class="relative mt-6 max-w-3xl">
                    <label for="knowledge-search" class="sr-only">Pesquisar na Base de Conhecimento</label>
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" /></svg>
                    <input id="knowledge-search" name="search" value="{{ $search }}" type="search" class="w-full rounded-2xl border border-white/10 bg-slate-950/75 py-4 pl-12 pr-28 text-sm text-white shadow-inner shadow-black/20 outline-none transition placeholder:text-slate-600 focus:border-cyan-400/50 focus:ring-2 focus:ring-cyan-500/15" placeholder="Ex.: VPN, senha, impressora, e-mail..." />
                    <button type="submit" class="absolute right-2 top-2 rounded-xl bg-cyan-500 px-4 py-2 text-xs font-bold text-slate-950 transition hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200">Buscar</button>
                </form>
            </section>

            @if($categories->isNotEmpty())
                <nav class="flex flex-wrap gap-2" aria-label="Filtrar artigos por categoria">
                    <a href="{{ route('client.knowledge.index', ['search' => $search ?: null]) }}" class="rounded-xl border px-3 py-2 text-xs font-semibold transition {{ !$category ? 'border-cyan-400/30 bg-cyan-500/10 text-cyan-200' : 'border-white/10 bg-slate-900/50 text-slate-400 hover:border-white/20 hover:text-white' }}">Todos os artigos</a>
                    @foreach($categories as $item)
                        <a href="{{ route('client.knowledge.index', array_filter(['category' => $item, 'search' => $search ?: null])) }}" class="rounded-xl border px-3 py-2 text-xs font-semibold transition {{ $category === $item ? 'border-cyan-400/30 bg-cyan-500/10 text-cyan-200' : 'border-white/10 bg-slate-900/50 text-slate-400 hover:border-white/20 hover:text-white' }}">{{ $item }}</a>
                    @endforeach
                </nav>
            @endif

            <div class="grid gap-7 lg:grid-cols-12">
                <section class="lg:col-span-8" aria-labelledby="knowledge-results-title">
                    <div class="mb-4 flex items-center justify-between gap-4"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-300">Artigos publicados</p><h2 id="knowledge-results-title" class="mt-1 text-lg font-bold text-white">{{ $search ? 'Resultados para “' . $search . '”' : 'Conteúdo para te ajudar' }}</h2></div><span class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-[11px] font-semibold text-slate-400">{{ $articles->total() }} {{ $articles->total() === 1 ? 'artigo' : 'artigos' }}</span></div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @forelse($articles as $article)
                            <article class="group flex h-full flex-col rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/15 transition hover:-translate-y-0.5 hover:border-cyan-400/30 hover:bg-slate-900/90">
                                <div class="flex items-center justify-between gap-3"><span class="rounded-lg border border-cyan-400/15 bg-cyan-500/[0.07] px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-cyan-300">{{ $article->category ?: 'Geral' }}</span><span class="text-[10px] text-slate-600">Atualizado {{ $article->updated_at->diffForHumans() }}</span></div>
                                <h3 class="mt-4 text-base font-bold leading-6 text-white transition group-hover:text-cyan-200">{{ $article->title }}</h3>
                                <p class="mt-3 flex-1 text-xs leading-5 text-slate-400">{{ str($article->content)->stripTags()->squish()->limit(175) }}</p>
                                <a href="{{ route('client.knowledge.show', $article) }}" class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-cyan-300 transition group-hover:text-cyan-200">Ler procedimento<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a>
                            </article>
                        @empty
                            <div class="sm:col-span-2 rounded-3xl border border-dashed border-white/10 bg-slate-900/40 px-6 py-12 text-center"><svg class="mx-auto h-10 w-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" /></svg><h3 class="mt-4 text-sm font-bold text-white">Nenhum artigo encontrado</h3><p class="mx-auto mt-2 max-w-sm text-xs leading-5 text-slate-500">Tente uma expressão diferente ou abra um chamado para receber ajuda da equipe.</p><a href="{{ route('client.tickets.create') }}" class="mt-5 inline-flex rounded-xl bg-indigo-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-indigo-400">Abrir chamado</a></div>
                        @endforelse
                    </div>

                    @if($articles->hasPages())
                        <div class="mt-6">{{ $articles->links() }}</div>
                    @endif
                </section>

                <aside class="space-y-5 lg:col-span-4" aria-label="Artigos e orientações em destaque">
                    <section class="rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/15"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-amber-300">Mais consultados</p><h2 class="mt-1 text-base font-bold text-white">Em destaque</h2><div class="mt-4 space-y-2">@forelse($popularArticles as $article)<a href="{{ route('client.knowledge.show', $article) }}" class="group block rounded-2xl border border-white/5 bg-slate-950/35 p-3 transition hover:border-amber-400/20 hover:bg-white/[0.03]"><span class="block text-[10px] font-bold uppercase tracking-wider text-amber-300/80">{{ $article->category ?: 'Geral' }}</span><span class="mt-1 block text-xs font-semibold leading-5 text-slate-200 group-hover:text-white">{{ $article->title }}</span><span class="mt-2 block text-[10px] text-slate-600">{{ $article->views_count }} {{ $article->views_count === 1 ? 'leitura' : 'leituras' }}</span></a>@empty<p class="rounded-2xl border border-dashed border-white/10 px-3 py-5 text-center text-xs text-slate-500">Quando a equipe publicar artigos, eles aparecerão aqui.</p>@endforelse</div></section>

                    <section class="rounded-3xl border border-indigo-400/15 bg-gradient-to-br from-indigo-500/10 to-slate-900/85 p-5 shadow-xl shadow-slate-950/15"><svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg><h2 class="mt-4 text-sm font-bold text-white">Ainda precisa de ajuda?</h2><p class="mt-2 text-xs leading-5 text-slate-400">A Base de Conhecimento resolve dúvidas comuns. Se não for suficiente, conte o contexto completo em um chamado.</p><a href="{{ route('client.tickets.create') }}" class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-indigo-200 transition hover:text-white">Ir para novo chamado<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a></section>
                </aside>
            </div>
        </div>
    </main>
</x-app-layout>
