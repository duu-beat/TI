<x-app-layout>
    <x-slot name="header">
        {{-- Adicionado 'gap-4' para criar espaço e evitar que colem --}}
        <div class="flex items-center justify-between gap-4">
            
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Painel de Controle
            </h2>

            {{-- Adicionado 'shrink-0' para o horário não amassar --}}
            <div class="shrink-0 text-xs text-slate-400 bg-slate-800/50 px-3 py-1 rounded-full border border-white/5">
                Atualizado: {{ now()->format('H:i') }}
            </div>
            
        </div>
    </x-slot>

    {{-- WRAPPER COM ALPINE PARA O LOADING --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="py-8">
        
        {{-- 💀 SKELETON LOADER (Simula o Dashboard) --}}
        <div x-show="!loaded" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 animate-pulse">
            
            {{-- Skeleton Alerta --}}
            <div class="h-24 w-full bg-white/5 rounded-2xl border border-white/5"></div>

            {{-- Skeleton Grid de Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div class="h-32 bg-white/5 rounded-2xl border border-white/5"></div>
                <div class="h-32 bg-white/5 rounded-2xl border border-white/5"></div>
                <div class="h-32 bg-white/5 rounded-2xl border border-white/5"></div>
                <div class="h-32 bg-white/5 rounded-2xl border border-white/5"></div>
            </div>

            {{-- 3. ALERTA DE TICKETS NÃO ATRIBUÍDOS --}}
            @if(isset($unassignedCount) && $unassignedCount > 0)
                <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-amber-500/20 text-amber-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white">{{ $unassignedCount }} {{ $unassignedCount === 1 ? 'Chamado' : 'Chamados' }} Sem Responsável</h3>
                                <p class="text-sm text-amber-200/80">Atribua agentes para melhorar o tempo de resposta</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.tickets.index', ['assigned_to' => 'unassigned']) }}" 
                           class="px-6 py-2 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm transition">
                            Ver Chamados
                        </a>
                    </div>
                </div>
            @endif

            {{-- 4. RANKING DE AGENTES --}}
            @if(isset($agentStats) && count($agentStats) > 0)
                <div class="bg-slate-900/50 border border-white/10 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            Top Agentes
                        </h3>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($agentStats as $index => $agent)
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-800/50 border border-white/5 hover:border-indigo-500/30 transition">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-500/20 text-yellow-400' : ($index === 1 ? 'bg-slate-400/20 text-slate-300' : 'bg-amber-700/20 text-amber-500') }} font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-white text-sm">{{ $agent->name }}</div>
                                    <div class="text-xs text-slate-400">
                                        {{ $agent->resolved_count }} resolvidos de {{ $agent->assigned_tickets_count }} atribuídos
                                        @if($agent->avg_rating)
                                            • ⭐ {{ number_format($agent->avg_rating, 1) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-emerald-400">{{ $agent->resolved_count > 0 ? round(($agent->resolved_count / $agent->assigned_tickets_count) * 100) : 0 }}%</div>
                                    <div class="text-[10px] text-slate-500 uppercase">Taxa</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 5. GRID: GRÁFICO + LISTA --}}
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 h-96 bg-white/5 rounded-3xl border border-white/5"></div>
                <div class="space-y-4">
                    <div class="h-8 w-1/2 bg-white/5 rounded"></div>
                    <div class="h-24 bg-white/5 rounded-2xl border border-white/5"></div>
                    <div class="h-24 bg-white/5 rounded-2xl border border-white/5"></div>
                    <div class="h-24 bg-white/5 rounded-2xl border border-white/5"></div>
                </div>
            </div>
        </div>

        {{-- ✅ CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;" 
             class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- 1. 🚨 ALERTA DE PRIORIDADE (Efeito de Crise) --}}
            @if(isset($priorityStats) && $priorityStats['high'] > 0)
                <div class="relative overflow-hidden rounded-2xl bg-red-500/10 border border-red-500/30 p-1 shadow-[0_0_30px_rgba(239,68,68,0.2)] animate-pulse-slow">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-600/10 to-transparent pointer-events-none"></div>
                    
                    <div class="relative flex flex-col md:flex-row items-center justify-between gap-6 p-6 bg-slate-950/40 backdrop-blur-sm rounded-xl">
                        <div class="flex items-center gap-5">
                            <div class="relative flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-red-500/20 text-3xl shadow-inner border border-red-500/20">
                                🔥
                                <span class="absolute top-0 right-0 -mr-1 -mt-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white tracking-wide">Ação Imediata Necessária</h3>
                                <p class="text-red-200/80 text-sm mt-1">
                                    Existem <strong class="text-red-400 text-lg border-b border-red-500/50">{{ $priorityStats['high'] }}</strong> chamados marcados como <strong>Alta Prioridade</strong> na fila.
                                </p>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.tickets.index', ['status' => 'new']) }}" 
                           class="group whitespace-nowrap px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold transition-all shadow-lg shadow-red-900/40 hover:shadow-red-600/40 hover:-translate-y-0.5 flex items-center gap-2">
                            <span>Resolver Agora</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endif

            {{-- 2. 📊 HUD DE ESTATÍSTICAS (Grid Pro) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                
                {{-- Card: Total --}}
                <div class="relative overflow-hidden rounded-2xl bg-slate-900/60 border border-white/5 p-5 group hover:border-indigo-500/30 transition-all duration-300">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Geral</div>
                            <div class="text-3xl font-black text-white mt-1 group-hover:scale-105 transition-transform origin-left">{{ $stats->total ?? 0 }}</div>
                        </div>
                        <div class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                    </div>
                    {{-- Barra de Progresso Decorativa --}}
                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                {{-- Card: Abertos --}}
                <div class="relative overflow-hidden rounded-2xl bg-slate-900/60 border border-white/5 p-5 group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Em Aberto</div>
                            <div class="text-3xl font-black text-white mt-1 group-hover:scale-105 transition-transform origin-left">{{ $stats->open ?? 0 }}</div>
                        </div>
                        <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    @php 
                        $openPercent = ($stats->total > 0) ? ($stats->open / $stats->total) * 100 : 0;
                    @endphp
                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $openPercent }}%"></div>
                    </div>
                </div>

                {{-- Card: Aguardando --}}
                <div class="relative overflow-hidden rounded-2xl bg-slate-900/60 border border-white/5 p-5 group hover:border-yellow-500/30 transition-all duration-300">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-yellow-500/10 rounded-full blur-2xl group-hover:bg-yellow-500/20 transition"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="text-xs font-bold text-yellow-400 uppercase tracking-widest">Aguardando</div>
                            <div class="text-3xl font-black text-white mt-1 group-hover:scale-105 transition-transform origin-left">{{ $stats->on_hold ?? 0 }}</div>
                        </div>
                        <div class="p-2 rounded-lg bg-yellow-500/10 text-yellow-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    @php 
                        $holdPercent = ($stats->total > 0) ? ($stats->on_hold / $stats->total) * 100 : 0;
                    @endphp
                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ $holdPercent }}%"></div>
                    </div>
                </div>

                {{-- Card: Resolvidos --}}
                <div class="relative overflow-hidden rounded-2xl bg-slate-900/60 border border-white/5 p-5 group hover:border-purple-500/30 transition-all duration-300">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="text-xs font-bold text-purple-400 uppercase tracking-widest">Resolvidos</div>
                            <div class="text-3xl font-black text-white mt-1 group-hover:scale-105 transition-transform origin-left">{{ $stats->resolved ?? 0 }}</div>
                        </div>
                        <div class="p-2 rounded-lg bg-purple-500/10 text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    @php 
                        $resPercent = ($stats->total > 0) ? ($stats->resolved / $stats->total) * 100 : 0;
                    @endphp
                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $resPercent }}%"></div>
                    </div>
                </div>
            </div>

            {{-- 3. ÁREA PRINCIPAL (Gráfico e Lista) --}}
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- GRÁFICO (Painel de Vidro) --}}
                <div class="lg:col-span-2 flex flex-col gap-4">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            Volume Semanal
                        </h3>
                        @if(isset($todayCount))
                            <span class="text-xs font-bold bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full border border-indigo-500/20">
                                Hoje: {{ $todayCount }} novos
                            </span>
                        @endif
                    </div>
                    
                    <div class="relative w-full p-4 sm:p-6 rounded-3xl bg-slate-900/80 border border-white/5 shadow-xl">
                        <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent rounded-3xl pointer-events-none"></div>
                        <div class="h-80 w-full relative z-10">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- LISTA RECENTE (Feed de Atividade) --}}
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Últimos Chamados
                        </h3>
                        <a href="{{ route('admin.tickets.index') }}" class="text-xs font-medium text-slate-400 hover:text-white transition">Ver todos &rarr;</a>
                    </div>

                    <div class="space-y-3">
                        @if(isset($latestTickets) && count($latestTickets) > 0)
                            @foreach($latestTickets as $ticket)
                                <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                   class="group block p-4 rounded-2xl bg-slate-900/60 border border-white/5 hover:bg-slate-800 hover:border-indigo-500/30 transition-all duration-300 hover:shadow-lg">
                                    
                                    <div class="flex justify-between items-start gap-3 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-950 text-slate-500 border border-white/5">
                                                #{{ $ticket->id }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">
                                                {{ $ticket->category ?? 'Geral' }}
                                            </span>
                                        </div>
                                        <x-ticket-status :status="$ticket->status" />
                                    </div>

                                    <h4 class="text-sm font-bold text-slate-200 group-hover:text-white truncate transition-colors mb-2">
                                        {{ $ticket->subject }}
                                    </h4>

                                    <div class="flex items-center gap-2 pt-2 border-t border-white/5">
                                        <div class="h-5 w-5 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-slate-300 font-bold">
                                            {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-xs text-slate-400 truncate max-w-[120px]">{{ $ticket->user->name ?? 'Usuário' }}</span>
                                        <span class="text-[10px] text-slate-600 ml-auto">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl border border-dashed border-white/10 bg-slate-900/30 text-center">
                                <div class="text-3xl mb-2 opacity-50">📭</div>
                                <div class="text-slate-500 text-sm">Sem chamados recentes.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script do Gráfico --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('weeklyChart');
            const labels = @json($chartLabels ?? []);
            const values = @json($chartValues ?? []);

            if (ctx) {
                // Gradiente para o gráfico
                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo 500
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                new Chart(ctx, {
                    type: 'bar', // ou 'line' se preferir
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Chamados',
                            data: values,
                            backgroundColor: '#6366f1',
                            hoverBackgroundColor: '#818cf8',
                            borderRadius: 6,
                            barThickness: 20, // Barras mais finas e elegantes
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                padding: 10,
                                cornerRadius: 8,
                                displayColors: false,
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { color: '#64748b' }
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { color: '#64748b' }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>