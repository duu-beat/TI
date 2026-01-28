@extends('layouts.portal')

@section('title', 'Abrir Novo Chamado')

@section('actions')
    <a href="{{ route('client.tickets.index') }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
        Voltar
    </a>
@endsection

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
    
    {{-- 💀 SKELETON --}}
    <div x-show="!loaded" class="animate-pulse space-y-6">
        <div class="h-12 bg-white/5 rounded-2xl w-full"></div>
        <div class="h-64 bg-white/5 rounded-2xl w-full"></div>
    </div>

    {{-- ✅ FORMULÁRIO --}}
    <div x-show="loaded" style="display: none;"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        <form method="POST" action="{{ route('client.tickets.store') }}" enctype="multipart/form-data" class="max-w-4xl mx-auto space-y-8">
            @csrf

            {{-- Card Principal --}}
            <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl shadow-2xl overflow-hidden p-8">
                
                {{-- Assunto --}}
                <div class="mb-6">
                    <label for="subject" class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-wide">Assunto</label>
                    <input type="text" name="subject" id="subject" required
                           class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-5 py-4 text-white placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all outline-none"
                           placeholder="Ex: Minha internet está lenta...">
                    @error('subject') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Descrição --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-wide">Descrição detalhada</label>
                    <textarea name="description" id="description" rows="6" required
                              class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-5 py-4 text-white placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all outline-none"
                              placeholder="Descreva o problema com o máximo de detalhes possível..."></textarea>
                    @error('description') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Upload Drag & Drop --}}
                <div x-data="{ files: [] }" class="mb-6">
                    <label class="block text-sm font-bold text-slate-300 mb-2 uppercase tracking-wide">Anexos (Opcional)</label>
                    <div class="relative group cursor-pointer">
                        <input type="file" name="attachments[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               @change="files = [...$event.target.files]">
                        
                        <div class="border-2 border-dashed border-white/10 rounded-2xl p-8 text-center transition-all group-hover:border-cyan-500/30 group-hover:bg-cyan-500/5">
                            <div class="mb-3 text-4xl opacity-50 group-hover:opacity-100 group-hover:scale-110 transition">📎</div>
                            <p class="text-sm text-slate-400 group-hover:text-cyan-200 transition">
                                Arraste arquivos aqui ou <span class="text-cyan-400 font-bold underline">clique para selecionar</span>
                            </p>
                            <p class="text-xs text-slate-600 mt-1">Imagens, PDFs (Máx 5MB)</p>
                        </div>
                    </div>

                    {{-- Lista de Arquivos Selecionados --}}
                    <div class="mt-3 grid grid-cols-2 gap-3" x-show="files.length > 0">
                        <template x-for="file in files">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">
                                <div class="h-8 w-8 rounded flex items-center justify-center bg-slate-700 text-xs">📄</div>
                                <div class="overflow-hidden">
                                    <div class="text-xs text-white truncate" x-text="file.name"></div>
                                    <div class="text-[10px] text-slate-400" x-text="(file.size / 1024).toFixed(1) + ' KB'"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                    @error('attachments') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    @error('attachments.*') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Botão Enviar --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-8 py-3 font-bold text-slate-950 shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/40 hover:scale-105 hover:brightness-110 active:scale-95 transition-all duration-300">
                        🚀 Criar Chamado
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection