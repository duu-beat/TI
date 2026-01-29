@extends('layouts.site')

@section('content')

{{-- ======================================================================
     LÓGICA DE DADOS (Carrega dados básicos dependendo do user)
     ====================================================================== --}}
@auth
    @php
        $user = auth()->user();
        
        // Dados específicos para ADMIN
        if ($user->isAdmin()) {
            $urgentTickets = \App\Models\Ticket::where('priority', \App\Enums\TicketPriority::HIGH)
                ->whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS])
                ->count();
                
            $newTickets = \App\Models\Ticket::where('status', \App\Enums\TicketStatus::NEW)->count();
        } 
        // Dados específicos para CLIENTE
        else {
            $activeTicket = $user->tickets()
                ->whereIn('status', [\App\Enums\TicketStatus::NEW, \App\Enums\TicketStatus::IN_PROGRESS])
                ->latest()
                ->first();
                
            $pendingResponse = $user->tickets()
                ->where('status', \App\Enums\TicketStatus::WAITING_CLIENT)
                ->count();
        }
    @endphp
@endauth

<div class="relative min-h-screen flex flex-col justify-center">
    
    {{-- Background Geral --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[20%] left-[10%] w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-[120px]"></div>
    </div>

    {{-- ======================================================================
         VISÃO DO ADMINISTRADOR (Modo Comando)
         ====================================================================== --}}
    @auth
        @if($user->isAdmin())
            <div class="relative z-10 max-w-5xl mx-auto px-6 w-full py-20">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold uppercase tracking-widest mb-4">
                        🛡️ Área Administrativa
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4">
                        Painel de Comando
                    </h1>
                    <p class="text-slate-400">Visão operacional rápida do sistema.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Card Urgência --}}
                    <div class="p-8 rounded-3xl border border-red-500/30 bg-gradient-to-br from-red-900/20 to-slate-900/50 relative overflow-hidden group hover:border-red-500/50 transition">
                        <div class="absolute right-0 top-0 p-6 opacity-10 text-9xl font-black text-red-500 pointer-events-none">!</div>
                        <h3 class="text-xl font-bold text-white mb-2">Alta Prioridade</h3>
                        <div class="text-4xl font-black text-red-400 mb-2">{{ $urgentTickets }}</div>
                        <p class="text-red-200/60 text-sm mb-6">Chamados críticos aguardando ação.</p>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-red-500 hover:bg-red-600 px-6 py-3 rounded-xl transition">
                            Resolver Agora →
                        </a>
                    </div>

                    {{-- Card Novos --}}
                    <div class="p-8 rounded-3xl border border-indigo-500/30 bg-gradient-to-br from-indigo-900/20 to-slate-900/50 relative overflow-hidden group hover:border-indigo-500/50 transition">
                        <div class="absolute right-0 top-0 p-6 opacity-10 text-9xl font-black text-indigo-500 pointer-events-none">#</div>
                        <h3 class="text-xl font-bold text-white mb-2">Novos Tickets</h3>
                        <div class="text-4xl font-black text-indigo-400 mb-2">{{ $newTickets }}</div>
                        <p class="text-indigo-200/60 text-sm mb-6">Solicitações recentes na fila.</p>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-indigo-500 hover:bg-indigo-600 px-6 py-3 rounded-xl transition">
                            Ir para Dashboard →
                        </a>
                    </div>
                </div>
            </div>
        
        {{-- ======================================================================
             VISÃO DO CLIENTE (Modo Concierge)
             ====================================================================== --}}
        @else
            <div class="relative z-10 max-w-4xl mx-auto px-6 w-full py-20">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-12">
                    <div>
                        <h1 class="text-4xl font-black text-white tracking-tight mb-2">
                            Olá, {{ explode(' ', $user->name)[0] }}! 👋
                        </h1>
                        <p class="text-slate-400">Como podemos ajudar você hoje?</p>
                    </div>
                    
                    {{-- Botão Principal --}}
                    <a href="{{ route('client.tickets.create') }}" class="group relative px-8 py-4 bg-white text-slate-900 rounded-2xl font-bold text-lg shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] hover:shadow-[0_0_60px_-15px_rgba(255,255,255,0.5)] hover:scale-105 transition duration-300">
                        <span class="relative z-10 flex items-center gap-2">
                            <span>✨</span> Abrir Novo Chamado
                        </span>
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-cyan-500 opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    </a>
                </div>

                {{-- Status Grid --}}
                <div class="grid gap-6">
                    
                    {{-- Alerta de Pendência (Se houver) --}}
                    @if($pendingResponse > 0)
                        <a href="{{ route('client.tickets.index', ['status' => 'waiting_client']) }}" class="flex items-center gap-4 p-5 rounded-2xl border border-yellow-500/30 bg-yellow-500/10 hover:bg-yellow-500/20 transition group">
                            <div class="h-12 w-12 rounded-full bg-yellow-500 text-slate-900 flex items-center justify-center text-xl font-bold animate-pulse">!</div>
                            <div>
                                <h3 class="font-bold text-white">Aguardando sua resposta</h3>
                                <p class="text-sm text-yellow-200/70">O suporte respondeu a {{ $pendingResponse }} chamado(s). Clique para ver.</p>
                            </div>
                            <div class="ml-auto text-yellow-400 group-hover:translate-x-1 transition">→</div>
                        </a>
                    @endif

                    {{-- Último Chamado Ativo --}}
                    @if($activeTicket)
                        <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/60 backdrop-blur-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Seu chamado atual</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $activeTicket->status->color() }}">
                                    {{ $activeTicket->status->label() }}
                                </span>
                            </div>
                            
                            <h2 class="text-2xl font-bold text-white mb-2 truncate">{{ $activeTicket->subject }}</h2>
                            <p class="text-slate-400 text-sm mb-6 line-clamp-2">{{ $activeTicket->description }}</p>
                            
                            <div class="flex items-center gap-4 border-t border-white/5 pt-6">
                                <a href="{{ route('client.tickets.show', $activeTicket) }}" class="text-cyan-400 font-bold hover:text-cyan-300 text-sm">
                                    Ver detalhes e interagir
                                </a>
                                <span class="text-slate-600">•</span>
                                <span class="text-slate-500 text-xs">Atualizado {{ $activeTicket->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @else
                        {{-- Estado "Tudo Limpo" --}}
                        <div class="p-10 rounded-3xl border border-dashed border-white/10 bg-white/5 text-center">
                            <div class="text-4xl mb-4 opacity-50">☕</div>
                            <h3 class="text-lg font-bold text-white mb-2">Tudo tranquilo por aqui</h3>
                            <p class="text-slate-400 text-sm">Você não tem chamados em aberto no momento.</p>
                            <div class="mt-6">
                                <a href="{{ route('client.dashboard') }}" class="text-sm text-indigo-400 hover:text-white transition">Ir para meu painel completo</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    {{-- ======================================================================
         VISÃO DO VISITANTE (Landing Page de Venda)
         ====================================================================== --}}
    @else
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 text-center">
            
            {{-- Hero --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-8 hover:bg-indigo-500/20 transition cursor-default">
                🚀 Suporte TI Profissional
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8 leading-tight">
                Seu negócio não pode <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">parar de crescer.</span>
            </h1>
            
            <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-12 leading-relaxed">
                Deixe a tecnologia connosco. Suporte técnico ágil, segurança de dados e infraestrutura para empresas que exigem alta performance.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-white text-slate-900 font-bold hover:bg-slate-200 transition shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] transform hover:-translate-y-1">
                    Área do Cliente
                </a>
                <a href="{{ route('services') }}" class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition backdrop-blur-md">
                    Ver Serviços
                </a>
            </div>

            {{-- Tech Stack Strip --}}
            <div class="mt-24 pt-12 border-t border-white/5">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-8">Tecnologia de ponta</p>
                <div class="flex justify-center gap-8 md:gap-16 opacity-40 grayscale hover:grayscale-0 transition duration-500">
                    {{-- Ícones simples (texto ou svg) --}}
                    <span class="text-xl font-bold text-white">LARAVEL</span>
                    <span class="text-xl font-bold text-white">VUE.JS</span>
                    <span class="text-xl font-bold text-white">AWS</span>
                    <span class="text-xl font-bold text-white">CLOUDFLARE</span>
                </div>
            </div>
        </div>
    @endauth

</div>
@endsection