@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-white/10 text-white">
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
    <div class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/5 p-6">
        {{-- ⚠️ IMPORTANTE: enctype adicionado aqui --}}
        <form method="POST" action="{{ route('client.tickets.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="text-sm text-slate-300">Assunto</label>
                <input name="subject" type="text" value="{{ old('subject') }}"
                       class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                             focus:border-cyan-400/60 focus:ring-cyan-400/20"
                       placeholder="Ex: PC não liga, Wi-Fi caindo, lentidão..." required>
                @error('subject') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-300">Descrição</label>
                <textarea name="description" rows="7"
                          class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                 focus:border-cyan-400/60 focus:ring-cyan-400/20"
                          placeholder="Descreva o problema, quando começou e o que você já tentou..." required>{{ old('description') }}</textarea>
                @error('description') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-300">Prioridade (opcional)</label>
                    <select name="priority"
                            class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100
                                   focus:border-cyan-400/60 focus:ring-cyan-400/20">
                        <option value="">Selecione</option>
                        <option value="low" @selected(old('priority') === 'low')>Baixa</option>
                        <option value="medium" @selected(old('priority') === 'medium')>Média</option>
                        <option value="high" @selected(old('priority') === 'high')>Alta</option>
                    </select>
                    @error('priority') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                    <div class="text-sm font-semibold text-white">Dica rápida</div>
                    <p class="mt-2 text-sm text-slate-300">
                        Se puder, informe:
                        modelo do PC/notebook, mensagens de erro e se o problema aconteceu após alguma atualização.
                    </p>
                </div>
            </div>

            {{-- 📎 Input de Anexo Estilizado para Dark Mode --}}
            <div class="mt-4 p-4 rounded-2xl border border-dashed border-white/20 bg-white/5 hover:bg-white/10 transition">
                <label class="block text-sm text-slate-300 mb-2">Anexo (Opcional)</label>
                <input type="file" 
                       name="attachment" 
                       id="attachment"
                       class="block w-full text-sm text-slate-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-cyan-500/10 file:text-cyan-400
                              hover:file:bg-cyan-500/20
                              cursor-pointer"
                >
                <p class="mt-2 text-xs text-slate-500">Imagens (PNG, JPG) ou PDF. Máximo 2MB.</p>
                @error('attachment') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 flex-wrap pt-2">
                <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                    Abrir chamado
                </button>

                <a href="{{ route('client.tickets.index') }}"
                   class="rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Side info --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 h-fit">
        <div class="text-sm font-semibold text-white">Como funciona</div>

        <div class="mt-4 space-y-3 text-sm text-slate-300">
            <div class="rounded-xl border border-white/10 bg-slate-950/40 p-4">
                <div class="font-semibold text-white">1) Você abre o chamado</div>
                <div class="mt-1 text-slate-400">Descreva bem e mande detalhes.</div>
            </div>

            <div class="rounded-xl border border-white/10 bg-slate-950/40 p-4">
                <div class="font-semibold text-white">2) Eu respondo</div>
                <div class="mt-1 text-slate-400">Pelo portal, com próximos passos.</div>
            </div>

            <div class="rounded-xl border border-white/10 bg-slate-950/40 p-4">
                <div class="font-semibold text-white">3) Resolvemos</div>
                <div class="mt-1 text-slate-400">E você acompanha tudo aqui.</div>
            </div>
        </div>
    </div>
</div>
@endsection