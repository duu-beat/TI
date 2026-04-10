<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Base de Conhecimento (Wiki)
            </h2>
            <a href="{{ route('admin.wiki.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition flex items-center gap-2 shadow-lg shadow-indigo-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Novo Artigo
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Filtros e Busca --}}
            <div class="bg-slate-900/50 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                <form action="{{ route('admin.wiki.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título ou conteúdo..." 
                               class="w-full bg-slate-950 border-white/10 rounded-xl text-sm text-white focus:ring-indigo-500 focus:border-indigo-500 pl-10">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <select name="category" class="bg-slate-950 border-white/10 rounded-xl text-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todas as Categorias</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded-xl transition border border-white/5">
                        Filtrar
                    </button>
                </form>
            </div>

            {{-- Grid de Artigos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($articles as $article)
                    <div class="group bg-slate-900/60 border border-white/5 rounded-2xl p-6 hover:border-indigo-500/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 text-[10px] font-bold uppercase tracking-wider border border-indigo-500/20">
                                {{ $article->category }}
                            </span>
                            <div class="flex items-center gap-2 text-slate-500 text-[10px]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ $article->views_count }}
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2 group-hover:text-indigo-400 transition-colors">{{ $article->title }}</h3>
                        <p class="text-sm text-slate-400 line-clamp-3 mb-6">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-white/5">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-slate-800 flex items-center justify-center text-[10px] font-bold text-indigo-400">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                                <span class="text-xs text-slate-500">{{ $article->author->name }}</span>
                            </div>
                            <a href="{{ route('admin.wiki.show', $article) }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition flex items-center gap-1">
                                Ler mais
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-slate-900/30 rounded-3xl border border-dashed border-white/10">
                        <div class="text-4xl mb-4">📚</div>
                        <h3 class="text-lg font-bold text-white">Nenhum artigo encontrado</h3>
                        <p class="text-slate-500 text-sm">Comece criando o primeiro tutorial para a equipe.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
