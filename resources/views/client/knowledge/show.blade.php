<x-app-layout>
    <x-slot name="header">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('client.knowledge.index') }}" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-slate-900/80 text-slate-400 transition hover:border-cyan-400/40 hover:bg-cyan-500/10 hover:text-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-500" aria-label="Voltar para a Base de Conhecimento"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 19-7-7 7-7" /></svg></a>
            <div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-300">Base de Conhecimento</p><h2 class="truncate text-lg font-bold text-white sm:text-xl">Procedimento de suporte</h2></div>
        </div>
    </x-slot>

    <main class="min-h-screen py-6 sm:py-8" aria-labelledby="article-title">
        <div class="mx-auto grid max-w-6xl gap-7 px-4 sm:px-6 lg:grid-cols-12 lg:px-8">
            <article class="lg:col-span-8">
                <div class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-2xl shadow-slate-950/25 backdrop-blur-xl">
                    <div class="relative border-b border-white/5 px-6 py-7 sm:px-8 sm:py-9"><div class="pointer-events-none absolute -right-16 -top-20 h-60 w-60 rounded-full bg-cyan-500/12 blur-3xl"></div><div class="relative"><div class="flex flex-wrap items-center gap-3"><span class="rounded-lg border border-cyan-400/20 bg-cyan-500/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-cyan-300">{{ $article->category ?: 'Geral' }}</span><span class="text-[11px] text-slate-500">Atualizado {{ $article->updated_at->format('d/m/Y') }}</span></div><h1 id="article-title" class="mt-5 text-2xl font-black leading-tight tracking-tight text-white sm:text-3xl">{{ $article->title }}</h1><p class="mt-4 text-sm leading-6 text-slate-400">Siga as orientações abaixo. Se o problema continuar, abra um chamado e informe o que já foi tentado.</p></div></div>
                    <div class="px-6 py-7 sm:px-8 sm:py-9"><div class="prose prose-invert max-w-none text-sm leading-7 text-slate-300 prose-headings:text-white prose-a:text-cyan-300 prose-a:no-underline hover:prose-a:text-cyan-200 hover:prose-a:underline prose-strong:text-slate-100">{!! nl2br(e($article->content)) !!}</div></div>
                    <footer class="flex flex-col gap-3 border-t border-white/5 bg-slate-950/30 px-6 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-8"><span>Publicado por {{ $article->author?->name ?? 'Equipe de TI' }}</span><span>{{ $article->views_count }} {{ $article->views_count === 1 ? 'leitura' : 'leituras' }}</span></footer>
                </div>

                <section class="mt-6 rounded-3xl border border-indigo-400/15 bg-gradient-to-r from-indigo-500/[0.09] to-slate-900/75 p-5 shadow-xl shadow-slate-950/15 sm:p-6" aria-labelledby="article-help-title"><div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-300">Próximo passo</p><h2 id="article-help-title" class="mt-1 text-base font-bold text-white">O procedimento não resolveu?</h2><p class="mt-1 text-xs leading-5 text-slate-400">Abra um chamado e mencione que você já seguiu este artigo.</p></div><a href="{{ route('client.tickets.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-500 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-400"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" /></svg>Abrir chamado</a></div></section>
            </article>

            <aside class="space-y-5 lg:col-span-4" aria-label="Artigos relacionados">
                <section class="rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/15"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-amber-300">Continue aprendendo</p><h2 class="mt-1 text-base font-bold text-white">Artigos relacionados</h2><div class="mt-4 space-y-2">@forelse($relatedArticles as $related)<a href="{{ route('client.knowledge.show', $related) }}" class="group block rounded-2xl border border-white/5 bg-slate-950/35 p-3 transition hover:border-cyan-400/20 hover:bg-white/[0.03]"><span class="block text-[10px] font-bold uppercase tracking-wider text-cyan-300/80">{{ $related->category ?: 'Geral' }}</span><span class="mt-1 block text-xs font-semibold leading-5 text-slate-200 group-hover:text-white">{{ $related->title }}</span></a>@empty<p class="rounded-2xl border border-dashed border-white/10 px-3 py-5 text-center text-xs text-slate-500">Não há outros artigos desta categoria no momento.</p>@endforelse</div></section>

                <a href="{{ route('client.knowledge.index') }}" class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-900/50 p-4 text-xs font-bold text-slate-300 transition hover:border-cyan-400/25 hover:text-white"><span>Explorar todos os artigos</span><svg class="h-4 w-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a>
            </aside>
        </div>
    </main>
</x-app-layout>
