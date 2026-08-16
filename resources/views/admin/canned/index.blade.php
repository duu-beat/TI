<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-3 4-3-4z"></path></svg>
                </div>
                Respostas Prontas
            </h2>
            <div class="text-xs text-slate-500">
                {{ $responses->count() }} Respostas cadastradas
            </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SKELETON LOADER --}}
            <div x-show="!loaded" class="grid lg:grid-cols-3 gap-8 animate-pulse">
                <div class="lg:col-span-1">
                    <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                </div>
                <div class="lg:col-span-2 space-y-4">
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
                    <div class="rounded-3xl border border-white/10 bg-slate-900/60 overflow-hidden shadow-xl backdrop-blur-sm p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                            Nova Resposta
                        </h3>
                        
                        <form action="{{ route('admin.respostas-prontas.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block">Título / Identificador</label>
                                <input type="text" name="title" required 
                                       class="w-full px-4 py-3 rounded-xl bg-slate-950/50 border border-white/10 text-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition placeholder-slate-600 text-sm shadow-inner shadow-black/20"
                                       placeholder="Ex: Encerramento de chamado">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block">Categoria (Opcional)</label>
                                <input type="text" name="category" list="categories-list"
                                       class="w-full px-4 py-3 rounded-xl bg-slate-950/50 border border-white/10 text-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition placeholder-slate-600 text-sm shadow-inner shadow-black/20"
                                       placeholder="Ex: Hardware, Acessos...">
                                <datalist id="categories-list">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">
                                    @endforeach
                                </datalist>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block">Conteúdo da Mensagem</label>
                                <textarea name="content" rows="6" required
                                          class="w-full px-4 py-3 rounded-xl bg-slate-950/50 border border-white/10 text-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition placeholder-slate-600 text-sm shadow-inner shadow-black/20"
                                          placeholder="Olá, estamos encerrando este chamado..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-900/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Salvar Resposta
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 2. Lista de Respostas (Coluna Direita) --}}
                <div class="lg:col-span-2 space-y-6" x-data="{ editingId: null }">
                    @forelse($responses as $response)
                        <div class="group rounded-3xl border border-white/10 bg-slate-900/60 hover:border-indigo-500/30 shadow-2xl backdrop-blur-sm p-6 transition-all duration-300">
                            
                            {{-- Modo Visualização --}}
                            <div x-show="editingId !== {{ $response->id }}">
                                <div class="flex justify-between items-start gap-4 mb-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <span class="px-2 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 text-[9px] font-bold uppercase tracking-wider border border-indigo-500/20 shadow-sm">
                                                {{ $response->category ?? 'Geral' }}
                                            </span>
                                            <h4 class="text-white font-bold text-lg truncate">
                                                {{ $response->title }}
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editingId = {{ $response->id }}" class="p-2 rounded-xl bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white border border-white/5 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.respostas-prontas.destroy', $response) }}" method="POST" onsubmit="return confirm('Apagar esta resposta?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white border border-white/5 transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="bg-slate-950/50 p-5 rounded-2xl border border-white/5 font-mono text-sm text-slate-300 leading-relaxed whitespace-pre-wrap shadow-inner shadow-black/20">
{{ $response->content }}
                                </div>
                            </div>

                            {{-- Modo Edição --}}
                            <div x-show="editingId === {{ $response->id }}" x-cloak>
                                <form action="{{ route('admin.respostas-prontas.update', $response) }}" method="POST" class="space-y-4">
                                    @csrf @method('PATCH')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Título</label>
                                            <input type="text" name="title" value="{{ $response->title }}" required 
                                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-sm text-white focus:border-indigo-500 outline-none p-2.5 transition shadow-inner">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Categoria</label>
                                            <input type="text" name="category" value="{{ $response->category }}" list="categories-list-edit"
                                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-sm text-white focus:border-indigo-500 outline-none p-2.5 transition shadow-inner">
                                            <datalist id="categories-list-edit">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat }}">
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 block">Conteúdo</label>
                                        <textarea name="content" rows="4" required 
                                                  class="w-full bg-slate-950 border-white/10 rounded-xl text-sm text-white focus:border-indigo-500 outline-none p-2.5 transition shadow-inner">{{ $response->content }}</textarea>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-2 border-t border-white/5">
                                        <button type="button" @click="editingId = null" 
                                                class="px-5 py-2 rounded-xl text-slate-400 hover:text-white text-xs font-bold transition">
                                            Cancelar
                                        </button>
                                        <button type="submit" 
                                                class="px-6 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-lg shadow-indigo-900/20 hover:bg-indigo-500 transition active:scale-95">
                                            Salvar Alterações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-white/10 bg-slate-900/30 px-6 py-20 text-center backdrop-blur-sm">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-20 w-20 bg-slate-800/50 rounded-3xl flex items-center justify-center mb-6 border border-white/5 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                                <h3 class="text-white font-black text-xl mb-2">Sem respostas rápidas</h3>
                                <p class="text-slate-500 text-sm max-w-xs mx-auto">Cadastre frases frequentes para responder chamados com um único clique.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
