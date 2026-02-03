<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    {{ __('Gerenciar Chamados') }}
                </h2>
            </div>
            
            {{-- Botão Relatório --}}
            <a href="{{ route('admin.tickets.report') }}" target="_blank"
               class="group flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-white/10 rounded-xl text-sm font-bold text-white transition hover:shadow-lg">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Gerar Relatório</span>
            </a>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON LOADER --}}
            <div x-show="!loaded" class="space-y-4 animate-pulse">
                <div class="h-16 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- PAINEL DE CONTROLE (Filtros) --}}
                <div class="sticky top-0 z-20 rounded-2xl bg-slate-900/90 backdrop-blur-xl border border-white/10 shadow-2xl p-2">
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex flex-col md:flex-row gap-2">
                        
                        {{-- Busca --}}
                        <div class="relative flex-1 group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Buscar por ID, Assunto, Cliente..." 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none transition placeholder-slate-600 text-sm">
                        </div>

                        {{-- Filtro Status --}}
                        <div class="relative w-full md:w-56 group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </div>
                            <select name="status" onchange="this.form.submit()" 
                                    class="w-full pl-10 pr-8 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 outline-none transition cursor-pointer appearance-none text-sm">
                                <option value="" class="bg-slate-900">Todos os Status</option>
                                @foreach(\App\Enums\TicketStatus::cases() as $status)
                                    <option value="{{ $status->value }}" class="bg-slate-900" {{ request('status') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botão Limpar --}}
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 hover:text-red-300 transition flex items-center justify-center" title="Limpar Filtros">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- TABELA DE DADOS (Data Grid) --}}
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5 text-xs uppercase tracking-wider text-slate-400 font-bold">
                                    <th class="px-6 py-4 w-20">ID</th>
                                    <th class="px-6 py-4">Solicitante</th>
                                    <th class="px-6 py-4">Assunto</th>
                                    <th class="px-6 py-4">Prioridade</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @forelse($tickets as $ticket)
                                    <tr class="group hover:bg-white/[0.02] transition duration-200">
                                        {{-- ID --}}
                                        <td class="px-6 py-4 font-mono text-slate-500">
                                            #{{ $ticket->id }}
                                        </td>

                                        {{-- Solicitante --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-300 border border-white/10">
                                                    {{ substr($ticket->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-white">{{ $ticket->user->name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $ticket->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Assunto --}}
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs">
                                                <div class="font-medium text-slate-200 group-hover:text-indigo-400 transition truncate mb-1">
                                                    {{ $ticket->subject }}
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-slate-800 text-slate-400 border border-white/5">
                                                    {{ $ticket->category ?? 'Geral' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Prioridade --}}
                                        <td class="px-6 py-4">
                                            @if($ticket->priority === \App\Enums\TicketPriority::HIGH)
                                                <div class="flex items-center gap-2 text-red-400 font-bold text-xs">
                                                    <span class="relative flex h-2 w-2">
                                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                    </span>
                                                    ALTA
                                                </div>
                                            @elseif($ticket->priority === \App\Enums\TicketPriority::MEDIUM)
                                                <span class="text-yellow-400 font-medium text-xs">Média</span>
                                            @else
                                                <span class="text-emerald-400 font-medium text-xs">Normal</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            <x-ticket-status :status="$ticket->status" />
                                            <div class="text-[10px] text-slate-600 mt-1">
                                                {{ $ticket->updated_at->diffForHumans() }}
                                            </div>
                                        </td>

                                        {{-- Ações --}}
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white border border-indigo-500/20 transition-all text-xs font-bold uppercase tracking-wide">
                                                <span>Gerir</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="h-16 w-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-white/5">
                                                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                </div>
                                                <h3 class="text-white font-medium mb-1">Nenhum chamado encontrado</h3>
                                                <p class="text-slate-500 text-sm">Tente ajustar os filtros de busca.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginação --}}
                    @if($tickets->hasPages())
                        <div class="px-6 py-4 border-t border-white/5 bg-slate-900/50">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>