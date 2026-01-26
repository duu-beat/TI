@extends('layouts.portal')

@section('menu')
    <a href="{{ route('admin.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        📊 Dashboard
    </a>

    <a href="{{ route('admin.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-white/10 text-white">
        🎫 Chamados
    </a>

{{-- Link Novo: Perfil --}}
    <a href="{{ route('profile.show') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        👤 Meu Perfil
    </a>

@endsection

@section('title')
    {{ $ticket->subject }}
@endsection

@section('actions')
    <a href="{{ route('admin.tickets.index') }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
        Voltar
    </a>
@endsection

@section('content')
@php
    $statusColors = [
        'new' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
        'in_progress' => 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30',
        'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
        'resolved' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
        'closed' => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
    ];
@endphp

<div class="space-y-4">
    {{-- Header info --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <div class="text-sm text-slate-400">Cliente</div>
                <div class="mt-1 text-white font-semibold">{{ $ticket->user->name }}</div>
                <div class="text-sm text-slate-400">{{ $ticket->user->email }}</div>

                <div class="mt-4 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400">Status:</span>
                    <span class="text-xs rounded-full px-3 py-1 font-medium
                        {{ $statusColors[$ticket->status->value] ?? 'bg-white/10 text-slate-200' }}">
                        {{ $ticket->status->label() }}
                    </span>

                    <span class="text-xs text-slate-500">•</span>
                    <span class="text-xs text-slate-400">
                        Criado em {{ $ticket->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>

            {{-- Update status --}}
            <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}"
                  class="flex gap-3 flex-wrap items-end">
                @csrf

                <div>
                    <label class="text-sm text-slate-300">Atualizar status</label>
                    <select name="status"
                        class="mt-2 rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100
                               focus:border-cyan-400/60 focus:ring-cyan-400/20">
                        {{-- Loop dinâmico usando o Enum --}}
                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($ticket->status === $status)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition">
                    Salvar
                </button>
            </form>
        </div>
    </div>

    {{-- Descrição inicial --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <div class="text-sm text-slate-300">Descrição inicial</div>
        <p class="mt-2 text-slate-200 whitespace-pre-line">{{ $ticket->description }}</p>
    </div>

    {{-- Mensagens --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 space-y-3">
        <div class="text-sm text-slate-300">Mensagens</div>

        @forelse($ticket->messages as $msg)
            @php
                $isAdmin = optional($msg->user)->role === 'admin';
            @endphp

            <div class="rounded-2xl border border-white/10 p-4 {{ $isAdmin ? 'bg-indigo-500/10 border-indigo-500/20' : 'bg-slate-950/40' }}">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm font-semibold {{ $isAdmin ? 'text-indigo-200' : 'text-white' }}">
                        {{ $isAdmin ? 'Admin' : $msg->user->name }}
                    </div>
                    <div class="text-xs text-slate-400">{{ $msg->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <p class="mt-2 text-slate-200 whitespace-pre-line">{{ $msg->message }}</p>
                
                {{-- Exibir Anexos se houver (para Admin ver também) --}}
                @if($msg->attachments->count() > 0)
                    <div class="mt-4 pt-3 border-t border-white/10">
                        <p class="text-xs font-bold text-slate-500 mb-2">Anexos:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($msg->attachments as $attachment)
                                <a href="{{ $attachment->url }}" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-cyan-400 text-sm transition">
                                    📎 {{ $attachment->file_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-sm text-slate-400">
                Ainda não há mensagens neste chamado.
            </div>
        @endforelse
    </div>

    {{-- Responder --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" class="space-y-3">
            @csrf

            {{-- ⚡ SELECT DE RESPOSTAS PRONTAS (NOVIDADE) --}}
            <div class="flex justify-between items-end">
                <label class="text-sm text-slate-300">Responder ao cliente</label>
                
                <select onchange="insertMacro(this)" 
                        class="text-xs rounded-lg border border-white/10 bg-slate-900 text-slate-400 focus:border-cyan-500 focus:ring-cyan-500 py-1 px-2 cursor-pointer">
                    <option value="">⚡ Respostas Rápidas...</option>
                    <option value="Olá! Recebemos seu chamado e estamos analisando. Em breve retornaremos.">1. Análise Inicial</option>
                    <option value="Você poderia nos enviar um print ou foto do erro para ajudarmos melhor?">2. Pedir Print</option>
                    <option value="Realizamos um ajuste no seu sistema. Poderia testar novamente?">3. Pedir Teste</option>
                    <option value="Como não houve retorno nos últimos dias, estamos encerrando este chamado. Caso precise, é só reabrir.">4. Encerramento por Inatividade</option>
                    <option value="Fico feliz que tenhamos resolvido! Qualquer dúvida, estamos à disposição.">5. Resolvido</option>
                </select>
            </div>

            <textarea id="replyMessage" name="message" rows="4"
                      class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                             focus:border-cyan-400/60 focus:ring-cyan-400/20"
                      placeholder="Escreva a resposta..." required>{{ old('message') }}</textarea>

            @error('message') <p class="text-sm text-red-300">{{ $message }}</p> @enderror

            <button type="submit"
                    class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                Enviar resposta
            </button>
        </form>
    </div>
</div>

{{-- SCRIPT PARA FUNCIONAR AS MACROS --}}
<script>
    function insertMacro(select) {
        const textarea = document.getElementById('replyMessage');
        if (select.value) {
            // Adiciona o texto (se já tiver algo escrito, pula duas linhas antes)
            textarea.value = textarea.value + (textarea.value ? '\n\n' : '') + select.value;
            // Reseta o select para o placeholder
            select.value = '';
            // Dá foco no textarea para continuar escrevendo
            textarea.focus();
        }
    }
</script>
@endsection