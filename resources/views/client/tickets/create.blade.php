@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-white/10 text-white font-medium shadow-lg shadow-black/20">
        🎫 Meus chamados
    </a>
@endsection

@section('title', 'Novo chamado')

@section('actions')
    <a href="{{ route('client.tickets.index') }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
        Voltar
    </a>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
        <form method="POST" action="{{ route('client.tickets.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- ASSUNTO --}}
            <div>
                <label class="text-sm text-slate-300 font-medium">Assunto</label>
                <input name="subject" type="text" value="{{ old('subject') }}"
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                              focus:border-cyan-400/60 focus:ring-cyan-400/20 transition-all duration-300"
                       placeholder="Ex: PC não liga, Wi-Fi caindo, lentidão..." required>
                @error('subject') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
            </div>

            {{-- DESCRIÇÃO --}}
            <div>
                <label class="text-sm text-slate-300 font-medium">Descrição</label>
                <textarea name="description" rows="7"
                          class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                 focus:border-cyan-400/60 focus:ring-cyan-400/20 transition-all duration-300"
                          placeholder="Descreva o problema, quando começou e o que você já tentou..." required>{{ old('description') }}</textarea>
                @error('description') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                {{-- PRIORIDADE --}}
                <div>
                    <label class="text-sm text-slate-300 font-medium">Prioridade (opcional)</label>
                    <div class="relative mt-2">
                        <select name="priority"
                                class="w-full appearance-none rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100
                                       focus:border-cyan-400/60 focus:ring-cyan-400/20 transition-all duration-300">
                            <option value="">Selecione</option>
                            <option value="low" @selected(old('priority') === 'low')>🟢 Baixa</option>
                            <option value="medium" @selected(old('priority') === 'medium')>⚠️ Média</option>
                            <option value="high" @selected(old('priority') === 'high')>🔥 Alta</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('priority') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                {{-- DICA --}}
                <div class="rounded-2xl border border-white/10 bg-indigo-500/5 p-4">
                    <div class="text-sm font-semibold text-indigo-200 flex items-center gap-2">
                        💡 Dica rápida
                    </div>
                    <p class="mt-2 text-xs text-slate-300 leading-relaxed">
                        Se puder, informe o modelo do equipamento e mensagens de erro que apareceram na tela.
                    </p>
                </div>
            </div>

            {{-- 📂 UPLOAD DRAG & DROP MODERNO (Alpine.js) --}}
            <div x-data="{ files: null, isDragging: false }">
                <label class="block text-sm font-medium text-slate-300 mb-2">Anexo (Opcional)</label>
                
                <div class="relative group">
                    {{-- Input Invisível (mas funcional) --}}
                    <input type="file" name="attachment" class="hidden" 
                           x-ref="fileInput"
                           @change="files = $refs.fileInput.files">

                    {{-- Zona Visual de Arraste --}}
                    <div @click="$refs.fileInput.click()"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="isDragging = false; files = $event.dataTransfer.files; $refs.fileInput.files = $event.dataTransfer.files"
                         :class="isDragging ? 'border-cyan-400 bg-cyan-400/10 ring-2 ring-cyan-400/20 scale-[0.99]' : 'border-white/10 bg-slate-950/30 hover:bg-slate-900/50 hover:border-white/20'"
                         class="cursor-pointer border-2 border-dashed rounded-2xl p-8 text-center transition-all duration-300 h-32 flex flex-col items-center justify-center">
                        
                        {{-- Estado Vazio --}}
                        <div x-show="!files || files.length === 0" class="space-y-2 pointer-events-none">
                            <div class="mx-auto h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-xl group-hover:scale-110 transition group-hover:bg-slate-700 text-slate-300">
                                📎
                            </div>
                            <div class="text-sm text-slate-400">
                                <span class="text-cyan-400 font-semibold hover:underline">Clique para enviar</span> ou arraste
                            </div>
                        </div>

                        {{-- Estado com Arquivo Selecionado --}}
                        <div x-show="files && files.length > 0" class="w-full max-w-sm" style="display: none;">
                            <template x-for="file in files">
                                <div class="flex items-center justify-between p-2 pl-3 bg-cyan-500/10 rounded-xl border border-cyan-500/20 text-left">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <span class="text-lg">📄</span>
                                        <div class="min-w-0">
                                            <div class="text-sm text-cyan-100 font-medium truncate" x-text="file.name"></div>
                                            <div class="text-[10px] text-cyan-300/70" x-text="(file.size / 1024).toFixed(0) + ' KB'"></div>
                                        </div>
                                    </div>
                                    <button type="button" @click.stop="files = null; $refs.fileInput.value = ''" 
                                            class="p-2 text-cyan-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition">
                                        ✕
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <p class="mt-2 text-xs text-slate-500 text-center" x-show="!files || files.length === 0">
                        Imagens (PNG, JPG) ou PDF. Máximo 2MB.
                    </p>
                </div>
            </div>

            {{-- Botões de Ação --}}
            <div class="flex gap-3 flex-wrap pt-4 border-t border-white/5">
                <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-8 py-3 font-bold text-slate-950 
                               hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:brightness-110 active:scale-95 transition-all duration-300">
                    Abrir chamado
                </button>

                <a href="{{ route('client.tickets.index') }}"
                   class="rounded-2xl bg-white/5 border border-white/5 px-6 py-3 font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Side info (Fixo à direita) --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 h-fit backdrop-blur-md sticky top-24">
        <div class="text-sm font-semibold text-white flex items-center gap-2 mb-4">
            ℹ️ Como funciona
        </div>

        <div class="space-y-4 text-sm relative">
            {{-- Linha conectora --}}
            <div class="absolute left-[15px] top-4 bottom-4 w-0.5 bg-white/10"></div>

            <div class="relative flex gap-4">
                <div class="h-8 w-8 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center text-xs font-bold text-cyan-400 z-10 shrink-0">1</div>
                <div>
                    <div class="font-semibold text-white">Você abre o chamado</div>
                    <div class="text-xs text-slate-400 mt-0.5">Descreva bem o problema.</div>
                </div>
            </div>

            <div class="relative flex gap-4">
                <div class="h-8 w-8 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center text-xs font-bold text-slate-400 z-10 shrink-0">2</div>
                <div>
                    <div class="font-semibold text-slate-300">Nós analisamos</div>
                    <div class="text-xs text-slate-500 mt-0.5">Verificamos a prioridade.</div>
                </div>
            </div>

            <div class="relative flex gap-4">
                <div class="h-8 w-8 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center text-xs font-bold text-slate-400 z-10 shrink-0">3</div>
                <div>
                    <div class="font-semibold text-slate-300">Solução</div>
                    <div class="text-xs text-slate-500 mt-0.5">Você acompanha tudo pelo portal.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection