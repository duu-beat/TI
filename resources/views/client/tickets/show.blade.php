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

@section('title')
    {{ $ticket->subject }}
@endsection

@section('actions')
    <a href="{{ route('client.tickets.index') }}"
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
        'waiting_client' => 'Aguardando você',
        'resolved' => 'Resolvido',
        'closed' => 'Fechado',
    ];
@endphp

<div class="space-y-4">
    {{-- Top info --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <div class="text-sm text-slate-400">Status</div>
                <div class="mt-2">
                    <span class="text-xs rounded-full px-3 py-1 font-medium
                        {{ $statusColors[$ticket->status] ?? 'bg-white/10 text-slate-200' }}">
                        {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_',' ', $ticket->status)) }}
                    </span>
                </div>
            </div>

            <div class="text-right">
                <div class="text-sm text-slate-400">Criado em</div>
                <div class="mt-1 text-sm text-slate-200">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Descrição --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
        <div class="text-sm text-slate-300">Descrição inicial</div>
        <p class="mt-2 text-slate-200 whitespace-pre-line">{{ $ticket->description }}</p>
    </div>

    {{-- Mensagens --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 space-y-3">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="text-sm text-slate-300">Mensagens</div>
        </div>

        @forelse($ticket->messages as $msg)
            @php
                $isMe = $msg->user_id === auth()->id();
            @endphp

            <div class="rounded-2xl border border-white/10 p-4
                {{ $isMe ? 'bg-indigo-500/10' : 'bg-slate-950/40' }}">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm font-semibold {{ $isMe ? 'text-indigo-200' : 'text-white' }}">
                        {{ $isMe ? 'Você' : $msg->user->name }}
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
        <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" class="space-y-3">
            @csrf

            <label class="text-sm text-slate-300">Enviar mensagem</label>
            <textarea name="message" rows="4"
                      class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                             focus:border-cyan-400/60 focus:ring-cyan-400/20"
                      placeholder="Escreva sua resposta..." required></textarea>

            @error('message')
                <p class="text-sm text-red-300">{{ $message }}</p>
            @enderror

            <button class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                Enviar resposta
            </button>
        </form>
    </div>
</div>
@endsection
