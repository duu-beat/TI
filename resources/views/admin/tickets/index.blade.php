<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-200 leading-tight">
                {{ __('Gerenciar Chamados') }}
            </h2>
            {{-- Botão Gerar Relatório --}}
            <a href="{{ route('admin.tickets.report') }}" target="_blank"
               class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition flex items-center gap-2">
                📄 <span class="hidden sm:inline">Gerar Relatório</span>
            </a>
        </div>
    </x-slot>

    {{-- ⚡ ALPINE.JS CONTROLLER --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 💀 SKELETON LOADING (Filtros + Lista) --}}
            <div x-show="!loaded" class="space-y-6 animate-pulse">
                {{-- Skeleton Filtros --}}
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5 h-20 w-full"></div>

                {{-- Skeleton Lista de Cards --}}
                <div class="space-y-4">
                    @for($i = 0; $i < 5; $i++)
                        <div class="p-6 rounded-2xl bg-white/5 border border-white/5 flex flex-col md:flex-row justify-between gap-4">
                            <div class="flex gap-4 w-full">
                                <div class="h-12 w-12 rounded-full bg-white/5 shrink-0"></div>
                                <div class="space-y-3 w-full max-w-lg">
                                    <div class="h-4 bg-white/5 rounded w-3/4"></div>
                                    <div class="h-3 bg-white/5 rounded w-1/2"></div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-3 w-32 shrink-0">
                                <div class="h-6 w-20 bg-white/5 rounded-full"></div>
                                <div class="h-3 w-16 bg-white/5 rounded"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- ✅ CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- BARRA DE FILTROS --}}
                <div class="mb-8">
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="p-4 rounded-2xl bg-slate-900/50 border border-white/10 flex flex-col md:flex-row gap-4 items-center shadow-lg">
                        
                        {{-- Busca --}}
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Buscar por ID, Assunto ou Cliente..." 
                                   class="w-full pl-4 pr-4 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-slate-200 focus:border-cyan-500/50 outline-none placeholder-slate-600 focus:ring-1 focus:ring-cyan-500/50 transition">
                        </div>

                        {{-- Filtro Status --}}
                        <div class="w-full md:w-64">
                            <select name="status" onchange="this.form.submit()" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-slate-950 border border-white/10 text-slate-200 focus:border-cyan-500/50 outline-none focus:ring-1 focus:ring-cyan-500/50 transition cursor-pointer">
                                <option value="">Todos os Status</option>
                                @foreach(\App\Enums\TicketStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                {{-- LISTA DE CHAMADOS --}}
                <div class="space-y-4">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="block p-6 rounded-2xl bg-slate-800/50 border border-white/5 hover:bg-slate-800 hover:border-cyan-500/30 transition group relative overflow-hidden">
                            
                            {{-- Hover Glow --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/0 via-cyan-500/5 to-cyan-500/0 opacity-0 group-hover:opacity-100 transition duration-700 pointer-events-none"></div>

                            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-start gap-4">
                                    {{-- Avatar Letra --}}
                                    <div class="h-12 w-12 rounded-full bg-slate-700 flex items-center justify-center text-lg font-bold text-white border border-white/10 shrink-0">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-950 border border-white/10 text-[10px] font-mono text-slate-400">#{{ $ticket->id }}</span>
                                            <h3 class="font-bold text-lg text-white group-hover:text-cyan-400 transition line-clamp-1">
                                                {{ $ticket->subject }}
                                            </h3>
                                        </div>
                                        <div class="flex items-center gap-3 text-sm text-slate-400">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                {{ $ticket->user->name }}
                                            </span>
                                            <span class="text-slate-600">•</span>
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between md:justify-end gap-4 md:w-auto w-full border-t md:border-t-0 border-white/5 pt-4 md:pt-0">
                                    {{-- Prioridade (Se alta) --}}
                                    @if($ticket->priority === \App\Enums\TicketPriority::HIGH)
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-red-400 bg-red-500/10 px-3 py-1.5 rounded-lg border border-red-500/20">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                            </span>
                                            ALTA
                                        </div>
                                    @endif

                                    <x-ticket-status :status="$ticket->status" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-20 rounded-2xl border border-dashed border-white/10 bg-white/5">
                            <div class="text-4xl mb-4">📭</div>
                            <h3 class="text-lg font-medium text-white">Nenhum chamado encontrado</h3>
                            <p class="text-slate-400 text-sm">Tente ajustar os filtros de busca.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Paginação --}}
                <div class="mt-8">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>