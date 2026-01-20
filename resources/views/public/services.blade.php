@extends('layouts.public')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-14">
    <div class="flex items-end justify-between gap-6 flex-wrap">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white">Serviços</h1>
            <p class="mt-2 text-slate-300 max-w-2xl">
                Suporte completo pra deixar seu PC e sua rede do jeito certo: rápidos, estáveis e seguros.
            </p>
        </div>
        <a href="{{ route('contact') }}"
           class="inline-flex items-center rounded-2xl bg-white/10 px-5 py-2.5 font-semibold text-white hover:bg-white/15 transition">
            Pedir orçamento
        </a>
    </div>

    <div class="mt-10 grid md:grid-cols-3 gap-6">
        @php
            $items = [
                ['title' => 'Manutenção e diagnóstico', 'desc' => 'Identificação de falhas, ajustes e correções com relatório claro.'],
                ['title' => 'Formatação e otimização', 'desc' => 'Instalação limpa, drivers, apps essenciais e performance.'],
                ['title' => 'Upgrade e montagem', 'desc' => 'SSD, RAM, GPU, fonte, airflow e configuração do setup.'],
                ['title' => 'Segurança e backup', 'desc' => 'Backup, antivírus, proteção e boas práticas.'],
                ['title' => 'Rede e Wi-Fi', 'desc' => 'Configuração, estabilidade e alcance com melhoria real.'],
                ['title' => 'Suporte remoto', 'desc' => 'Resolução rápida de problemas sem sair de casa.'],
            ];
        @endphp

        @foreach($items as $it)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 hover:bg-white/10 transition">
                <div class="text-white font-semibold">{{ $it['title'] }}</div>
                <p class="mt-2 text-slate-300">{{ $it['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
