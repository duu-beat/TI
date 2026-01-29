<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span>Meus Chamados</span>
            <a href="{{ route('client.tickets.create') }}"
               class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-2 text-sm font-bold text-slate-950 
                      transition-all duration-300 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:brightness-110 active:scale-95 flex items-center gap-1">
                <span>+</span> <span class="hidden sm:inline">Novo Chamado</span>
            </a>
        </div>
    </x-slot>

    {{-- BARRA DE PESQUISA E FILTROS --}}
    <div class="mb-8 mt-4">
        <form method="GET" action="{{ route('client.tickets.index') }}" class="p-4 rounded-2xl bg-slate-900/50 border border-white/10 flex flex-col md:flex-row gap-4 items-center shadow-lg">
            
            {{-- Campo de Busca --}}
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por assunto ou ID..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-slate-200 placeholder-slate-600 focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 outline-none transition">
            </div>

            {{-- Filtro de Status --}}
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <select name="status" class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-slate-200 focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 outline-none appearance-none cursor-pointer">
                    <option value="">Todos os status</option>
                    @foreach(\App\Enums\TicketStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botão Filtrar --}}
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow-lg shadow-indigo-500/20 hover:scale-105 active:scale-95">
                Filtrar
            </button>

            {{-- Botão Limpar --}}
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('client.tickets.index') }}" class="text-sm text-slate-400 hover:text-white underline decoration-slate-600 hover:decoration-white whitespace-nowrap">
                    Limpar filtros
                </a>
            @endif
        </form>
    </div>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">

        {{-- SKELETON --}}
        <div x-show="!loaded" class="space-y-4 animate-pulse">
            @foreach(range(1, 4) as $i)
                <div class="rounded-2xl border border-white/5 bg-white/5 p-5 h-32"></div>
            @endforeach
        </div>

        {{-- CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
             
            @if($tickets->count() > 0)
                <div class="grid gap-4">
                    @foreach($tickets as $ticket)
                        <a href="{{ route('client.tickets.show', $ticket) }}"
                           class="relative block rounded-2xl border border-white/10 bg-white/5 p-5 group
                                  transition-all duration-300 
                                  hover:-translate-y-1 hover:bg-slate-800/80 hover:border-cyan-500/30 
                                  hover:shadow-[0_10px_30px_-10px_rgba(6,182,212,0.15)]">
                            
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="font-semibold text-white text-lg group-hover:text-cyan-400 transition">
                                        {{ $ticket->subject }}
                                    </div>
                                    <div class="text-sm text-slate-400 line-clamp-1 group-hover:text-slate-300">
                                        {{ Str::limit($ticket->description, 80) }}
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    {{-- 🔥 USO DO SMART ENUM AQUI --}}
                                    <span class="px-3 py-1 rounded-full text-[11px] uppercase tracking-wider font-bold border {{ $ticket->status->color() }}">
                                        {{ $ticket->status->label() }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center gap-4 text-xs text-slate-500 border-t border-white/5 pt-3">
                                <div class="flex items-center gap-1.5" title="{{ $ticket->created_at->format('d/m/Y H:i') }}">
                                    <span class="text-slate-600">📅</span> 
                                    {{ ucfirst($ticket->created_at->diffForHumans()) }}
                                </div>
                                
                                @if($ticket->priority)
                                    <div class="flex items-center gap-1.5">
                                        @if($ticket->priority->value === 'high')
                                            🔥 <span class="text-red-400 font-medium">Alta Prioridade</span>
                                        @elseif($ticket->priority->value === 'medium')
                                            ⚠️ <span class="text-yellow-400">Média</span>
                                        @else
                                            🟢 <span class="text-emerald-400">Normal</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="ml-auto flex items-center gap-1 text-cyan-500/0 group-hover:text-cyan-400 transition-all duration-300 transform translate-x-[-10px] group-hover:translate-x-0">
                                    Ver detalhes →
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $tickets->links() }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="flex flex-col items-center justify-center py-16 text-center rounded-3xl border border-dashed border-white/10 bg-white/5">
                    <div class="bg-slate-800/50 p-4 rounded-full mb-4 ring-1 ring-white/10 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-cyan-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Nenhum chamado encontrado</h3>
                    <p class="text-sm text-slate-400 max-w-xs mx-auto mt-1 mb-6">
                        Está tudo tranquilo por aqui! Se precisar de ajuda, estamos à disposição.
                    </p>
                    
                    <a href="{{ route('client.tickets.create') }}"
                       class="inline-flex items-center gap-2 rounded-2xl bg-white/10 px-5 py-2.5 text-sm font-bold text-white hover:bg-white/20 hover:scale-105 transition-all duration-300">
                        <span>✨</span> Abrir primeiro chamado
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>