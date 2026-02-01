<x-app-layout>
    <x-slot name="header">
        Dashboard Administrativo
    </x-slot>

    <div class="space-y-8">
        
        {{-- 🚨 ALERTA DE PRIORIDADE --}}
        @if($priorityStats['high'] > 0)
            <div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-4 flex items-center gap-4 animate-pulse">
                <div class="bg-red-500 text-white h-10 w-10 rounded-full flex items-center justify-center font-bold">!</div>
                <div>
                    <h3 class="text-red-400 font-bold">Atenção Necessária</h3>
                    <p class="text-red-200/70 text-sm">Existem <strong>{{ $priorityStats['high'] }}</strong> chamados de Alta Prioridade em aberto.</p>
                </div>
                <a href="{{ route('admin.tickets.index', ['status' => 'new']) }}" class="ml-auto text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition">Ver Fila</a>
            </div>
        @endif

        {{-- KPIS PRINCIPAIS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="rounded-2xl border border-indigo-500/20 bg-gradient-to-br from-indigo-500/10 to-transparent p-6">
                <div class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-2">Novos Tickets</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold text-white">{{ $stats['new'] }}</div>
                    <div class="h-10 w-10 rounded-lg bg-indigo-500/20 flex items-center justify-center text-xl">🔥</div>
                </div>
            </div>

            <div class="rounded-2xl border border-cyan-500/20 bg-gradient-to-br from-cyan-500/10 to-transparent p-6">
                <div class="text-cyan-300 text-xs font-bold uppercase tracking-wider mb-2">Em Atendimento</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold text-white">{{ $stats['in_progress'] }}</div>
                    <div class="h-10 w-10 rounded-lg bg-cyan-500/20 flex items-center justify-center text-xl">⚙️</div>
                </div>
            </div>

            <div class="rounded-2xl border border-emerald-500/20 bg-gradient-to-br from-emerald-500/10 to-transparent p-6">
                <div class="text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">Resolvidos</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold text-white">{{ $stats['resolved'] }}</div>
                    <div class="h-10 w-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-xl">✅</div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Total Geral</div>
                <div class="flex items-center justify-between">
                    <div class="text-3xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center text-xl">📊</div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- GRÁFICO SEMANAL --}}
            <div class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-sm font-bold text-white mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-400"></span> Volume Semanal
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            {{-- LISTA RÁPIDA (Recentes) --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 flex flex-col">
                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span> Entrada Recente
                </h3>
                
                <div class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                    @foreach($latestTickets as $ticket)
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="block p-3 rounded-xl bg-slate-900/50 border border-white/5 hover:border-indigo-500/30 transition group">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-bold text-slate-500">#{{ $ticket->id }}</span>
                                <span class="text-[10px] {{ $ticket->created_at->diffInHours() < 24 ? 'text-green-400' : 'text-slate-500' }}">
                                    {{ $ticket->created_at->diffForHumans(short: true) }}
                                </span>
                            </div>
                            <div class="text-sm font-medium text-slate-200 group-hover:text-white truncate">
                                {{ $ticket->subject }}
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-5 w-5 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-white">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs text-slate-400 truncate max-w-[80px]">{{ $ticket->user->name }}</span>
                                </div>
                                {{-- ✅ Componente de Status --}}
                                <x-ticket-status :status="$ticket->status" />
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 text-center">
                    <a href="{{ route('admin.tickets.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300">Ver todos os chamados &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.color = '#64748b';
        Chart.defaults.borderColor = '#334155';
        
        new Chart(document.getElementById('weeklyChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Chamados',
                    data: @json($chartValues),
                    backgroundColor: '#22d3ee',
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#1e293b' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>