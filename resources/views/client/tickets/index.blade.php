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

@section('title', 'Meus chamados')

@section('actions')
    <a href="{{ route('client.tickets.create') }}"
       class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:opacity-95 transition">
        Novo chamado
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
    @forelse($tickets as $ticket)
        <a href="{{ route('client.tickets.show', $ticket) }}"
           class="block rounded-2xl border border-white/10 bg-white/5 p-5 hover:bg-white/10 transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-white font-semibold">
                        {{ $ticket->subject }}
                    </div>
                    <div class="mt-1 text-sm text-slate-400">
                        Criado em {{ $ticket->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <span class="text-xs rounded-full px-3 py-1 font-medium
                    {{ $statusColors[$ticket->status] ?? 'bg-white/10 text-slate-200' }}">
                    {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_',' ', $ticket->status)) }}
                </span>
            </div>
        </a>
    @empty
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-slate-300">
            Você ainda não abriu nenhum chamado.
        </div>
    @endforelse

    @if(method_exists($tickets, 'links'))
        <div class="pt-4">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection
