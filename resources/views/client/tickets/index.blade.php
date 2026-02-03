<x-app-layout>
    {{-- CABEÇALHO --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-lg bg-indigo-500/20 flex items-center justify-center border border-indigo-500/30 text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Meus Chamados</h2>
                <p class="text-xs text-slate-400">Gerencie suas solicitações de suporte</p>
            </div>
        </div>

        {{-- 
            BOTÃO NOVO CHAMADO (Com Posicionamento Absoluto)
            Usamos 'absolute right-4' para colar na direita da tela,
            ignorando o bloqueio do layout principal.
        --}}
        <a href="{{ route('client.tickets.create') }}"
        class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition-all duration-300 shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 overflow-hidden text-sm z-50">
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="relative z-10 hidden sm:inline">Abrir Novo Chamado</span>
        </a>
    </x-slot>

    {{-- BARRA DE FILTROS (Sticky & Glass) --}}
    <div class="sticky top-0 z-30 -mt-6 -mx-6 mb-8 px-6 py-4 bg-slate-900/80 backdrop-blur-xl border-b border-white/10 shadow-2xl transition-all duration-300">
        <form method="GET" action="{{ route('client.tickets.index') }}" 
              class="max-w-7xl mx-auto flex flex-col md:flex-row gap-3 items-center">
            
            {{-- Campo de Busca --}}
            <div class="relative w-full md:flex-1 group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-cyan-400">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por ID, assunto..." 
                       class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-950/50 border border-white/10 text-slate-200 placeholder-slate-500 focus:border-cyan-500/50 focus:bg-slate-950 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all text-sm">
            </div>

            {{-- Filtro de Status --}}
            <div class="relative w-full md:w-48">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <select name="status" class="w-full pl-10 pr-8 py-2 rounded-xl bg-slate-950/50 border border-white/10 text-slate-200 focus:border-cyan-500/50 focus:bg-slate-950 focus:ring-2 focus:ring-cyan-500/20 outline-none appearance-none cursor-pointer transition-all text-sm">
                    <option value="" class="bg-slate-900">Todos os Status</option>
                    @foreach(\App\Enums\TicketStatus::cases() as $status)
                        <option value="{{ $status->value }}" class="bg-slate-900" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BOTÃO FILTRAR --}}
            <button type="submit" class="w-full md:w-auto px-6 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/10 text-white font-medium transition hover:shadow-lg text-sm">
                Filtrar
            </button>
            
            {{-- Botão Limpar --}}
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('client.tickets.index') }}" class="px-3 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 hover:text-red-300 transition flex items-center justify-center" title="Limpar Filtros">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif

        </form>
    </div>

    {{-- LISTA DE CHAMADOS --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">

        {{-- SKELETON LOADER --}}
        <div x-show="!loaded" class="space-y-4 animate-pulse">
            @foreach(range(1, 4) as $i)
                <div class="rounded-2xl border border-white/5 bg-slate-900/50 p-6 h-36 flex flex-col justify-between">
                    <div class="flex justify-between">
                        <div class="h-6 bg-slate-800 rounded w-1/3"></div>
                        <div class="h-6 bg-slate-800 rounded w-20"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 bg-slate-800 rounded w-full"></div>
                        <div class="h-4 bg-slate-800 rounded w-2/3"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;">
            @if($tickets->count() > 0)
                <div class="grid gap-4">
                    @foreach($tickets as $index => $ticket)
                        {{-- CARD DO TICKET --}}
                        <a href="{{ route('client.tickets.show', $ticket) }}"
                           class="relative block rounded-2xl border border-white/5 bg-slate-900/40 p-1 group
                                  transition-all duration-500 hover:scale-[1.01]"
                           style="animation: fadeIn 0.5s ease-out {{ $index * 0.1 }}s backwards;">
                            
                            {{-- Borda Hover --}}
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-indigo-500/20 transition-all duration-300"></div>
                            
                            <div class="relative bg-slate-900/60 backdrop-blur-sm rounded-xl p-5 sm:p-6 overflow-hidden">
                                {{-- Background Glow --}}
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/10 blur-[50px] rounded-full group-hover:bg-indigo-500/20 transition-all duration-500"></div>

                                <div class="flex flex-col sm:flex-row gap-5 relative z-10">
                                    {{-- Lado Esquerdo --}}
                                    <div class="flex-1 flex gap-4">
                                        <div class="hidden sm:flex shrink-0 w-12 h-12 rounded-xl bg-slate-800/50 border border-white/10 items-center justify-center text-2xl shadow-inner">
                                            @php
                                                $icon = match($ticket->category ?? 'other') {
                                                    'hardware' => '🖥️',
                                                    'software' => '💾',
                                                    'network'  => '🌐',
                                                    'access'   => '🔒',
                                                    'printer'  => '🖨️',
                                                    default    => '📌'
                                                };
                                            @endphp
                                            {{ $icon }}
                                        </div>

                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-white/5">
                                                    #{{ $ticket->id }}
                                                </span>
                                                <span class="text-xs text-indigo-400 font-medium tracking-wide uppercase">
                                                    {{ $ticket->category ?? 'Geral' }}
                                                </span>
                                            </div>
                                            <h3 class="font-bold text-white text-lg leading-tight group-hover:text-indigo-400 transition-colors">
                                                {{ $ticket->subject }}
                                            </h3>
                                            <p class="text-sm text-slate-400 line-clamp-1 group-hover:text-slate-300 transition-colors">
                                                {{ Str::limit($ticket->description, 90) }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Lado Direito --}}
                                    <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 sm:min-w-[140px] border-t sm:border-t-0 sm:border-l border-white/5 pt-3 sm:pt-0 sm:pl-5">
                                        <x-ticket-status :status="$ticket->status" />
                                        <div class="text-xs text-right space-y-1">
                                            @if($ticket->priority)
                                                <div class="flex items-center justify-end gap-1.5">
                                                    @if($ticket->priority->value === 'high')
                                                        <span class="text-red-400 font-bold flex items-center gap-1">🔥 Alta</span>
                                                    @elseif($ticket->priority->value === 'medium')
                                                        <span class="text-yellow-400 font-medium flex items-center gap-1">⚠️ Média</span>
                                                    @else
                                                        <span class="text-emerald-400 flex items-center gap-1">🟢 Normal</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="text-slate-500" title="{{ $ticket->created_at->format('d/m/Y H:i') }}">
                                                {{ ucfirst($ticket->created_at->diffForHumans()) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $tickets->links() }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="flex flex-col items-center justify-center py-20 text-center rounded-3xl border border-dashed border-white/10 bg-slate-900/30">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-indigo-500/20 blur-xl rounded-full animate-pulse"></div>
                        <div class="relative bg-slate-950 p-6 rounded-2xl border border-white/10 shadow-2xl">
                            <svg class="w-12 h-12 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Tudo Limpo Por Aqui!</h3>
                    <p class="text-sm text-slate-400 max-w-xs mx-auto mb-8">
                        Você não tem nenhum chamado com esses filtros no momento. Aproveite a tranquilidade.
                    </p>
                    <a href="{{ route('client.tickets.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Abrir Novo Chamado
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>