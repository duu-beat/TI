@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        🎫 Meus chamados
    </a>

    <a href="{{ route('profile.show') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        👤 Minha conta
    </a>
@endsection

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
        <div class="h-12 bg-white/5 rounded-xl w-full border border-white/5"></div>
        <div class="h-40 bg-white/5 rounded-xl w-full border border-white/5"></div>
        <div class="h-12 bg-white/5 rounded-xl w-1/3 border border-white/5"></div>
    </div>

    {{-- ✅ FORMULÁRIO REAL --}}
    <div x-show="loaded" style="display: none;"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
            <form action="{{ route('client.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Assunto --}}
                <div>
                    <label for="subject" class="block text-sm font-medium text-slate-300 mb-1">Assunto</label>
                    <input type="text" name="subject" id="subject" required
                           class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400/50 focus:ring-cyan-400/20 transition"
                           placeholder="Ex: Minha internet está lenta..."
                           value="{{ old('subject') }}">
                    @error('subject') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Prioridade --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Prioridade</label>
                    <div class="flex gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="low" class="peer sr-only" checked>
                            <div class="px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-slate-400 text-sm font-medium transition-all peer-checked:bg-emerald-500/20 peer-checked:text-emerald-400 peer-checked:border-emerald-500/50 hover:bg-white/10">
                                🟢 Normal
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="medium" class="peer sr-only">
                            <div class="px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-slate-400 text-sm font-medium transition-all peer-checked:bg-yellow-500/20 peer-checked:text-yellow-400 peer-checked:border-yellow-500/50 hover:bg-white/10">
                                ⚠️ Média
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="high" class="peer sr-only">
                            <div class="px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-slate-400 text-sm font-medium transition-all peer-checked:bg-red-500/20 peer-checked:text-red-400 peer-checked:border-red-500/50 hover:bg-white/10">
                                🔥 Alta
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Descrição --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-300 mb-1">Descrição detalhada</label>
                    <textarea name="description" id="description" rows="5" required
                              class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400/50 focus:ring-cyan-400/20 transition"
                              placeholder="Descreva o problema com o máximo de detalhes possível...">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Anexos com Preview --}}
                <div x-data="{ files: [] }" class="pt-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Anexos (Opcional)</label>
                    
                    <div class="flex items-center gap-4">
                        <label class="cursor-pointer inline-flex items-center gap-2 rounded-xl bg-white/5 border border-white/10 px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition group">
                            <span class="text-lg group-hover:scale-110 transition">📎</span>
                            <span x-text="files.length > 0 ? 'Adicionar mais' : 'Escolher arquivos'"></span>
                            <input type="file" name="attachments[]" multiple class="hidden"
                                   @change="files = Array.from($el.files)">
                        </label>
                        <span class="text-xs text-slate-500">Máx: 5MB por arquivo</span>
                    </div>

                    {{-- Lista de arquivos selecionados --}}
                    <div x-show="files.length > 0" class="mt-3 grid gap-2 grid-cols-1 sm:grid-cols-2">
                        <template x-for="file in files">
                            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-800/50 border border-white/5">
                                <div class="h-8 w-8 rounded flex items-center justify-center bg-slate-700 text-xs">📄</div>
                                <div class="overflow-hidden">
                                    <div class="text-xs text-white truncate" x-text="file.name"></div>
                                    <div class="text-[10px] text-slate-400" x-text="(file.size / 1024).toFixed(1) + ' KB'"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                    {{-- MUDANÇA AQUI: @error('attachments') --}}
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

            </form>
        </div>
    </div>
</div>
@endsection