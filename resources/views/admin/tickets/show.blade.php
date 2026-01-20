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
        'new' => 'bg-indigo-500/20 text-indigo-300',
        'in_progress' => 'bg-cyan-500/20 text-cyan-300',
        'waiting_client' => 'bg-yellow-500/20 text-yellow-300',
        'resolved' => 'bg-emerald-500/20 text-emerald-300',
        'closed' => 'bg-slate-500/20 text-slate-300',
    ];

    $statusLabels = [
        'new' => 'Novo',
        'in_progress' => 'Em andamento',
        'waiting_client' => 'Aguardando cliente',
        'resolved' => 'Resolvido',
        'closed' => 'Fechado',
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
                        {{ $statusColors[$ticket->status] ?? 'bg-white/10 text-slate-200' }}">
                        {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_',' ', $ticket->status)) }}
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
                        @foreach(['new','in_progress','waiting_client','resolved','closed'] as $st)
                            <option value="{{ $st }}" @selected($ticket->status === $st)>
                                {{ $statusLabels[$st] ?? $st }}
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

            <div class="rounded-2xl border border-white/10 p-4 {{ $isAdmin ? 'bg-indigo-500/10' : 'bg-slate-950/40' }}">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm font-semibold {{ $isAdmin ? 'text-indigo-200' : 'text-white' }}">
                        {{ $isAdmin ? 'Admin' : $msg->user->name }}
                    </div>
                    <div class="text-xs text-slate-400">{{ $msg->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <p class="mt-2 text-slate-200 whitespace-pre-line">{{ $msg->message }}</p>
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

            <label class="text-sm text-slate-300">Responder ao cliente</label>

            <textarea name="message" rows="4"
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
@endsection
