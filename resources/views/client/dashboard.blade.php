@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 text-white border border-white/10">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        🎫 Meus chamados
    </a>
@endsection

@section('title', 'Minha conta')

@section('content')
@php
    $user = auth()->user();
    
    // Importante: usar os Enums para garantir consistência
    $ticketsQuery = \App\Models\Ticket::query()->where('user_id', $user->id);

    // Contagens
    $countOpen = (clone $ticketsQuery)->whereIn('status', [
        \App\Enums\TicketStatus::NEW, 
        \App\Enums\TicketStatus::IN_PROGRESS, 
        \App\Enums\TicketStatus::WAITING_CLIENT
    ])->count();
    
    $countInProgress = (clone $ticketsQuery)->where('status', \App\Enums\TicketStatus::IN_PROGRESS)->count();
    
    $countResolved = (clone $ticketsQuery)->whereIn('status', [
        \App\Enums\TicketStatus::RESOLVED, 
        \App\Enums\TicketStatus::CLOSED
    ])->count();

    $recentTickets = (clone $ticketsQuery)->latest()->take(5)->get();

    // Definição de cores usando os valores do Enum (strings)
    $statusColors = [
        'new' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
        'in_progress' => 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30',
        'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
        'resolved' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
        'closed' => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
    ];
@endphp

<div class="rounded-3xl border border-white/10 bg-white/5 p-6 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <div class="text-sm text-slate-400">Bem-vindo,</div>
            <div class="text-2xl font-extrabold text-white">{{ $user->name }}</div>
            <div class="mt-1 text-sm text-slate-300">
                Abra um chamado e acompanhe tudo por aqui.
            </div>
        </div>

        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('client.tickets.index') }}"
               class="rounded-2xl bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/15 transition">
                Ver meus chamados
            </a>
            <a href="{{ route('client.tickets.create') }}"
               class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:opacity-95 transition">
                Abrir chamado
            </a>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Chamados em aberto</div>
        <div class="mt-2 text-3xl font-bold text-white">{{ $countOpen }}</div>
        <div class="mt-2 text-xs text-slate-400">Inclui novos, em andamento e aguardando.</div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Em atendimento</div>
        <div class="mt-2 text-3xl font-bold text-cyan-400">{{ $countInProgress }}</div>
        <div class="mt-2 text-xs text-slate-400">Chamados que estão sendo trabalhados.</div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Finalizados</div>
        <div class="mt-2 text-3xl font-bold text-emerald-400">{{ $countResolved }}</div>
        <div class="mt-2 text-xs text-slate-400">Resolvidos ou fechados.</div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rounded-2xl bg-white/5 border border-white/10 p-6">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <h2 class="text-lg font-semibold text-white">Últimos chamados</h2>
            <a href="{{ route('client.tickets.index') }}" class="text-sm text-slate-300 hover:text-white underline">
                Ver todos
            </a>
        </div>

        <div class="mt-4 space-y-3">
            @forelse($recentTickets as $ticket)
                <a href="{{ route('client.tickets.show', $ticket) }}"
                   class="block rounded-2xl border border-white/10 bg-slate-950/30 p-4 hover:bg-slate-950/40 transition group">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="font-semibold text-white group-hover:text-cyan-400 transition">{{ $ticket->subject }}</div>
                            <div class="mt-1 text-xs text-slate-400">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        {{-- Correção aqui: Acessar ->value para o array e ->label() para o texto --}}
                        <span class="text-xs rounded-full px-3 py-1 font-medium {{ $statusColors[$ticket->status->value] ?? 'bg-white/10 text-slate-200' }}">
                            {{ $ticket->status->label() }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="text-sm text-slate-300 py-4">
                    Você ainda não tem chamados. Bora abrir o primeiro 😉
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
        <h2 class="text-lg font-semibold text-white">Dicas rápidas</h2>
        <div class="mt-4 space-y-3 text-sm text-slate-300">
            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                Informe o modelo do seu PC/notebook e o que você já tentou.
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                Se for erro, mande a mensagem completa ou print.
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                Se for rede, diga se é Wi-Fi ou cabo, e o modelo do roteador.
            </div>
        </div>
    </div>
</div>
@endsection