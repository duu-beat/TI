<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    {{ __('Respostas Prontas') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON LOADER --}}
            <div x-show="!loaded" class="grid lg:grid-cols-3 gap-8 animate-pulse">
                <div class="lg:col-span-1">
                    <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <div class="h-32 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                    <div class="h-32 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                    <div class="h-32 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                </div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 class="grid lg:grid-cols-3 gap-8"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                {{-- 1. Formulário de Criação (Coluna Esquerda) --}}
                <div class="lg:col-span-1">
                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 overflow-hidden shadow-xl backdrop-blur-sm p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-white mb-4">Nova Resposta</h3>
                        
                        <form action="{{ route('admin.respostas-prontas.store') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Título (Ex: Saudação)</label>
                                <input type="text" name="title" required 
                                       class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-slate-200 focus:border-yellow-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-yellow-500/20 outline-none transition placeholder-slate-600 text-sm"
                                       placeholder="Identificação rápida...">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Conteúdo da Mensagem</label>
                                <textarea name="content" rows="6" required
                                          class="w-full mt-1 px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-slate-200 focus:border-yellow-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-yellow-500/20 outline-none transition placeholder-slate-600 text-sm"
                                          placeholder="Olá, tudo bem? Recebemos seu chamado..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl bg-yellow-600 hover:bg-yellow-500 text-white font-bold shadow-lg shadow-yellow-500/20 transition hover:scale-[1.02] mt-2 border border-yellow-400/30">
                                Salvar Resposta
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 2. Lista de Respostas (Coluna Direita) --}}
                <div class="lg:col-span-2 space-y-4">
                    @if($responses->isEmpty())
                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 shadow-xl backdrop-blur-sm px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-white/5">
                                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                                <h3 class="text-white font-medium mb-1">Nenhuma resposta cadastrada</h3>
                                <p class="text-slate-500 text-sm">Use o formulário ao lado para criar seus modelos de resposta.</p>
                            </div>
                        </div>
                    @else
                        @foreach($responses as $response)
                            <div class="group rounded-2xl border border-white/10 bg-slate-900/60 hover:bg-slate-900/80 hover:border-yellow-500/30 shadow-xl backdrop-blur-sm p-6 transition-all duration-300">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <h4 class="text-yellow-400 font-bold text-lg mb-4 flex items-center gap-2">
                                            {{ $response->title }}
                                        </h4>
                                        <div class="bg-slate-950/50 p-4 rounded-xl border border-white/5 font-mono text-sm text-slate-300 leading-relaxed whitespace-pre-wrap">
{{ $response->content }}
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('admin.respostas-prontas.destroy', $response) }}" method="POST" onsubmit="return confirm('Apagar esta resposta?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-red-600 text-slate-400 hover:text-white border border-white/5 transition-all shadow-sm" title="Excluir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>