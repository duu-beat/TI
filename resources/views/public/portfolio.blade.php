@extends('layouts.site')

@section('content')
<div class="relative py-20">
    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-4">
                    Resultados Reais
                </h1>
                <p class="text-lg text-slate-400 max-w-xl">
                    Veja como transformamos setups lentos e problemáticos em máquinas de alta performance.
                </p>
            </div>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-white font-semibold hover:bg-white/10 transition">
                Quero um resultado assim →
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $cases = [
                    [
                        'tag' => 'Otimização', 
                        'color' => 'text-cyan-400 bg-cyan-400/10 border-cyan-400/20',
                        'title' => 'PC Lento virou Foguete 🚀', 
                        'desc' => 'Cliente reclamava de inicialização de 5 minutos. Trocamos o HD por SSD NVMe, clonamos o sistema e otimizamos a BIOS. Boot agora em 12 segundos.',
                        'result' => 'Boot 25x mais rápido'
                    ],
                    [
                        'tag' => 'Redes', 
                        'color' => 'text-indigo-400 bg-indigo-400/10 border-indigo-400/20',
                        'title' => 'Wi-Fi em toda a casa 📡', 
                        'desc' => 'Sinal caía no escritório. Mapeamos a interferência, configuramos uma rede Mesh e ajustamos os canais de frequência.',
                        'result' => 'Zero quedas em 3 meses'
                    ],
                    [
                        'tag' => 'Gamer', 
                        'color' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                        'title' => 'Setup Competitivo 🎮', 
                        'desc' => 'Montagem completa com cable management invisível, configuração de curvas de fan para silêncio e overclock seguro na GPU.',
                        'result' => '-15ºC em Full Load'
                    ],
                ];
            @endphp

            @foreach($cases as $c)
                <div class="group flex flex-col h-full rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm overflow-hidden hover:border-white/20 transition-all duration-300">
                    {{-- Header do Card --}}
                    <div class="p-8 pb-0">
                        <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border mb-4 {{ $c['color'] }}">
                            {{ $c['tag'] }}
                        </span>
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-cyan-400 transition">{{ $c['title'] }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            {{ $c['desc'] }}
                        </p>
                    </div>

                    {{-- Spacer --}}
                    <div class="flex-1"></div>

                    {{-- Footer do Card (Resultado) --}}
                    <div class="p-8 pt-6">
                        <div class="p-4 rounded-2xl bg-slate-950/50 border border-white/5 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 text-xs">
                                ✔
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 uppercase font-bold">Resultado</div>
                                <div class="text-sm font-semibold text-white">{{ $c['result'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection