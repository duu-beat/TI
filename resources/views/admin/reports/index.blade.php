<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Relatórios de Chamados
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ loaded: false, showFilters: true }" x-init="setTimeout(() => loaded = true, 500)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Painel de Filtros (Collapsible) --}}
            <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-xl overflow-hidden transition-all duration-300">
                <div class="p-6 bg-white/5 border-b border-white/5 flex justify-between items-center cursor-pointer" @click="showFilters = !showFilters">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filtros Avançados
                    </h3>
                    <svg class="w-5 h-5 text-slate-400 transform transition-transform" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <div x-show="showFilters" x-collapse>
                    <div class="p-6">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Data Inicial</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                       class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Data Final</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                       class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Status</label>
                                <select name="status" class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 transition appearance-none">
                                    <option value="">Todos</option>
                                    @foreach(\App\Enums\TicketStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Prioridade</label>
                                <select name="priority" class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 transition appearance-none">
                                    <option value="">Todas</option>
                                    @foreach(\App\Enums\TicketPriority::cases() as $priority)
                                        <option value="{{ $priority->value }}" {{ request('priority') == $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Categoria</label>
                                <input type="text" name="category" value="{{ request('category') }}" placeholder="Ex: Hardware..." 
                                       class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Responsável</label>
                                <select name="assigned_to" class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 transition appearance-none">
                                    <option value="">Todos</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2 flex items-end gap-3">
                                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Filtrar Resultados
                                </button>
                                <a href="{{ route('admin.reports.index') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-6 py-2.5 rounded-xl transition border border-white/10">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="!loaded" class="space-y-6 animate-pulse">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @for($i = 0; $i < 4; $i++)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-6 h-32"></div>
                    @endfor
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <x-skeleton-table :rows="5" :columns="6" />
                </div>
            </div>

            <div x-show="loaded" style="display: none;" 
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                {{-- Card: Total --}}
                <div class="bg-slate-900 border border-blue-500/20 rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-blue-400 uppercase tracking-wider">Total</span>
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $stats['total'] }}</p>
                </div>

                {{-- Card: Resposta --}}
                <div class="bg-slate-900 border border-green-500/20 rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-500/10 rounded-full blur-xl group-hover:bg-green-500/20 transition"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-green-400 uppercase tracking-wider">T.M. Resposta</span>
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ number_format($stats['avg_response_time'], 0) }} <span class="text-sm font-medium text-slate-500">min</span></p>
                </div>

                {{-- Card: Resolução --}}
                <div class="bg-slate-900 border border-purple-500/20 rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-purple-400 uppercase tracking-wider">T.M. Resolução</span>
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ number_format($stats['avg_resolution_time'], 0) }} <span class="text-sm font-medium text-slate-500">min</span></p>
                </div>

                {{-- Card: Avaliação --}}
                <div class="bg-slate-900 border border-yellow-500/20 rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-yellow-500/10 rounded-full blur-xl group-hover:bg-yellow-500/20 transition"></div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-yellow-400 uppercase tracking-wider">Satisfação</span>
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ number_format($stats['avg_rating'], 1) }} <span class="text-sm font-medium text-slate-500">/ 5.0</span></p>
                </div>
            </div>

            <div x-show="loaded" class="flex justify-end gap-3 pb-2">
                <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" 
                   class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2 shadow-lg shadow-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    PDF
                </a>
                <a href="{{ route('admin.reports.export-excel', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2 shadow-lg shadow-green-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Excel
                </a>
            </div>

            <div x-show="loaded" class="bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-xl"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-slate-950 border-b border-white/5 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Assunto</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Prioridade</th>
                                <th class="px-6 py-4">Responsável</th>
                                <th class="px-6 py-4">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 font-mono text-white">#{{ $ticket->id }}</td>
                                <td class="px-6 py-4 text-white font-medium">{{ $ticket->user->name }}</td>
                                <td class="px-6 py-4">{{ Str::limit($ticket->subject, 40) }}</td>
                                <td class="px-6 py-4">
                                    <x-ticket-status :status="$ticket->status" />
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->priority === \App\Enums\TicketPriority::HIGH)
                                        <span class="text-red-400 font-bold text-xs flex items-center gap-1">🔥 ALTA</span>
                                    @elseif($ticket->priority === \App\Enums\TicketPriority::MEDIUM)
                                        <span class="text-yellow-400 font-bold text-xs">⚠️ MÉDIA</span>
                                    @else
                                        <span class="text-emerald-400 font-bold text-xs">🟢 BAIXA</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->assignee)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded bg-slate-700 flex items-center justify-center text-xs text-white font-bold">{{ substr($ticket->assignee->name, 0, 1) }}</div>
                                            <span class="text-xs">{{ $ticket->assignee->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-600 italic">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                    {{ $ticket->created_at->format('d/m/y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-50">
                                        <div class="text-4xl mb-2">📂</div>
                                        <p>Nenhum dado encontrado para este filtro.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-white/5 bg-slate-950">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>