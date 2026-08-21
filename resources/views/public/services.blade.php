@extends('layouts.site')

@section('title', 'Nossos Serviços - Suporte TI')
@section('meta_description', 'Serviços completos de TI: Manutenção, redes e segurança.')

@section('content')
<div class="relative py-20">
    {{-- Glow --}}
    <div class="absolute top-0 right-0 w-full h-[600px] bg-indigo-900/20 blur-[150px] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        <div>
            
            <div class="text-center mb-20">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-6">
                    Nossas Soluções
                </h1>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                    Do hardware ao software, cobrimos todas as camadas da sua tecnologia.
                </p>
            </div>

            {{-- Grid de Serviços --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-24">
                @php
                    $services = [
                        ['icon' => '💻', 'title' => 'Suporte Técnico', 'desc' => 'Resolução rápida de problemas em computadores, impressoras e servidores.'],
                        ['icon' => '🌐', 'title' => 'Redes e Wi-Fi', 'desc' => 'Instalação e configuração de redes corporativas estáveis e seguras.'],
                        ['icon' => '🔒', 'title' => 'Cibersegurança', 'desc' => 'Proteção contra vírus, ransomware e ataques externos.'],
                        ['icon' => '☁️', 'title' => 'Cloud & Backup', 'desc' => 'Migração para nuvem e rotinas de backup automático.'],
                        ['icon' => '⚙️', 'title' => 'Consultoria TI', 'desc' => 'Planejamento estratégico para modernizar sua empresa.'],
                        ['icon' => '🔧', 'title' => 'Manutenção', 'desc' => 'Limpeza, upgrade e reparo de hardware especializado.'],
                    ];
                @endphp

                @foreach($services as $service)
                    <div class="p-8 rounded-3xl border border-white/5 bg-slate-900/50 hover:bg-white/5 hover:border-white/10 transition group">
                        <div class="text-4xl mb-4 group-hover:scale-110 transition duration-300">{{ $service['icon'] }}</div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $service['title'] }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- 🔥 LÓGICA DE EXIBIÇÃO: Esconde tabela de preços para Admin E Master --}}
            @unless(auth()->check() && auth()->user()->isAdmin())
            
                {{-- Tabela Comparativa (CTA) --}}
                <div class="max-w-4xl mx-auto">
                    <div class="rounded-[2.5rem] bg-slate-900 border border-white/10 overflow-hidden shadow-2xl">
                        <div class="grid md:grid-cols-2">
                            
                            {{-- Plano Avulso --}}
                            <div class="p-8 md:p-12 border-b md:border-b-0 md:border-r border-white/10">
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Avulso</div>
                                <h3 class="text-2xl font-bold text-white mb-4">Chamado Único</h3>
                                <p class="text-slate-400 text-sm mb-8 min-h-[40px]">Ideal para problemas pontuais sem compromisso.</p>
                                <ul class="space-y-3 text-slate-400 text-sm mb-8">
                                    <li class="flex gap-2">✅ Pagamento por hora</li>
                                    <li class="flex gap-2">✅ Atendimento remoto ou local</li>
                                    <li class="flex gap-2 text-slate-600">❌ Sem prioridade no SLA</li>
                                </ul>
                                
                                {{-- BOTÃO LÓGICO AVULSO --}}
                                @auth
                                    {{-- Como o admin/master estão ocultos, só cliente vê isso --}}
                                    <a href="{{ route('client.tickets.create') }}" class="block w-full py-3 rounded-xl bg-white/10 text-white font-bold text-center hover:bg-white/20 transition">Abrir no Portal</a>
                                @else
                                    <a href="{{ route('contact') }}" class="block w-full py-3 rounded-xl bg-white/10 text-white font-bold text-center hover:bg-white/20 transition">Solicitar Orçamento</a>
                                @endauth
                            </div>

                            {{-- Plano Mensal --}}
                            <div class="p-8 rounded-3xl border border-cyan-500/30 bg-gradient-to-b from-cyan-900/20 to-slate-900/50 relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-cyan-500 text-slate-950 text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase">Recomendado</div>
                                <div class="text-xs font-bold text-cyan-400 uppercase tracking-widest mb-4">Contrato</div>
                                <h3 class="text-2xl font-bold text-white mb-4">Suporte Mensal</h3>
                                <p class="text-slate-400 text-sm mb-8 min-h-[40px]">Segurança total e prioridade para o seu negócio.</p>
                                <ul class="space-y-3 text-slate-300 text-sm mb-8">
                                    <li class="flex gap-2">✅ Valor fixo mensal</li>
                                    <li class="flex gap-2">✅ <strong>Prioridade Alta</strong> no SLA</li>
                                    <li class="flex gap-2">✅ Monitoramento preventivo</li>
                                </ul>
                                
                                {{-- BOTÃO LÓGICO MENSAL --}}
                                @auth
                                    <a href="{{ route('client.tickets.create') }}" class="block w-full py-3 rounded-xl bg-cyan-500 text-slate-950 text-center font-bold hover:bg-cyan-400 transition">Contratar via Portal</a>
                                @else
                                    <a href="{{ route('contact') }}" class="block w-full py-3 rounded-xl bg-cyan-500 text-slate-950 text-center font-bold hover:bg-cyan-400 transition">Solicitar Proposta</a>
                                @endauth
                            </div>

                        </div>
                    </div>
                </div>

            @endunless 
        </div>

    </div>
</div>
@endsection