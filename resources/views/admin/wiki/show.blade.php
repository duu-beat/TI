<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.wiki.index') }}" class="p-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-xl text-white leading-tight">
                    {{ $article->title }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.wiki.edit', $article) }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm transition border border-white/5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/50 border border-white/10 rounded-3xl overflow-hidden backdrop-blur-sm">
                {{-- Capa / Header do Artigo --}}
                <div class="p-8 border-b border-white/5 bg-gradient-to-br from-indigo-500/5 to-transparent">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-[10px] font-bold uppercase tracking-widest border border-indigo-500/20">
                            {{ $article->category }}
                        </span>
                        <span class="text-slate-500 text-xs">•</span>
                        <span class="text-slate-500 text-xs">Publicado em {{ $article->created_at->format('d/m/Y') }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-white mb-6">{{ $article->title }}</h1>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
                                {{ substr($article->author->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">{{ $article->author->name ?? 'Sistema' }}</div>
                                <div class="text-xs text-slate-500">Autor do Artigo</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-slate-400 text-sm">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ $article->views_count }} visualizações
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Conteúdo --}}
                <div class="p-8 prose prose-invert prose-indigo max-w-none">
                    {!! nl2br(e($article->content)) !!}
                </div>

                {{-- Footer --}}
                <div class="p-8 bg-slate-950/50 border-t border-white/5 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        Última atualização: {{ $article->updated_at->diffForHumans() }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Compartilhar:</span>
                        <button class="p-2 rounded-lg bg-slate-800 text-slate-400 hover:text-white transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.33 3.67a10 10 0 1 0-11.32 15.31l-4.34 1.37a.5.5 0 0 0-.62.62l1.37 4.34a10 10 0 1 0 14.91-11.64z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
