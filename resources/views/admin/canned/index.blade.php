<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-2">
            <span class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400">⚡</span>
            Respostas Prontas
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 400)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-8">
            
            {{-- 1. Formulário de Criação (Coluna Esquerda) --}}
            <div class="lg:col-span-1">
                <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 shadow-xl sticky top-6">
                    <h3 class="text-lg font-bold text-white mb-4">Nova Resposta</h3>
                    
                    <form action="{{ route('admin.respostas-prontas.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Título (Ex: Saudação)</label>
                            <input type="text" name="title" required 
                                   class="w-full mt-1 bg-slate-950 border border-white/10 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Identificação rápida...">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">Conteúdo da Mensagem</label>
                            <textarea name="content" rows="6" required
                                      class="w-full mt-1 bg-slate-950 border border-white/10 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Olá, tudo bem? Recebemos seu chamado..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-500/20 transition hover:scale-[1.02]">
                            Salvar Resposta
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. Lista de Respostas (Coluna Direita) --}}
            <div class="lg:col-span-2 space-y-4">
                
                {{-- Skeleton Loader --}}
                <div x-show="!loaded" class="space-y-4 animate-pulse">
                    @for($i = 0; $i < 5; $i++)
                    <div class="bg-slate-800/50 border border-white/5 rounded-2xl p-5">
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <div class="flex-1 space-y-2">
                                <div class="h-6 w-48 bg-slate-700/50 rounded"></div>
                                <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-9 w-20 bg-slate-700/50 rounded-lg"></div>
                                <div class="h-9 w-20 bg-slate-700/50 rounded-lg"></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-3 bg-slate-700/50 rounded w-full"></div>
                            <div class="h-3 bg-slate-700/50 rounded w-5/6"></div>
                            <div class="h-3 bg-slate-700/50 rounded w-4/6"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                
                {{-- Conteúdo Real --}}
                <div x-show="loaded" style="display: none;">
                @if($responses->count() > 0)
                    @foreach($responses as $response)
                        <div class="group bg-slate-800/50 hover:bg-slate-800 border border-white/5 hover:border-indigo-500/30 rounded-2xl p-5 transition-all duration-300">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h4 class="text-indigo-400 font-bold text-lg mb-2 flex items-center gap-2">
                                        {{ $response->title }}
                                    </h4>
                                    <div class="bg-slate-950/50 p-4 rounded-xl border border-white/5 font-mono text-xs text-slate-400 leading-relaxed whitespace-pre-wrap">
{{ $response->content }}
                                    </div>
                                </div>
                                
                                <form action="{{ route('admin.respostas-prontas.destroy', $response) }}" method="POST" onsubmit="return confirm('Apagar esta resposta?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12 px-6 rounded-3xl border border-dashed border-white/10 bg-white/5">
                        <div class="text-4xl mb-4">📝</div>
                        <h3 class="text-white font-bold text-lg">Nenhuma resposta cadastrada</h3>
                        <p class="text-slate-400 text-sm mt-2">Use o formulário ao lado para criar seus modelos de resposta.</p>
                    </div>
                @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>