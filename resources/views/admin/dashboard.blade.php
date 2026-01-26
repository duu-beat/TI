@extends('layouts.portal')

@section('menu')
    <a href="{{ route('admin.dashboard') }}"
       class="block rounded-xl px-4 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-medium shadow-lg' : 'text-slate-300 hover:bg-white/10' }}">
       📊 Dashboard
    </a>

    <a href="{{ route('admin.tickets.index') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        🎫 Chamados
    </a>
@endsection

@section('title', 'Dashboard administrativo')

@section('content')
@php
    $tickets = \App\Models\Ticket::query();

    $countNew = (clone $tickets)->where('status', \App\Enums\TicketStatus::NEW)->count();
    $countInProgress = (clone $tickets)->where('status', \App\Enums\TicketStatus::IN_PROGRESS)->count();
    $countWaiting = (clone $tickets)->where('status', \App\Enums\TicketStatus::WAITING_CLIENT)->count();
    $countResolved = (clone $tickets)->whereIn('status', [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED])->count();

    $dailyTickets = \App\Models\Ticket::selectRaw('DATE(created_at) as date, count(*) as total')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $chartLabels = [];
    $chartValues = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $prettyDate = now()->subDays($i)->format('d/m');
        $chartLabels[] = $prettyDate;
        $chartValues[] = $dailyTickets->where('date', $date)->first()->total ?? 0;
    }

    $queue = (clone $tickets)->with('user')->latest()->take(6)->get();
    
    $statusColors = [
        'new' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
        'in_progress' => 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30',
        'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
        'resolved' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
        'closed' => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
    ];
@endphp

{{-- 💀 WRAPPER ALPINE PARA O LOADING --}}
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">

    {{-- ========================
         1. ESTADO DE LOADING (Skeleton)
         ======================== --}}
    <div x-show="!loaded" class="space-y-6 animate-pulse">
        {{-- Banner --}}
        <x-skeleton class="h-32 rounded-3xl" />

        {{-- Stats Grid (4 cards) --}}
        <div class="grid lg:grid-cols-4 gap-6">
            <x-skeleton class="h-32 rounded-2xl" />
            <x-skeleton class="h-32 rounded-2xl" />
            <x-skeleton class="h-32 rounded-2xl" />
            <x-skeleton class="h-32 rounded-2xl" />
        </div>

        {{-- Charts --}}
        <div class="grid lg:grid-cols-3 gap-6">
            <x-skeleton class="h-64 rounded-2xl" />
            <x-skeleton class="lg:col-span-2 h-64 rounded-2xl" />
        </div>

        {{-- List --}}
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                 <x-skeleton class="h-8 w-40 mb-2" />
                 <x-skeleton type="card" />
                 <x-skeleton type="card" />
                 <x-skeleton type="card" />
            </div>
            <div>
                 <x-skeleton class="h-48 rounded-2xl" />
            </div>
        </div>
    </div>


    {{-- ========================
         2. CONTEÚDO REAL
         ======================== --}}
    <div x-show="loaded" style="display: none;"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Banner Summary --}}
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

        {{-- CARDS DE STATUS --}}
        <div class="grid lg:grid-cols-4 gap-6 mb-6">
            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-indigo-500/30 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Novos</div>
                <div class="mt-2 text-3xl font-bold text-indigo-300">{{ $countNew }}</div>
                <div class="mt-2 text-xs text-slate-400">Chegaram e ainda não foram assumidos.</div>
            </div>

            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-cyan-500/30 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Em andamento</div>
                <div class="mt-2 text-3xl font-bold text-cyan-300">{{ $countInProgress }}</div>
                <div class="mt-2 text-xs text-slate-400">Você está trabalhando nisso.</div>
            </div>

            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-yellow-500/30 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Aguardando cliente</div>
                <div class="mt-2 text-3xl font-bold text-yellow-300">{{ $countWaiting }}</div>
                <div class="mt-2 text-xs text-slate-400">Você já respondeu e espera retorno.</div>
            </div>

            <div class="rounded-2xl bg-white/5 border border-white/10 p-5 hover:border-emerald-500/30 transition duration-300 hover:-translate-y-1">
                <div class="text-sm text-slate-400">Finalizados</div>
                <div class="mt-2 text-3xl font-bold text-emerald-300">{{ $countResolved }}</div>
                <div class="mt-2 text-xs text-slate-400">Resolvidos ou fechados.</div>
            </div>
        </div>

        {{-- GRÁFICOS (Chart.js) --}}
        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            {{-- Gráfico 1: Status (Rosca) --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">Distribuição de Status</h3>
                <div class="relative h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Gráfico 2: Volume Semanal (Barras) --}}
            <div class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-sm font-semibold text-slate-300 mb-4">Chamados nos últimos 7 dias</h3>
                <div class="relative h-64">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- LISTA E ATALHOS --}}
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
                           class="block rounded-2xl border border-white/10 bg-slate-950/30 p-4 hover:bg-slate-950/40 transition group hover:border-white/20">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-white group-hover:text-cyan-400 transition">{{ $ticket->subject }}</div>
                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ $ticket->user->name }} • {{ $ticket->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                <span class="text-xs rounded-full px-3 py-1 font-medium {{ $statusColors[$ticket->status->value] ?? 'bg-white/10 text-slate-200' }}">
                                    {{ $ticket->status->label() }}
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
                       class="block rounded-2xl border border-white/10 bg-slate-950/30 p-4 hover:bg-slate-950/40 transition hover:border-white/20">
                        <div class="font-semibold text-white">Abrir fila de chamados</div>
                        <div class="mt-1 text-sm text-slate-400">Filtrar, responder e atualizar status.</div>
                    </a>

                    <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                        <div class="font-semibold text-white">Sugestão</div>
                        <div class="mt-1 text-sm text-slate-400">
                            Responde e deixa em “Aguardando cliente” pra manter a fila organizada.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT DOS GRÁFICOS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#334155';

    // Gráfico de Status
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Novos', 'Em Andamento', 'Resolvidos'],
            datasets: [{
                // Aqui somamos "In Progress" + "Waiting" para simplificar o gráfico visualmente
                data: [{{ $countNew }}, {{ $countInProgress + $countWaiting }}, {{ $countResolved }}],
                backgroundColor: ['#818cf8', '#22d3ee', '#34d399'], 
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Gráfico Semanal
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Chamados',
                data: @json($chartValues),
                backgroundColor: '#38bdf8',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: '#1e293b' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection