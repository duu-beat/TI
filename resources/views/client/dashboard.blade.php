@extends('layouts.portal')

@section('menu')
    {{-- MENU LATERAL --}}
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 text-white border border-white/10 shadow-lg shadow-indigo-500/10 mb-1">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition mb-1">
        🎫 Meus chamados
    </a>

    {{-- Link Perfil (Mobile/Menu) --}}
    <a href="{{ route('profile.show') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        👤 Minha Conta
    </a>
@endsection

@section('title', 'Minha conta')

@section('content')
@php
    $user = auth()->user();
    $ticketsQuery = \App\Models\Ticket::query()->where('user_id', $user->id);

    // Contagens
    $countOpen = (clone $ticketsQuery)->whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS, \App\Enums\TicketStatus::WAITING_CLIENT])->count();
    $countInProgress = (clone $ticketsQuery)->where('status', \App\Enums\TicketStatus::IN_PROGRESS)->count();
    $countResolved = (clone $ticketsQuery)->whereIn('status', [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED])->count();

    $recentTickets = (clone $ticketsQuery)->latest()->take(5)->get();

    $statusColors = [
        'new' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
        'in_progress' => 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30',
        'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
        'resolved' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
        'closed' => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
    ];
@endphp

<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">

    {{-- LOADING SKELETON --}}
    <div x-show="!loaded" class="space-y-6 animate-pulse">
        <x-skeleton class="h-40 rounded-3xl" />
        <div class="grid lg:grid-cols-3 gap-6">
            <x-skeleton class="h-32 rounded-2xl" />
            <x-skeleton class="h-32 rounded-2xl" />
            <x-skeleton class="h-32 rounded-2xl" />
        </div>
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <x-skeleton class="h-8 w-40 mb-4" />
                <x-skeleton type="card" />
                <x-skeleton type="card" />
            </div>
            <div>
                <x-skeleton class="h-64 rounded-2xl" />
            </div>
        </div>
    </div>

    {{-- CONTEÚDO REAL --}}
    <div x-show="loaded" style="display: none;"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Top Bar de Navegação Rápida (NOVO) --}}
        <div class="flex items-center justify-between mb-6">
            <div class="text-sm text-slate-400">Portal do Cliente</div>
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm font-medium text-cyan-400 hover:text-cyan-300 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar ao Site
            </a>
        </div>

        {{-- Banner Welcome --}}
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 mb-6 backdrop-blur-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110 duration-700">
                <img src="{{ asset('images/logosuporteTI.png') }}" class="w-32 h-32 grayscale" alt="">
            </div>
            
            <div class="relative z-10 flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <div class="text-sm text-slate-400">Bem-vindo,</div>
                    <div class="text-2xl font-extrabold text-white">{{ $user->name }}</div>
                    <div class="mt-1 text-sm text-slate-300">
                        Abra um chamado e acompanhe tudo por aqui.
                    </div>
                </div>

                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('client.tickets.create') }}"
                       class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:scale-105 transition-all duration-300">
                        + Abrir chamado
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-white/20 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Chamados em aberto</div>
                <div class="mt-2 text-3xl font-bold text-white">{{ $countOpen }}</div>
            </div>

            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-cyan-500/30 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Em atendimento</div>
                <div class="mt-2 text-3xl font-bold text-cyan-400 drop-shadow-[0_0_10px_rgba(34,211,238,0.5)]">{{ $countInProgress }}</div>
            </div>

            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-emerald-500/30 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Finalizados</div>
                <div class="mt-2 text-3xl font-bold text-emerald-400 drop-shadow-[0_0_10px_rgba(52,211,153,0.5)]">{{ $countResolved }}</div>
            </div>
        </div>

        {{-- Lists & Sidebar --}}
        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Coluna Esquerda: Tickets --}}
            <div class="lg:col-span-2 rounded-2xl bg-white/5 border border-white/10 p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <h2 class="text-lg font-semibold text-white">Últimos chamados</h2>
                    <a href="{{ route('client.tickets.index') }}" class="text-sm text-slate-300 hover:text-white underline">Ver todos</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentTickets as $ticket)
                        <a href="{{ route('client.tickets.show', $ticket) }}"
                           class="block rounded-2xl border border-white/10 bg-slate-950/30 p-4 hover:bg-slate-950/40 transition group hover:border-white/20">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-white group-hover:text-cyan-400 transition">{{ $ticket->subject }}</div>
                                    <div class="mt-1 text-xs text-slate-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <span class="text-xs rounded-full px-3 py-1 font-medium {{ $statusColors[$ticket->status->value] ?? 'bg-white/10 text-slate-200' }}">
                                    {{ $ticket->status->label() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 text-sm text-slate-300">
                            Nenhum chamado recente.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Coluna Direita: Ações Rápidas & Dicas --}}
            <div class="space-y-6">
                
                {{-- CARD PERFIL (NOVO) --}}
                <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-slate-800/50 to-slate-900/50 p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Ações Rápidas</h2>
                    <div class="space-y-3">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition group">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:text-white transition">⚙️</div>
                            <div class="text-sm text-slate-300 group-hover:text-white">Editar Perfil / Senha</div>
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/10 transition group text-left">
                                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-400 group-hover:text-white transition">🚪</div>
                                <div class="text-sm text-red-300 group-hover:text-red-200">Sair da conta</div>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Dicas --}}
                <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
                    <h2 class="text-lg font-semibold text-white mb-3">💡 Dicas</h2>
                    <div class="text-sm text-slate-400 space-y-2">
                        <p>• Tire prints do erro.</p>
                        <p>• Descreva o passo a passo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection