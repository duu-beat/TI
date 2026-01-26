@extends('layouts.site')

@section('content')

{{-- ======================================================================
     LÓGICA DO USUÁRIO LOGADO (CLIENTE OU ADMIN)
     ====================================================================== --}}
@auth
    @php
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            // ADMIN: Contar chamados que precisam de atenção
            $newTickets = \App\Models\Ticket::where('status', \App\Enums\TicketStatus::NEW)->count();
            $waitingTickets = \App\Models\Ticket::where('status', \App\Enums\TicketStatus::WAITING_CLIENT)->count();
        } else {
            // CLIENTE: Buscar o último chamado ativo
            $myActiveTicket = \App\Models\Ticket::where('user_id', $user->id)
                ->whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS, \App\Enums\TicketStatus::WAITING_CLIENT])
                ->latest()
                ->first();
        }
    @endphp

    <div class="relative min-h-[85vh] flex items-center justify-center overflow-hidden pt-20 pb-20">
        
        {{-- Luzes de Fundo (Ambiente) --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-full pointer-events-none">
            <div class="absolute top-[10%] left-[10%] w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-96 h-96 bg-cyan-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 max-w-5xl w-full px-6">
            
            {{-- Cabeçalho de Boas-vindas --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-slate-300 text-xs font-semibold uppercase tracking-wider mb-6">
                    👋 Bem-vindo de volta
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-4">
                    Olá, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">{{ explode(' ', $user->name)[0] }}</span>.
                </h1>
                <p class="text-xl text-slate-400">
                    @if($user->role === 'admin')
                        O sistema está operacional. Aqui está o resumo do dia.
                    @else
                        Estamos prontos para ajudar. Como está a sua tecnologia hoje?
                    @endif
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">

                {{-- ==========================================
                     VISÃO DO ADMIN (Painel de Controle Rápido)
                     ========================================== --}}
                @if($user->role === 'admin')
                    
                    {{-- Card Principal: Fila de Entrada --}}
                    <div class="group relative p-8 rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl hover:border-indigo-500/30 transition duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition duration-500">
                            <span class="text-8xl">🔥</span>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="h-2 w-2 rounded-full bg-indigo-400 animate-pulse"></span>
                                <span class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Fila de Entrada</span>
                            </div>
                            
                            <div class="text-5xl font-bold text-white mb-2">{{ $newTickets }}</div>
                            <p class="text-slate-400 mb-8">Novos chamados aguardando triagem.</p>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('admin.tickets.index') }}" class="w-full text-center py-3 rounded-xl bg-indigo-500 hover:bg-indigo-400 text-white font-bold transition shadow-lg shadow-indigo-500/20">
                                    Ver Fila
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Card Secundário: Ações --}}
                    <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl flex flex-col justify-center">
                        <h3 class="text-lg font-bold text-white mb-6">Acesso Rápido</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between p-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 transition group">
                                <span class="text-slate-200 font-medium group-hover:text-white">📊 Dashboard Completo</span>
                                <span class="text-slate-500 group-hover:text-cyan-400 transition">→</span>
                            </a>
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5">
                                <span class="text-slate-400">Aguardando Cliente</span>
                                <span class="text-yellow-400 font-bold">{{ $waitingTickets }}</span>
                            </div>
                        </div>
                    </div>

                {{-- ==========================================
                     VISÃO DO CLIENTE (Status Pessoal)
                     ========================================== --}}
                @else
                    
                    {{-- Card de Status --}}
                    <div class="group relative p-8 rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl hover:border-cyan-500/30 transition duration-300 overflow-hidden">
                        
                        @if($myActiveTicket)
                            {{-- Tem chamado aberto --}}
                            <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition duration-500">
                                <span class="text-8xl">🎫</span>
                            </div>
                            <div class="relative z-10 h-full flex flex-col">
                                <div class="inline-flex self-start items-center gap-2 px-2 py-1 rounded-lg bg-cyan-500/10 text-cyan-400 text-xs font-bold mb-4 border border-cyan-500/20">
                                    Em andamento
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2 line-clamp-2">{{ $myActiveTicket->subject }}</h3>
                                <p class="text-slate-400 text-sm mb-8 line-clamp-2 flex-1">{{ $myActiveTicket->description }}</p>
                                
                                <a href="{{ route('client.tickets.show', $myActiveTicket) }}" class="w-full text-center py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold transition shadow-lg shadow-cyan-500/20">
                                    Ver Detalhes
                                </a>
                            </div>
                        @else
                            {{-- Tudo tranquilo --}}
                            <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition duration-500">
                                <span class="text-8xl">✅</span>
                            </div>
                            <div class="relative z-10 h-full flex flex-col justify-center">
                                <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center text-xl mb-4 text-emerald-400">✨</div>
                                <h3 class="text-2xl font-bold text-white mb-2">Tudo certo por aqui!</h3>
                                <p class="text-slate-400 mb-6">Você não tem chamados em aberto no momento.</p>
                                <a href="{{ route('client.dashboard') }}" class="text-cyan-400 font-bold hover:text-white transition text-sm flex items-center gap-2">
                                    Ir para meu histórico <span>→</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Card de Abrir Novo --}}
                    <div class="p-8 rounded-3xl border border-white/10 bg-gradient-to-br from-white/5 to-transparent backdrop-blur-xl flex flex-col justify-center text-center">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 flex items-center justify-center text-3xl mb-4 text-indigo-300 border border-white/5">
                            ⚡
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Novo Problema?</h3>
                        <p class="text-slate-400 text-sm mb-8">Relate o que está acontecendo e nossa equipe entrará em ação.</p>
                        
                        <a href="{{ route('client.tickets.create') }}" class="w-full py-3 rounded-xl border border-white/10 bg-white/5 font-bold text-white hover:bg-white/10 transition hover:scale-[1.02]">
                            + Abrir Novo Chamado
                        </a>
                    </div>
                @endif
            </div>

            {{-- Footerzinho do Painel --}}
            <div class="mt-16 text-center">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-slate-500 hover:text-red-400 transition flex items-center justify-center gap-2 mx-auto uppercase tracking-widest font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Encerrar Sessão
                    </button>
                </form>
            </div>
        </div>
    </div>

{{-- ======================================================================
     VISÃO DO VISITANTE (LANDING PAGE DE MARKETING)
     ====================================================================== --}}
@else
    <div class="relative overflow-hidden">
        
        {{-- HERO SECTION --}}
        <section class="relative pt-20 pb-32 lg:pt-32 lg:pb-40">
            <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
                
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-8 animate-fade-in-up">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Sistema Online v1.0
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                    Suporte de TI <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-cyan-400 to-emerald-400">
                        simples e eficiente.
                    </span>
                </h1>

                <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Chega de e-mails perdidos. Centralize os seus problemas de TI numa plataforma organizada, rápida e transparente.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 font-bold text-slate-950 text-lg hover:shadow-[0_0_30px_rgba(6,182,212,0.4)] hover:scale-105 transition-all duration-300">
                        Criar Conta Grátis
                    </a>
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white/5 border border-white/10 font-bold text-white hover:bg-white/10 transition-all">
                        Já tenho conta
                    </a>
                </div>
            </div>
        </section>
        
        {{-- Features Grid --}}
        <section class="py-24 bg-white/[0.02] border-t border-white/5 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-3xl border border-white/10 bg-white/5 hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-2xl mb-6 text-indigo-400">⚡</div>
                        <h3 class="text-xl font-bold text-white mb-2">Atendimento Rápido</h3>
                        <p class="text-slate-400 leading-relaxed">Abra chamados em segundos e receba notificações em tempo real.</p>
                    </div>
                    <div class="p-8 rounded-3xl border border-white/10 bg-white/5 hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center text-2xl mb-6 text-cyan-400">📊</div>
                        <h3 class="text-xl font-bold text-white mb-2">Tudo Organizado</h3>
                        <p class="text-slate-400 leading-relaxed">Histórico completo de problemas e soluções. Nada se perde.</p>
                    </div>
                    <div class="p-8 rounded-3xl border border-white/10 bg-white/5 hover:-translate-y-2 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-2xl mb-6 text-emerald-400">🔒</div>
                        <h3 class="text-xl font-bold text-white mb-2">100% Seguro</h3>
                        <p class="text-slate-400 leading-relaxed">Ambiente criptografado e acesso restrito aos seus dados.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endauth

@endsection