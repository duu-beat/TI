@extends('layouts.site')

@section('content')
<div class="relative py-20">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[20%] right-[10%] w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        {{-- Header --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-cyan-400 text-xs font-bold uppercase tracking-widest mb-4">
                🚀 O que fazemos
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">
                Soluções completas para <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">sua máquina voar.</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                Do diagnóstico à otimização extrema. Resolvemos problemas de hardware, software e rede com agilidade e transparência.
            </p>
        </div>

        {{-- Grid de Serviços --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $items = [
                    ['icon' => '🔍', 'title' => 'Diagnóstico Avançado', 'desc' => 'Identificação precisa de falhas com relatório técnico claro e sem "tech-ês".'],
                    ['icon' => '🚀', 'title' => 'Formatação & Otimização', 'desc' => 'Instalação limpa (Windows/Linux), drivers atualizados e debloat para performance máxima.'],
                    ['icon' => '🛠', 'title' => 'Upgrade & Montagem', 'desc' => 'Consultoria de peças, montagem profissional, cable management e configuração de BIOS.'],
                    ['icon' => '🔒', 'title' => 'Segurança & Backup', 'desc' => 'Remoção de vírus, configuração de firewall e rotinas de backup automático.'],
                    ['icon' => '📡', 'title' => 'Redes & Wi-Fi', 'desc' => 'Configuração de roteadores, repetidores e otimização de canal para estabilidade.'],
                    ['icon' => '💻', 'title' => 'Suporte Remoto', 'desc' => 'Resolução rápida de problemas de software sem você precisar sair de casa.'],
                ];
            @endphp

            @foreach($items as $it)
                <div class="group p-8 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm hover:bg-white/[0.07] hover:border-cyan-500/30 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900/50 border border-white/10 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:border-cyan-500/50 transition">
                        {{ $it['icon'] }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-cyan-400 transition">{{ $it['title'] }}</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        {{ $it['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- CTA Bottom --}}
        <div class="mt-20 p-10 rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-900/40 to-slate-900/40 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-white/[0.02] bg-[length:20px_20px]"></div>
            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-white mb-4">Não achou o que procura?</h2>
                <p class="text-slate-400 mb-8">Cada caso é um caso. Entre em contato e personalizamos uma solução.</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-2xl bg-white text-slate-900 font-bold hover:bg-cyan-50 transition hover:scale-105">
                    Falar com especialista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection