@extends('layouts.site')

{{-- SEO do Portfólio --}}
@section('title', 'Portfólio - Suporte TI')
@section('meta_description', 'Veja como a Suporte TI ajudou empresas a otimizar sua infraestrutura. Cases reais de otimização, segurança, redes e montagem de computadores.')

@section('content')
<div class="relative py-24 min-h-screen">
    
    {{-- Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[10%] left-[20%] w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[10%] right-[10%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        {{-- HERO SECTION --}}
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-bold uppercase tracking-widest mb-6 hover:bg-purple-500/20 transition cursor-default">
                💼 Nossos Cases
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-6">
                Resultados que <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">falam por si.</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                Não vendemos apenas suporte, entregamos transformação. Veja como ajudámos outras empresas a voar.
            </p>
        </div>

        {{-- GRID DE CASOS --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24">
            @php
                $cases = [
                    [
                        'tag' => 'Otimização', 
                        'icon' => '🚀',
                        'title' => 'PC Gamer Renascido', 
                        'desc' => 'Cliente sofria com telas azuis e lentidão em jogos pesados. Diagnóstico revelou superaquecimento e drivers conflitantes.',
                        'result' => 'FPS dobrou e temperatura caiu 20°C.',
                        'style' => [
                            'bg' => 'bg-cyan-500/10',
                            'border' => 'border-cyan-500/20',
                            'text' => 'text-cyan-400',
                            'hover_text' => 'group-hover:text-cyan-400',
                            'gradient' => 'from-cyan-500',
                        ]
                    ],
                    [
                        'tag' => 'Infraestrutura', 
                        'icon' => '🏢',
                        'title' => 'Escritório Conectado', 
                        'desc' => 'Empresa de advocacia com Wi-Fi instável. Implementação de rede Mesh corporativa e servidor de arquivos seguro.',
                        'result' => 'Conexão 100% estável e backup automático.',
                        'style' => [
                            'bg' => 'bg-indigo-500/10',
                            'border' => 'border-indigo-500/20',
                            'text' => 'text-indigo-400',
                            'hover_text' => 'group-hover:text-indigo-400',
                            'gradient' => 'from-indigo-500',
                        ]
                    ],
                    [
                        'tag' => 'Segurança', 
                        'icon' => '🛡️',
                        'title' => 'Remoção de Ransomware', 
                        'desc' => 'Pequeno negócio atacado por vírus que encriptou dados. Isolamento da rede e recuperação via "Shadow Copy".',
                        'result' => 'Dados recuperados sem pagar resgate.',
                        'style' => [
                            'bg' => 'bg-red-500/10',
                            'border' => 'border-red-500/20',
                            'text' => 'text-red-400',
                            'hover_text' => 'group-hover:text-red-400',
                            'gradient' => 'from-red-500',
                        ]
                    ],
                    [
                        'tag' => 'Montagem', 
                        'icon' => '🔧',
                        'title' => 'Setup de Edição 4K', 
                        'desc' => 'Consultoria e montagem de workstation para editor de vídeo. Foco em velocidade de renderização e armazenamento.',
                        'result' => 'Renderização 3x mais rápida.',
                        'style' => [
                            'bg' => 'bg-emerald-500/10',
                            'border' => 'border-emerald-500/20',
                            'text' => 'text-emerald-400',
                            'hover_text' => 'group-hover:text-emerald-400',
                            'gradient' => 'from-emerald-500',
                        ]
                    ],
                    [
                        'tag' => 'Web', 
                        'icon' => '🌐',
                        'title' => 'Site Institucional', 
                        'desc' => 'Clínica médica sem presença digital. Criação de site moderno, rápido e otimizado para agendamentos.',
                        'result' => '+40% em agendamentos no 1º mês.',
                        'style' => [
                            'bg' => 'bg-pink-500/10',
                            'border' => 'border-pink-500/20',
                            'text' => 'text-pink-400',
                            'hover_text' => 'group-hover:text-pink-400',
                            'gradient' => 'from-pink-500',
                        ]
                    ],
                    [
                        'tag' => 'Manutenção', 
                        'icon' => '🧹',
                        'title' => 'Limpeza Preventiva', 
                        'desc' => 'Parque de 20 máquinas de um Call Center. Limpeza física, troca de pasta térmica e organização de cabos.',
                        'result' => 'Redução drástica de ruído e falhas.',
                        'style' => [
                            'bg' => 'bg-orange-500/10',
                            'border' => 'border-orange-500/20',
                            'text' => 'text-orange-400',
                            'hover_text' => 'group-hover:text-orange-400',
                            'gradient' => 'from-orange-500',
                        ]
                    ],
                ];
            @endphp

            @foreach($cases as $c)
                <div class="group relative p-8 rounded-3xl border border-white/10 bg-slate-900/50 hover:bg-slate-800/80 transition duration-300 flex flex-col h-full overflow-hidden">
                    {{-- Decorative Line --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r {{ $c['style']['gradient'] }} to-transparent opacity-0 group-hover:opacity-100 transition"></div>

                    {{-- Header do Card --}}
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 rounded-2xl {{ $c['style']['bg'] }} flex items-center justify-center text-2xl {{ $c['style']['border'] }} border group-hover:scale-110 transition">
                            {{ $c['icon'] }}
                        </div>
                        <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $c['style']['bg'] }} {{ $c['style']['text'] }} border {{ $c['style']['border'] }}">
                            {{ $c['tag'] }}
                        </span>
                    </div>

                    {{-- Conteúdo --}}
                    <h3 class="text-xl font-bold text-white mb-3 {{ $c['style']['hover_text'] }} transition">{{ $c['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6 flex-grow">
                        {{ $c['desc'] }}
                    </p>

                    {{-- Footer do Card (Resultado) --}}
                    <div class="mt-auto p-4 rounded-xl bg-white/5 border border-white/5 flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 text-xs shrink-0">
                            ✔
                        </div>
                        <div class="text-xs font-semibold text-white">
                            {{ $c['result'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA FINAL COM LÓGICA INTELIGENTE --}}
        {{-- ESCONDIDO PARA ADMIN E MASTER --}}
        @unless(auth()->check() && auth()->user()->isAdmin())
            <div class="text-center">
                
                @auth
                    {{-- CLIENTE (Visão Formal e Direta) --}}
                    <h2 class="text-2xl font-bold text-white mb-3">Expanda a sua infraestrutura conosco.</h2>
                    <p class="text-slate-400 mb-8 max-w-xl mx-auto">
                        Identificou uma oportunidade de melhoria para o seu negócio? Abra um chamado específico para projetos e receba atendimento prioritário.
                    </p>
                    
                    <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-bold hover:scale-105 transition shadow-[0_0_20px_-5px_rgba(168,85,247,0.5)]">
                        Iniciar Novo Projeto
                    </a>
                @else
                    {{-- VISITANTE (Visão de Vendas) --}}
                    <h2 class="text-2xl font-bold text-white mb-6">Quer um resultado assim?</h2>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-white text-slate-900 font-bold hover:bg-slate-200 transition shadow-[0_0_20px_-5px_rgba(255,255,255,0.3)]">
                        Solicitar Orçamento
                    </a>
                @endauth

            </div>
        @endunless

    </div>
</div>
@endsection