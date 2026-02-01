@extends('layouts.site')

@section('title', auth()->check() ? 'Dashboard - Suporte TI' : 'Suporte TI - Soluções Corporativas')
@section('meta_description', 'Gestão de TI especializada. Segurança, Infraestrutura e Suporte para empresas.')

@section('content')

@auth
    @php
        $user = auth()->user();
        
        // --- DADOS MASTER (SEGURANÇA) ---
        if ($user->isMaster()) {
            // Conta chamados escalonados
            $escalatedCount = \App\Models\Ticket::where('is_escalated', true)
                ->where('status', '!=', \App\Enums\TicketStatus::RESOLVED)
                ->count();
            // Conta admins ativos (usando string 'admin' direto para evitar erro de constante)
            $adminsCount = \App\Models\User::where('role', 'admin')->count();
        }
        
        // --- DADOS ADMIN ---
        elseif ($user->isAdmin()) {
            $urgentCount = \App\Models\Ticket::where('priority', \App\Enums\TicketPriority::HIGH)
                ->whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS])
                ->count();
            $openTickets = \App\Models\Ticket::whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS])->count();
        } 
        
        // --- DADOS CLIENTE ---
        else {
            $recentTickets = $user->tickets()->latest()->take(3)->get();
            $ticketsMonth = $user->tickets()->whereMonth('created_at', now()->month)->count();
        }
    @endphp
@endauth

<div class="relative min-h-screen flex flex-col">
    
    {{-- Background Global --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-600/5 rounded-full blur-[100px]"></div>
    </div>

    @auth
        
        {{-- ======================================================================
             🛑 1. VISÃO DO MASTER (SEGURANÇA / ROOT)
             ====================================================================== --}}
        @if(auth()->user()->isMaster())
            <div class="max-w-7xl mx-auto px-6 py-12 w-full">
                
                {{-- Header Segurança --}}
                <div class="flex flex-col md:flex-row items-center justify-between mb-10 border-b border-red-500/20 pb-8 gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold uppercase tracking-widest mb-2 shadow-[0_0_10px_rgba(239,68,68,0.2)]">
                            🛡️ Security Clearance: Level 1
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-white">Comando Central</h1>
                    </div>
                    
                    {{-- Status Segurança --}}
                    <div class="flex gap-4">
                        <div class="px-5 py-3 rounded-xl bg-slate-950 border border-red-500/30 flex flex-col items-center justify-center min-w-[140px] shadow-lg shadow-red-500/10">
                            <span class="text-2xl font-black text-red-500 animate-pulse">{{ $escalatedCount }}</span>
                            <span class="text-[10px] uppercase font-bold text-red-400/70">Alertas Críticos</span>
                        </div>
                        <div class="px-5 py-3 rounded-xl bg-slate-900 border border-white/10 flex flex-col items-center justify-center min-w-[120px]">
                            <span class="text-2xl font-black text-white">{{ $adminsCount }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Admins Ativos</span>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    {{-- Card: Painel de Segurança --}}
                    <a href="{{ route('master.dashboard') }}" class="group p-6 rounded-2xl bg-slate-950 border border-red-500/20 hover:border-red-500 hover:bg-red-500/5 transition relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-10 text-6xl text-red-500 group-hover:scale-110 transition">🚨</div>
                        <h3 class="font-bold text-red-100 mb-2">Painel de Incidentes</h3>
                        <p class="text-xs text-red-200/50 mb-4 h-8">Resolver chamados escalonados.</p>
                        <div class="w-full py-2 rounded-lg bg-red-500/10 text-red-400 text-xs font-bold uppercase group-hover:bg-red-500 group-hover:text-white transition text-center">
                            Acessar Agora
                        </div>
                    </a>

                    {{-- Card: Gestão de Admins --}}
                    <a href="{{ route('master.users.index') }}" class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-white/30 transition relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-white group-hover:scale-110 transition">👮</div>
                        <h3 class="font-bold text-white mb-2">Hierarquia</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Promover ou rebaixar administradores.</p>
                        <div class="w-full py-2 rounded-lg bg-white/5 text-slate-300 text-xs font-bold uppercase group-hover:bg-white/20 group-hover:text-white transition text-center">
                            Gerenciar Equipe
                        </div>
                    </a>

                    {{-- Card: Atalho Admin --}}
                    <a href="{{ route('admin.dashboard') }}" class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-cyan-500 transition relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-cyan-500 group-hover:scale-110 transition">📊</div>
                        <h3 class="font-bold text-white mb-2">Visão Administrativa</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Acessar o painel padrão de atendimento.</p>
                        <div class="w-full py-2 rounded-lg bg-cyan-500/10 text-cyan-400 text-xs font-bold uppercase group-hover:bg-cyan-500 group-hover:text-white transition text-center">
                            Ir para Admin
                        </div>
                    </a>
                </div>
            </div>

        {{-- ======================================================================
             📊 2. VISÃO DO ADMINISTRADOR
             ====================================================================== --}}
        @elseif(auth()->user()->isAdmin())
            {{-- ... conteúdo do admin (mantido) ... --}}
            <div class="max-w-7xl mx-auto px-6 py-12 w-full">
                <div class="flex flex-col md:flex-row items-center justify-between mb-10 border-b border-white/10 pb-8 gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-widest mb-2">
                            ⚡ Admin Panel
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-white">Centro de Controle</h1>
                    </div>
                    <div class="flex gap-4">
                        <div class="px-5 py-3 rounded-xl bg-slate-900 border {{ $urgentCount > 0 ? 'border-orange-500/50 bg-orange-500/10' : 'border-white/10' }} flex flex-col items-center justify-center min-w-[120px]">
                            <span class="text-2xl font-black {{ $urgentCount > 0 ? 'text-orange-400' : 'text-white' }}">{{ $urgentCount }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Urgentes</span>
                        </div>
                        <div class="px-5 py-3 rounded-xl bg-slate-900 border border-white/10 flex flex-col items-center justify-center min-w-[120px]">
                            <span class="text-2xl font-black text-white">{{ $openTickets }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Em Aberto</span>
                        </div>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <a href="{{ route('admin.dashboard') }}" class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-cyan-500 transition relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-cyan-500 group-hover:scale-110 transition">🎫</div>
                        <h3 class="font-bold text-white mb-2">Chamados</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Ver fila de atendimento.</p>
                        <div class="w-full py-2 rounded-lg bg-cyan-500/10 text-cyan-400 text-xs font-bold uppercase group-hover:bg-cyan-500 group-hover:text-white transition text-center">Acessar</div>
                    </a>
                </div>
            </div>

        {{-- ======================================================================
             🚀 3. VISÃO DO CLIENTE
             ====================================================================== --}}
        @else
            {{-- ... conteúdo do cliente (mantido) ... --}}
            <div class="max-w-7xl mx-auto px-6 py-12 w-full">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-white">Meu Espaço</h1>
                        <p class="text-slate-400">Gerencie sua assinatura e acompanhe seus tickets.</p>
                    </div>
                    <a href="{{ route('client.tickets.create') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-bold hover:brightness-110 transition shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                        <span>+</span> Abrir Chamado
                    </a>
                </div>
                {{-- ... cards do cliente ... --}}
                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/80 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-0">
                                <div class="bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-bl-2xl uppercase tracking-wider">Ativo</div>
                            </div>
                            <div class="flex items-center gap-6 mb-8">
                                <div class="h-16 w-16 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-3xl border border-indigo-500/20 text-indigo-400">🏢</div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white mb-1">Business Standard</h2>
                                    <p class="text-slate-400 text-sm">Renova em: <span class="text-white font-bold">15/02/2026</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    {{-- ======================================================================
         🌍 4. VISÃO DO VISITANTE (LANDING PAGE)
         ====================================================================== --}}
    @else
        {{-- ... landing page (mantida) ... --}}
        <div class="w-full">
            <div class="max-w-7xl mx-auto px-6 pt-20 pb-20 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800 border border-slate-700 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-6">
                    🚀 Suporte TI Premium
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-tight">
                    TI que funciona. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Negócio que cresce.</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-10">
                    Tenha um departamento de TI completo por uma fração do custo.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/25">Ver Planos</a>
                    <a href="{{ route('contact') }}" class="px-8 py-4 rounded-xl bg-white/10 text-white font-bold hover:bg-white/20 transition border border-white/5">Falar com Especialista</a>
                </div>
            </div>
        </div>
    @endauth
</div>
@endsection