<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            {{ __('Dashboard Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. ALERTA DE PRIORIDADE (Se houver) --}}
            @if(isset($priorityStats) && $priorityStats['high'] > 0)
                <div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="text-2xl">🚨</div>
                        <div>
                            <h3 class="text-lg font-bold text-red-400">Atenção Necessária</h3>
                            <p class="text-red-200/80 text-sm">Existem <strong>{{ $priorityStats['high'] }}</strong> chamados de Alta Prioridade.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tickets.index', ['status' => 'new']) }}" class="px-4 py-2 rounded-lg bg-red-500 text-white font-bold text-sm hover:bg-red-600">
                        Ver Fila
                    </a>
                </div>
            @endif

            {{-- 2. STATUS RÁPIDO (Cards) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Total --}}
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5">
                    <div class="text-xs text-slate-500 uppercase font-bold mb-1">Total</div>
                    <div class="text-3xl font-bold text-white">{{ $stats->total ?? 0 }}</div>
                </div>
                
                {{-- Abertos --}}
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5">
                    <div class="text-xs text-emerald-500 uppercase font-bold mb-1">Abertos</div>
                    <div class="text-3xl font-bold text-emerald-400">{{ $stats->open ?? 0 }}</div>
                </div>

                {{-- Aguardando --}}
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5">
                    <div class="text-xs text-yellow-500 uppercase font-bold mb-1">Aguardando</div>
                    <div class="text-3xl font-bold text-yellow-400">{{ $stats->on_hold ?? 0 }}</div>
                </div>

                {{-- Resolvidos --}}
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5">
                    <div class="text-xs text-purple-500 uppercase font-bold mb-1">Resolvidos</div>
                    <div class="text-3xl font-bold text-purple-400">{{ $stats->resolved ?? 0 }}</div>
                </div>
            </div>

            {{-- 3. GRÁFICO E LISTA --}}
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- Gráfico --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Volume Semanal</h3>
                        @if(isset($todayCount))
                            <span class="text-xs bg-blue-500/20 text-blue-400 px-2 py-1 rounded">Hoje: {{ $todayCount }}</span>
                        @endif
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-900 border border-white/5 h-80">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>

                {{-- Lista Recente --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-white">Recentes</h3>
                    <div class="space-y-3">
                        @if(isset($latestTickets) && count($latestTickets) > 0)
                            @foreach($latestTickets as $ticket)
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="block p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-xs font-mono text-slate-500">#{{ $ticket->id }}</span>
                                        <x-ticket-status :status="$ticket->status" />
                                    </div>
                                    <div class="text-sm font-bold text-white truncate">{{ $ticket->subject }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $ticket->user->name ?? 'Usuário' }}</div>
                                </a>
                            @endforeach
                        @else
                            <div class="text-slate-500 text-sm">Sem chamados recentes.</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPT DIRETO (Sem @push, para garantir que carregue) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('weeklyChart');
            
            // Dados vindos do PHP de forma segura
            const labels = @json($chartLabels ?? []);
            const values = @json($chartValues ?? []);

            console.log('Dados do Gráfico:', labels, values); // Para debug no F12

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Chamados',
                            data: values,
                            backgroundColor: '#3b82f6',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#334155' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>