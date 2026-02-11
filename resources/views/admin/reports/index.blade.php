<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Relatórios de Chamados
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filtros -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtros
                </h3>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Data Inicial</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Data Final</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                        <select name="status" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                            <option value="">Todos</option>
                            @foreach(\App\Enums\TicketStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Prioridade</label>
                        <select name="priority" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                            <option value="">Todas</option>
                            @foreach(\App\Enums\TicketPriority::cases() as $priority)
                                <option value="{{ $priority->value }}" {{ request('priority') == $priority->value ? 'selected' : '' }}>
                                    {{ $priority->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Categoria</label>
                        <input type="text" name="category" value="{{ request('category') }}" 
                               placeholder="Ex: Hardware, Software..." 
                               class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Responsável</label>
                        <select name="assigned_to" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                            <option value="">Todos</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 flex items-end gap-3">
                        <button type="submit" 
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.reports.index') }}" 
                           class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-2 rounded-lg transition">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Skeleton Loader -->
            <div x-show="!loaded" class="space-y-6">
                <!-- Skeleton Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 animate-pulse">
                    @for($i = 0; $i < 4; $i++)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                            <div class="h-8 w-8 bg-slate-700/50 rounded"></div>
                        </div>
                        <div class="h-9 w-24 bg-slate-700/50 rounded"></div>
                    </div>
                    @endfor
                </div>
                
                <!-- Skeleton Tabela -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <x-skeleton-table :rows="5" :columns="6" />
                </div>
            </div>

            <!-- Estatísticas -->
            <div x-show="loaded" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-400">Total de Chamados</span>
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-500/10 to-green-600/5 border border-green-500/20 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-400">Tempo Médio Resposta</span>
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['avg_response_time'], 0) }}<span class="text-lg text-slate-400">min</span></p>
                </div>

                <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/5 border border-purple-500/20 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-400">Tempo Médio Resolução</span>
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['avg_resolution_time'], 0) }}<span class="text-lg text-slate-400">min</span></p>
                </div>

                <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/20 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-400">Avaliação Média</span>
                        <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['avg_rating'], 1) }}<span class="text-lg text-slate-400">/5</span></p>
                </div>
            </div>

            <!-- Botões de Exportação -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Exportar Relatório</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Exportar PDF
                    </a>
                    <a href="{{ route('admin.reports.export-excel', request()->all()) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar Excel
                    </a>
                </div>
            </div>

            <!-- Tabela de Chamados -->
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-800/50 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Assunto</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Prioridade</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Responsável</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-mono">#{{ $ticket->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">{{ $ticket->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ Str::limit($ticket->subject, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $ticket->status->color() }}">
                                        {{ $ticket->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $ticket->priority->color() }}">
                                        {{ $ticket->priority->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $ticket->assignee->name ?? 'Não atribuído' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    Nenhum chamado encontrado com os filtros aplicados
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-white/10">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
