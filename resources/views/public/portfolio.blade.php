@extends('layouts.public')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-14">
    <h1 class="text-3xl md:text-4xl font-extrabold text-white">Portfólio</h1>
    <p class="mt-2 text-slate-300 max-w-2xl">
        Alguns exemplos do tipo de solução que eu entrego. Depois você pode trocar por itens do banco.
    </p>

    <div class="mt-10 grid md:grid-cols-3 gap-6">
        @php
            $cases = [
                ['title' => 'PC lento virou foguete', 'desc' => 'Troca pra SSD + otimização e ajuste de inicialização.'],
                ['title' => 'Quedas no Wi-Fi resolvidas', 'desc' => 'Canal, posicionamento, configuração e estabilidade.'],
                ['title' => 'Setup gamer preparado', 'desc' => 'Organização, airflow, drivers e configuração de performance.'],
            ];
        @endphp

        @foreach($cases as $c)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                <div class="text-white font-semibold">{{ $c['title'] }}</div>
                <p class="mt-2 text-slate-300">{{ $c['desc'] }}</p>
                <div class="mt-4 text-xs text-slate-400">Caso demonstrativo</div>
            </div>
        @endforeach
    </div>
</section>
@endsection
