@extends('layouts.site')

{{-- SEO dinâmico --}}
@section('title', auth()->check() ? 'Dashboard - Suporte TI' : 'Suporte TI - Soluções Corporativas')
@section('meta_description', 'Gestão de TI especializada. Segurança, Infraestrutura e Suporte para empresas.')

@section('content')

{{-- ======================================================================
     🔌 LÓGICA DE DADOS (Controlador na View)
     Recupera dados reais para dar vida ao dashboard
     ====================================================================== --}}
@auth
    @php
        $user = auth()->user();
        
        // --- DADOS ADMIN ---
        if ($user->isAdmin()) {
            $urgentCount = \App\Models\Ticket::where('priority', \App\Enums\TicketPriority::HIGH)
                ->whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS])
                ->count();
            $openTickets = \App\Models\Ticket::whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS])->count();
        } 
        // --- DADOS CLIENTE ---
        else {
            // Últimos 3 chamados para a lista rápida
            $recentTickets = $user->tickets()->latest()->take(3)->get();
            // Contagem para barra de progresso simulada
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

    {{-- ======================================================================
         1. VISÃO DO ADMINISTRADOR (Gestão + Dados Reais)
         ====================================================================== --}}
    @auth
        @if(auth()->user()->isAdmin())
            <div class="max-w-7xl mx-auto px-6 py-12 w-full">
                
                {{-- Header Admin --}}
                <div class="flex flex-col md:flex-row items-center justify-between mb-10 border-b border-white/10 pb-8 gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold uppercase tracking-widest mb-2">
                            🔐 Admin Authority
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-white">Centro de Controle</h1>
                    </div>
                    
                    {{-- Status em Tempo Real --}}
                    <div class="flex gap-4">
                        <div class="px-5 py-3 rounded-xl bg-slate-900 border {{ $urgentCount > 0 ? 'border-red-500/50 bg-red-500/10' : 'border-white/10' }} flex flex-col items-center justify-center min-w-[120px]">
                            <span class="text-2xl font-black {{ $urgentCount > 0 ? 'text-red-400' : 'text-white' }}">{{ $urgentCount }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Urgentes</span>
                        </div>
                        <div class="px-5 py-3 rounded-xl bg-slate-900 border border-white/10 flex flex-col items-center justify-center min-w-[120px]">
                            <span class="text-2xl font-black text-white">{{ $openTickets }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Em Aberto</span>
                        </div>
                    </div>
                </div>

                {{-- Grid de Ações --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    {{-- Card: Editar Site --}}
                    <div class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-indigo-500 transition relative overflow-hidden cursor-pointer">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-indigo-500 group-hover:scale-110 transition">✏️</div>
                        <h3 class="font-bold text-white mb-2">Frontend</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Personalize banners e textos.</p>
                        <button class="w-full py-2 rounded-lg bg-indigo-500/10 text-indigo-400 text-xs font-bold uppercase hover:bg-indigo-500 hover:text-white transition">Editar Aparência</button>
                    </div>

                    {{-- Card: Usuários --}}
                    <div class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-cyan-500 transition relative overflow-hidden cursor-pointer">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-cyan-500 group-hover:scale-110 transition">👥</div>
                        <h3 class="font-bold text-white mb-2">Clientes</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Gerencie permissões e acessos.</p>
                        <button class="w-full py-2 rounded-lg bg-cyan-500/10 text-cyan-400 text-xs font-bold uppercase hover:bg-cyan-500 hover:text-white transition">Gerenciar Users</button>
                    </div>

                    {{-- Card: Planos --}}
                    <div class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-emerald-500 transition relative overflow-hidden cursor-pointer">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-emerald-500 group-hover:scale-110 transition">💰</div>
                        <h3 class="font-bold text-white mb-2">Financeiro</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Ajuste valores e recursos.</p>
                        <button class="w-full py-2 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase hover:bg-emerald-500 hover:text-white transition">Tabela de Preços</button>
                    </div>

                    {{-- Card: Sistema --}}
                    <div class="group p-6 rounded-2xl bg-slate-900 border border-white/10 hover:border-red-500 transition relative overflow-hidden cursor-pointer">
                        <div class="absolute right-0 top-0 p-4 opacity-5 text-6xl text-red-500 group-hover:scale-110 transition">⚙️</div>
                        <h3 class="font-bold text-white mb-2">Core</h3>
                        <p class="text-xs text-slate-400 mb-4 h-8">Logs e Backups do sistema.</p>
                        <a href="{{ route('admin.dashboard') }}" class="block text-center w-full py-2 rounded-lg bg-red-500/10 text-red-400 text-xs font-bold uppercase hover:bg-red-500 hover:text-white transition">Dashboard Full</a>
                    </div>
                </div>
            </div>

    {{-- ======================================================================
         2. VISÃO DO CLIENTE (Hub: Assinatura + Operação)
         ====================================================================== --}}
        @else
            <div class="max-w-7xl mx-auto px-6 py-12 w-full">
                
                {{-- Welcome --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-white">Meu Espaço</h1>
                        <p class="text-slate-400">Gerencie sua assinatura e acompanhe seus tickets.</p>
                    </div>
                    <a href="{{ route('client.tickets.create') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-bold hover:brightness-110 transition shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                        <span>+</span> Abrir Chamado
                    </a>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    
                    {{-- 🟢 COLUNA ESQUERDA: Assinatura e Status --}}
                    <div class="lg:col-span-2 space-y-8">
                        
                        {{-- Card do Plano --}}
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

                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between text-xs font-bold uppercase tracking-widest mb-2">
                                        <span class="text-slate-400">Chamados este mês</span>
                                        <span class="text-white">{{ $ticketsMonth }} / 20</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-cyan-500 rounded-full" style="width: {{ min(($ticketsMonth/20)*100, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ⭐ NOVIDADE: Lista de Últimas Interações (Para não ficar só 'bonito' mas funcional) --}}
                        <div>
                            <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span> Atividade Recente
                            </h3>
                            <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
                                @forelse($recentTickets as $ticket)
                                    <a href="{{ route('client.tickets.show', $ticket) }}" class="flex items-center justify-between p-4 hover:bg-white/5 transition border-b border-white/5 last:border-0 group">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-lg group-hover:scale-110 transition">
                                                {{ $ticket->status === \App\Enums\TicketStatus::RESOLVED ? '✅' : '🎫' }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-white text-sm group-hover:text-cyan-400 transition">{{ $ticket->subject }}</div>
                                                <div class="text-xs text-slate-500">{{ $ticket->updated_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $ticket->status->color() }}">
                                            {{ $ticket->status->label() }}
                                        </span>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-slate-500 text-sm">Nenhuma atividade recente.</div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    {{-- 🟡 COLUNA DIREITA: Upsell (Venda para quem já é cliente) --}}
                    <div class="space-y-6">
                        <div class="p-6 rounded-3xl border border-yellow-500/30 bg-gradient-to-b from-yellow-500/10 to-transparent sticky top-24">
                            <h3 class="font-bold text-white mb-2">Upgrade Disponível</h3>
                            <p class="text-sm text-slate-400 mb-6">Sua empresa cresceu? O plano Enterprise oferece suporte 24h e gestor dedicado.</p>
                            
                            <ul class="space-y-3 mb-6">
                                <li class="text-sm text-yellow-200/80 flex gap-2"><span>★</span> Atendimento Telefônico</li>
                                <li class="text-sm text-yellow-200/80 flex gap-2"><span>★</span> SLA de 1 hora</li>
                                <li class="text-sm text-yellow-200/80 flex gap-2"><span>★</span> Auditoria de Segurança</li>
                            </ul>

                            <button class="w-full py-3 rounded-xl bg-yellow-500 text-slate-900 font-bold hover:bg-yellow-400 transition">
                                Ver Detalhes Enterprise
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    {{-- ======================================================================
         3. VISÃO DO VISITANTE (Conversão + Prova Social)
         ====================================================================== --}}
    @else
        <div class="w-full">
            
            {{-- HERO --}}
            <div class="max-w-7xl mx-auto px-6 pt-20 pb-20 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800 border border-slate-700 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-6">
                    🚀 Suporte TI Premium
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-tight">
                    TI que funciona. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Negócio que cresce.</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-10">
                    Tenha um departamento de TI completo por uma fração do custo. Segurança, nuvem e suporte ágil.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#planos" class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/25">
                        Ver Planos
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-4 rounded-xl bg-white/10 text-white font-bold hover:bg-white/20 transition border border-white/5">
                        Falar com Especialista
                    </a>
                </div>
            </div>

            {{-- ⭐ NOVIDADE: LOGOS (Prova Social) --}}
            <div class="border-y border-white/5 bg-slate-900/50 py-12 mb-24">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-8">Empresas que confiam na nossa segurança</p>
                    <div class="flex flex-wrap justify-center gap-12 opacity-40 grayscale hover:grayscale-0 transition duration-500">
                        {{-- Logos simulados com texto (ou svg) --}}
                        <div class="text-xl font-black text-white">TECHCORP</div>
                        <div class="text-xl font-black text-white">NEXUS LOGISTICS</div>
                        <div class="text-xl font-black text-white">GLOBAL FINANCE</div>
                        <div class="text-xl font-black text-white">FUTURE LABS</div>
                    </div>
                </div>
            </div>

            {{-- O QUE FAZEMOS --}}
            <div id="expertise" class="max-w-7xl mx-auto px-6 mb-24">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-white mb-4">Proteção 360º</h2>
                    <p class="text-slate-400">Cobertura completa para sua infraestrutura.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-3xl bg-slate-950 border border-white/5 hover:border-cyan-400/30 transition group">
                        <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 flex items-center justify-center text-3xl mb-6 text-cyan-400 group-hover:scale-110 transition">🛡️</div>
                        <h3 class="text-xl font-bold text-white mb-3">Cibersegurança</h3>
                        <p class="text-slate-400 text-sm">Monitoramento de ameaças em tempo real e proteção Zero-Trust.</p>
                    </div>
                    
                    <div class="p-8 rounded-3xl bg-slate-950 border border-white/5 hover:border-indigo-400/30 transition group">
                        <div class="h-14 w-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-3xl mb-6 text-indigo-400 group-hover:scale-110 transition">☁️</div>
                        <h3 class="text-xl font-bold text-white mb-3">Cloud & DevOps</h3>
                        <p class="text-slate-400 text-sm">Gestão de servidores AWS/Azure com backups automáticos.</p>
                    </div>

                    <div class="p-8 rounded-3xl bg-slate-950 border border-white/5 hover:border-emerald-400/30 transition group">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-3xl mb-6 text-emerald-400 group-hover:scale-110 transition">🚀</div>
                        <h3 class="text-xl font-bold text-white mb-3">Alta Performance</h3>
                        <p class="text-slate-400 text-sm">Otimização de redes e hardware para máxima produtividade.</p>
                    </div>
                </div>
            </div>

            {{-- PLANOS --}}
            <div id="planos" class="py-24 border-t border-white/5 bg-slate-900/30">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-black text-white mb-4">Planos Flexíveis</h2>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8 items-end">
                        {{-- Starter --}}
                        <div class="p-8 rounded-3xl bg-slate-950 border border-white/10">
                            <div class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-4">Starter</div>
                            <div class="text-3xl font-black text-white mb-6">R$ 199<span class="text-lg text-slate-500 font-normal">/mês</span></div>
                            <ul class="space-y-4 mb-8 text-sm text-slate-400">
                                <li>✓ Suporte Email</li>
                                <li>✓ Horário Comercial</li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full py-3 rounded-xl border border-white/20 text-white font-bold text-center hover:bg-white/5 transition">Começar</a>
                        </div>

                        {{-- Business --}}
                        <div class="p-8 rounded-3xl bg-slate-900 border border-indigo-500 relative transform md:-translate-y-4 shadow-2xl shadow-indigo-500/10">
                            <div class="absolute top-0 inset-x-0 h-1 bg-indigo-500"></div>
                            <div class="text-indigo-400 font-bold text-xs uppercase tracking-widest mb-4">Recomendado</div>
                            <div class="text-4xl font-black text-white mb-6">R$ 499<span class="text-lg text-slate-500 font-normal">/mês</span></div>
                            <ul class="space-y-4 mb-8 text-sm text-slate-300">
                                <li>✓ Suporte Prioritário</li>
                                <li>✓ Acesso Remoto</li>
                                <li>✓ Backup Cloud</li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full py-3 rounded-xl bg-indigo-600 text-white font-bold text-center hover:bg-indigo-500 transition">Assinar</a>
                        </div>

                        {{-- Enterprise --}}
                        <div class="p-8 rounded-3xl bg-slate-950 border border-white/10">
                            <div class="text-white font-bold text-xs uppercase tracking-widest mb-4">Enterprise</div>
                            <div class="text-3xl font-black text-white mb-6">Consulte</div>
                            <ul class="space-y-4 mb-8 text-sm text-slate-400">
                                <li>✓ Atendimento 24/7</li>
                                <li>✓ Gestor Dedicado</li>
                            </ul>
                            <a href="{{ route('contact') }}" class="block w-full py-3 rounded-xl border border-white/20 text-white font-bold text-center hover:bg-white/5 transition">Cotar</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endauth
</div>
@endsection