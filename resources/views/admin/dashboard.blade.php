@extends('layouts.portal')

@section('menu')
    <a href="{{ route('admin.dashboard') }}"
   class="block rounded-xl px-4 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10' }}">
   📊 Dashboard
</a>


    <a href="{{ route('admin.tickets.index') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        🎫 Chamados
    </a>
@endsection

@section('title', 'Dashboard administrativo ')


@section('content')
@php
    $tickets = \App\Models\Ticket::query();

    $countNew = (clone $tickets)->where('status','new')->count();
    $countInProgress = (clone $tickets)->where('status','in_progress')->count();
    $countWaiting = (clone $tickets)->where('status','waiting_client')->count();
    $countResolved = (clone $tickets)->whereIn('status',['resolved','closed'])->count();

    $queue = (clone $tickets)->with('user')->latest()->take(8)->get();

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

<div class="rounded-3xl border border-white/10 bg-white/5 p-6 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <div class="text-sm text-slate-400">Resumo</div>
            <div class="text-2xl font-extrabold text-white">Fila de atendimento</div>
            <div class="mt-1 text-sm text-slate-300">
                Priorize os novos e acompanhe respostas pendentes.
            </div>
        </div>

        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('admin.tickets.index') }}"
               class="rounded-2xl bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/15 transition">
                Gerenciar chamados
            </a>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-4 gap-6 mb-6">
    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Novos</div>
        <div class="mt-2 text-3xl font-bold text-indigo-300">{{ $countNew }}</div>
        <div class="mt-2 text-xs text-slate-400">Chegaram e ainda não foram assumidos.</div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Em andamento</div>
        <div class="mt-2 text-3xl font-bold text-cyan-300">{{ $countInProgress }}</div>
        <div class="mt-2 text-xs text-slate-400">Você está trabalhando nisso.</div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Aguardando cliente</div>
        <div class="mt-2 text-3xl font-bold text-yellow-300">{{ $countWaiting }}</div>
        <div class="mt-2 text-xs text-slate-400">Você já respondeu e espera retorno.</div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
        <div class="text-sm text-slate-400">Finalizados</div>
        <div class="mt-2 text-3xl font-bold text-emerald-300">{{ $countResolved }}</div>
        <div class="mt-2 text-xs text-slate-400">Resolvidos ou fechados.</div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rounded-2xl bg-white/5 border border-white/10 p-6">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <h2 class="text-lg font-semibold text-white">Últimas atividades</h2>
            <a href="{{ route('admin.tickets.index') }}" class="text-sm text-slate-300 hover:text-white underline">
                Ver todos
            </a>
        </div>

        <div class="mt-4 space-y-3">
            @forelse($queue as $ticket)
                <a href="{{ route('admin.tickets.show', $ticket) }}"
                   class="block rounded-2xl border border-white/10 bg-slate-950/30 p-4 hover:bg-slate-950/40 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="font-semibold text-white">{{ $ticket->subject }}</div>
                            <div class="mt-1 text-xs text-slate-400">
                                {{ $ticket->user->name }} • {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <span class="text-xs rounded-full px-3 py-1 font-medium {{ $statusColors[$ticket->status] ?? 'bg-white/10 text-slate-200' }}">
                            {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_',' ', $ticket->status)) }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="text-sm text-slate-300">
                    Nenhum chamado por enquanto. Milagre? 😄
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
        <h2 class="text-lg font-semibold text-white">Atalhos</h2>

        <div class="mt-4 space-y-3">
            <a href="{{ route('admin.tickets.index') }}"
               class="block rounded-2xl border border-white/10 bg-slate-950/30 p-4 hover:bg-slate-950/40 transition">
                <div class="font-semibold text-white">Abrir fila de chamados</div>
                <div class="mt-1 text-sm text-slate-400">Filtrar, responder e atualizar status.</div>
            </a>

            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                <div class="font-semibold text-white">Sugestão</div>
                <div class="mt-1 text-sm text-slate-400">
                    Responde e deixa em “Aguardando cliente” pra manter a fila organizada.
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                <div class="font-semibold text-white">Padrão de resposta</div>
                <div class="mt-1 text-sm text-slate-400">
                    Pergunte: quando começou, se houve atualização, e se aparece mensagem de erro.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
