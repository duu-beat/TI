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

    <a href="{{ route('profile.show') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        👤 Meu Perfil
    </a>
@endsection

@section('title')
    Gerenciar Chamado #{{ $ticket->id }}
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

    {{-- INFO DO TICKET --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <div class="text-sm text-slate-400">Assunto</div>
                <div class="mt-1 text-white font-semibold text-xl">{{ $ticket->subject }}</div>

                <div class="mt-3 text-sm text-slate-400">
                    Aberto por <span class="text-slate-200 font-semibold">{{ $ticket->user->name }}</span>
                    <span class="text-slate-500">•</span>
                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                </div>

                <div class="mt-4 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400">Status:</span>
                    <span class="text-xs rounded-full px-3 py-1 font-medium
                        {{ $statusColors[$ticket->status->value] ?? 'bg-white/10 text-slate-200' }}">
                        {{ $ticket->status->label() }}
                    </span>

                    <span class="text-xs text-slate-500">•</span>
                    <span class="text-xs text-slate-400">{{ $ticket->user->email }}</span>
                </div>
            </div>

            {{-- Form de Alterar Status --}}
            <form action="{{ route('admin.tickets.status', $ticket) }}"
                  method="POST"
                  class="flex gap-3 flex-wrap items-end">
                @csrf

                <div>
                    <label class="text-sm text-slate-300">Atualizar status</label>
                    <select name="status"
                            onchange="this.form.submit()"
                            class="mt-2 rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100
                                   focus:border-cyan-400/60 focus:ring-cyan-400/20">
                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($ticket->status === $status)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <noscript>
                    <button type="submit"
                            class="rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition">
                        Salvar
                    </button>
                </noscript>
            </form>
        </div>

        {{-- Descrição do Cliente --}}
        <div class="mt-6 border-t border-white/10 pt-4">
            <div class="text-sm text-slate-300">Descrição do cliente</div>
            <p class="mt-2 text-slate-200 whitespace-pre-line">{{ $ticket->description }}</p>

            {{-- Anexo Original (se houver) --}}
            @if($ticket->messages->first() && $ticket->messages->first()->attachments->count() > 0)
                <div class="mt-4 pt-3 border-t border-white/10">
                    <p class="text-xs font-bold text-slate-500 mb-2">Anexos:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ticket->messages->first()->attachments as $att)
                            <a href="{{ Storage::url($att->file_path) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-cyan-400 text-sm transition">
                                📎 {{ $att->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ÁREA DE CHAT (Admin) --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <div class="text-sm text-slate-300 mb-3">Mensagens</div>

        <div class="space-y-3 max-h-[520px] overflow-y-auto pr-1"
             x-data="{ init() { this.$el.scrollTop = this.$el.scrollHeight } }">

            @forelse($ticket->messages as $message)
                @php
                    $isAdmin = optional($message->user)->role === 'admin';
                    // se preferir pelo usuário logado:
                    // $isAdmin = $message->user_id === auth()->id();
                @endphp

                <div class="rounded-2xl border border-white/10 p-4 {{ $isAdmin ? 'bg-indigo-500/10 border-indigo-500/20' : 'bg-slate-950/40' }}">
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-sm font-semibold {{ $isAdmin ? 'text-indigo-200' : 'text-white' }}">
                            {{ $isAdmin ? 'Admin' : $message->user->name }}
                        </div>
                        <div class="text-xs text-slate-400">{{ $message->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <p class="mt-2 text-slate-200 whitespace-pre-line">{{ $message->message }}</p>

                    @if($message->attachments->count() > 0)
                        <div class="mt-4 pt-3 border-t border-white/10">
                            <p class="text-xs font-bold text-slate-500 mb-2">Anexos:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($message->attachments as $attachment)
                                    <a href="{{ Storage::url($attachment->file_path) }}"
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
    </div>

    {{-- FORM DE RESPOSTA (Admin) --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <form action="{{ route('admin.tickets.reply', $ticket) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-3">
            @csrf

            <label class="text-sm text-slate-300">Responder ao cliente</label>

            <textarea id="replyMessage"
                      name="message"
                      rows="4"
                      class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                             focus:border-cyan-400/60 focus:ring-cyan-400/20"
                      placeholder="Escreva a resposta..." required>{{ old('message') }}</textarea>

            @error('message') <p class="text-sm text-red-300">{{ $message }}</p> @enderror

            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div x-data="{ files: [] }" class="flex flex-col gap-2">
                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-slate-200 text-sm transition">
                        📎 Anexar Arquivos
                        <input type="file" name="attachments[]" multiple class="hidden"
                               @change="files = Array.from($el.files)">
                    </label>

                    <div class="text-xs text-slate-400 space-y-1">
                        <template x-for="file in files" :key="file.name">
                            <div x-text="'📄 ' + file.name"></div>
                        </template>
                    </div>
                </div>

                <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                    Enviar resposta
                </button>
            </div>
        </form>
    </div>

</div>
@endsection