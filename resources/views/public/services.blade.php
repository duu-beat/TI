@extends('layouts.site')

@section('content')
<div class="relative py-20">
    {{-- Glow --}}
    <div class="absolute top-0 right-0 w-full h-[600px] bg-indigo-900/20 blur-[150px] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        {{-- Header --}}
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
                    ['icon' => '💻', 'title' => 'Suporte Técnico', 'desc' => 'Manutenção de computadores, notebooks e periféricos. Remoção de vírus e otimização.'],
                    ['icon' => '🌐', 'title' => 'Redes & Wi-Fi', 'desc' => 'Configuração de roteadores, cabeamento estruturado e segurança de rede corporativa.'],
                    ['icon' => '☁️', 'title' => 'Cloud & Backup', 'desc' => 'Migração para nuvem, configuração de backups automáticos e recuperação de dados.'],
                    ['icon' => '🔒', 'title' => 'Cibersegurança', 'desc' => 'Firewall, antivírus corporativo e auditoria de vulnerabilidades.'],
                    ['icon' => '🚀', 'title' => 'Sites & Sistemas', 'desc' => 'Desenvolvimento de sites institucionais, lojas virtuais e sistemas web sob medida.'],
                    ['icon' => '📹', 'title' => 'Monitoramento', 'desc' => 'Instalação e configuração de câmeras de segurança e sistemas de DVR.'],
                ];
            @endphp

            @foreach($services as $s)
                <div class="group p-8 rounded-3xl border border-white/10 bg-white/5 hover:bg-slate-800 transition duration-300">
                    <div class="text-4xl mb-6 bg-slate-950 w-16 h-16 flex items-center justify-center rounded-2xl border border-white/5 group-hover:scale-110 transition">
                        {{ $s['icon'] }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-cyan-400 transition">{{ $s['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Secção de Planos (NOVO) --}}
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-white text-center mb-12">Escolha como quer ser atendido</h2>
            
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                {{-- Plano Avulso --}}
                <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/50 hover:border-white/20 transition">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Sob Demanda</div>
                    <h3 class="text-2xl font-bold text-white mb-4">Atendimento Avulso</h3>
                    <p class="text-slate-400 text-sm mb-8 min-h-[40px]">Ideal para problemas pontuais e empresas pequenas.</p>
                    <ul class="space-y-3 text-slate-300 text-sm mb-8">
                        <li class="flex gap-2">✅ Pagamento por hora/visita</li>
                        <li class="flex gap-2">✅ Sem fidelidade</li>
                        <li class="flex gap-2">✅ Atendimento em até 24h</li>
                    </ul>
                    <a href="{{ route('contact') }}" class="block w-full py-3 rounded-xl border border-white/20 text-center text-white font-bold hover:bg-white/10 transition">
                        Solicitar Orçamento
                    </a>
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
                    <a href="{{ route('contact') }}" class="block w-full py-3 rounded-xl bg-cyan-500 text-slate-950 text-center font-bold hover:bg-cyan-400 transition shadow-lg shadow-cyan-500/20">
                        Falar com Consultor
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection