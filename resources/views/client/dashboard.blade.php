<x-app-layout>
    <x-slot name="header">
        Visão Geral
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
        
        {{-- Skeleton Loader (Mantido, mas ajustado) --}}
        <div x-show="!loaded" class="space-y-8 animate-pulse">
            <div class="h-40 bg-white/5 rounded-3xl w-full border border-white/5"></div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="h-32 bg-white/5 rounded-2xl"></div>
                <div class="h-32 bg-white/5 rounded-2xl"></div>
                <div class="h-32 bg-white/5 rounded-2xl"></div>
                <div class="h-32 bg-white/5 rounded-2xl"></div>
            </div>
        </div>

        <div x-show="loaded" style="display: none;" class="space-y-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- 1. 🔔 ALERTA DE AÇÃO (Com design de urgência elegante) --}}
            @if(isset($waitingForUser) && $waitingForUser > 0)
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 p-1">
                    <div class="absolute inset-0 bg-amber-500/5 animate-pulse"></div>
                    <div class="relative flex flex-col md:flex-row items-center justify-between gap-6 p-6 rounded-[20px] bg-slate-900/50 backdrop-blur-sm">
                        
                        <div class="flex items-center gap-5">
                            <div class="relative flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-2xl shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                                <span class="animate-bounce">👋</span>
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight">Atenção Necessária</h3>
                                <p class="text-slate-300 mt-1">
                                    O suporte respondeu a <strong class="text-amber-400 border-b border-amber-400/30">{{ $waitingForUser }} chamado(s)</strong>. Sua interação é necessária para prosseguir.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('client.tickets.index', ['status' => 'waiting_client']) }}" 
                           class="group flex items-center gap-2 whitespace-nowrap px-8 py-3 rounded-xl bg-amber-500 text-slate-900 font-bold hover:bg-amber-400 transition shadow-lg shadow-amber-500/20 hover:shadow-amber-500/40 hover:-translate-y-0.5">
                            <span>Responder Agora</span>
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @else
                {{-- 2. Banner de Boas-vindas (Hero Section) --}}
                <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl shadow-black/50 group">
                    {{-- Background Mesh --}}
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[80px] group-hover:bg-indigo-500/30 transition duration-1000"></div>
                    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-[80px] group-hover:bg-cyan-500/20 transition duration-1000"></div>

                    <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <div>
                            <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">
                                Olá, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">{{ Auth::user()->name }}</span>!
                            </h2>
                            <p class="text-slate-400 text-lg max-w-xl leading-relaxed">
                                Bem-vindo à sua central de suporte. Acompanhe seus chamados em tempo real ou inicie um novo atendimento.
                            </p>
                        </div>
                        
                        <a href="{{ route('client.tickets.create') }}" 
                           class="group relative inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition-all duration-300 shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 overflow-hidden">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <svg class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="relative z-10 text-lg">Novo Chamado</span>
                        </a>
                    </div>
                </div>
            @endif

            {{-- 3. 📊 CARDS DE ESTATÍSTICAS (Grid Melhorado) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                {{-- Card: Em Aberto --}}
                <div class="relative overflow-hidden rounded-2xl p-6 bg-slate-900/50 border border-white/5 group hover:border-slate-600 transition duration-300">
                    <div class="absolute -right-6 -top-6 h-24 w-24 bg-slate-500/10 rounded-full blur-2xl group-hover:bg-slate-500/20 transition"></div>
                    <div class="relative">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Em Aberto</div>
                        <div class="flex items-end justify-between">
                            <span class="text-3xl sm:text-4xl font-bold text-white">{{ $stats['open'] }}</span>
                            <svg class="w-8 h-8 text-slate-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card: Em Atendimento --}}
                <div class="relative overflow-hidden rounded-2xl p-6 bg-slate-900/50 border border-white/5 group hover:border-cyan-500/50 transition duration-300">
                    <div class="absolute -right-6 -top-6 h-24 w-24 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition"></div>
                    <div class="relative">
                        <div class="text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">Em Andamento</div>
                        <div class="flex items-end justify-between">
                            <span class="text-3xl sm:text-4xl font-bold text-white">{{ $stats['in_progress'] }}</span>
                            <svg class="w-8 h-8 text-cyan-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card: Finalizados --}}
                <div class="relative overflow-hidden rounded-2xl p-6 bg-slate-900/50 border border-white/5 group hover:border-emerald-500/50 transition duration-300">
                    <div class="absolute -right-6 -top-6 h-24 w-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition"></div>
                    <div class="relative">
                        <div class="text-emerald-400 text-xs font-bold uppercase tracking-wider mb-2">Resolvidos</div>
                        <div class="flex items-end justify-between">
                            <span class="text-3xl sm:text-4xl font-bold text-white">{{ $stats['resolved'] }}</span>
                            <svg class="w-8 h-8 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card: FAQ / Ajuda --}}
                <a href="{{ route('faq') }}" class="relative overflow-hidden rounded-2xl p-6 bg-indigo-600/10 border border-indigo-500/20 group hover:bg-indigo-600/20 hover:border-indigo-500/40 transition duration-300 cursor-pointer flex flex-col justify-center">
                    <div class="absolute -right-6 -top-6 h-24 w-24 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/30 transition"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <div class="text-indigo-300 text-xs font-bold uppercase tracking-wider mb-1">Precisa de Ajuda?</div>
                            <div class="font-bold text-white group-hover:text-indigo-200 transition">Ver FAQ </div>
                        </div>
                        <svg class="w-8 h-8 text-indigo-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </a>
            </div>

            {{-- 4. CONTEÚDO PRINCIPAL (Grid 2 colunas: Lista + FAQ) --}}
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- COLUNA PRINCIPAL: Atividades Recentes --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Atividades Recentes
                        </h3>
                        <a href="{{ route('client.tickets.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition flex items-center gap-1 group">
                            Ver todos
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentTickets as $ticket)
                            <a href="{{ route('client.tickets.show', $ticket) }}" 
                               class="group relative block rounded-2xl border border-white/5 bg-slate-900/40 p-1 hover:bg-slate-800/60 transition-all duration-300 hover:shadow-lg hover:shadow-black/20 hover:-translate-y-0.5">
                                
                                {{-- Borda Brilhante no Hover --}}
                                <div class="absolute inset-0 rounded-2xl border border-transparent group-hover:border-indigo-500/30 transition-all"></div>
                                
                                <div class="relative flex items-center justify-between p-4 sm:p-5">
                                    <div class="flex items-center gap-4 sm:gap-5 overflow-hidden">
                                        
                                        {{-- Ícone da Categoria (Miniatura) --}}
                                        <div class="shrink-0 h-12 w-12 rounded-xl bg-slate-800 border border-white/5 flex items-center justify-center text-xl shadow-inner group-hover:bg-slate-700 transition-colors">
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

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-slate-800 text-slate-400 border border-white/5 group-hover:border-indigo-500/20 group-hover:text-indigo-300 transition">
                                                    #{{ $ticket->id }}
                                                </span>
                                                <span class="text-xs text-slate-500 font-medium truncate">
                                                    {{ $ticket->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <h4 class="text-base font-bold text-slate-200 group-hover:text-white truncate transition-colors">
                                                {{ $ticket->subject }}
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="shrink-0 pl-4">
                                        <x-ticket-status :status="$ticket->status" />
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl border border-dashed border-white/10 bg-slate-900/20 text-center">
                                <div class="h-16 w-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4 text-3xl">🎫</div>
                                <h3 class="text-lg font-bold text-white mb-1">Nenhuma atividade recente</h3>
                                <p class="text-slate-400 text-sm max-w-xs mx-auto mb-6">
                                    Seus últimos chamados aparecerão aqui para acesso rápido.
                                </p>
                                <a href="{{ route('client.tickets.create') }}" class="text-sm font-bold text-indigo-400 hover:text-indigo-300 hover:underline">
                                    Abrir primeiro chamado
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- COLUNA LATERAL: FAQs Rápidas --}}
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2 px-1">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        Dúvidas Comuns
                    </h3>
                    
                    <div class="rounded-3xl bg-slate-900/50 border border-white/10 p-6 backdrop-blur-sm shadow-xl shadow-black/20">
                        <ul class="space-y-4">
                            @forelse($faqs as $faq)
                                <li>
                                    <a href="{{ route('faq') }}#faq-{{ $faq->id }}" class="group flex items-start gap-3">
                                        <div class="mt-1 shrink-0 h-2 w-2 rounded-full bg-slate-600 group-hover:bg-cyan-400 transition-colors"></div>
                                        <span class="text-sm font-medium text-slate-300 group-hover:text-white transition leading-relaxed">
                                            {{ $faq->question }}
                                        </span>
                                    </a>
                                </li>
                            @empty
                                <li class="text-sm text-slate-500 italic text-center py-4">
                                    Nenhuma pergunta frequente cadastrada no momento.
                                </li>
                            @endforelse
                        </ul>

                        <div class="mt-8 pt-6 border-t border-white/5">
                            <a href="{{ route('faq') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/5 text-sm font-bold text-white transition hover:shadow-lg">
                                <span>Acessar Central de Ajuda</span>
                            </a>
                        </div>
                    </div>

                    {{-- Card Extra: Contato --}}
                    <div class="rounded-3xl bg-gradient-to-br from-indigo-900/20 to-purple-900/20 border border-white/5 p-6 text-center">
                        <h4 class="font-bold text-white mb-2">Não encontrou o que busca?</h4>
                        <p class="text-xs text-slate-400 mb-4">Nossa equipe está pronta para resolver problemas complexos.</p>
                        <a href="{{ route('contact') }}" class="text-sm text-indigo-300 hover:text-white font-medium hover:underline">
                            Falar com Suporte Humano
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>