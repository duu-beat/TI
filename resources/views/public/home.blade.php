@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 py-20">
        <div class="max-w-2xl">
            <p class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs text-slate-200">
                Suporte TI para PC, redes e sistemas
            </p>

            <h1 class="mt-6 text-4xl md:text-5xl font-extrabold tracking-tight text-white">
                Seu PC rápido, seguro e pronto pra rodar sem dor de cabeça.
            </h1>

            <p class="mt-5 text-lg text-slate-300">
                Manutenção, otimização, upgrades, suporte remoto e consultoria. Atendimento direto com você, sem enrolação.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('contact') }}"
                   class="inline-flex justify-center rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                    Pedir orçamento
                </a>

                <a href="{{ route('services') }}"
                   class="inline-flex justify-center rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition">
                    Ver serviços
                </a>
            </div>

            <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-white font-semibold">Suporte rápido</div>
                    <div class="text-slate-400 mt-1">Remoto ou presencial</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="text-white font-semibold">Segurança</div>
                    <div class="text-slate-400 mt-1">Backup e proteção</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 col-span-2 sm:col-span-1">
                    <div class="text-white font-semibold">Upgrade</div>
                    <div class="text-slate-400 mt-1">SSD, RAM, setup</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-14">
    <h2 class="text-2xl font-bold text-white">O que eu resolvo pra você</h2>
    <p class="mt-2 text-slate-300 max-w-2xl">Do básico ao avançado, sempre com foco em performance e segurança.</p>

    <div class="mt-8 grid md:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <div class="text-white font-semibold">Otimização</div>
            <p class="mt-2 text-slate-300">Sistema mais leve, inicialização rápida e menos travamentos.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <div class="text-white font-semibold">Manutenção</div>
            <p class="mt-2 text-slate-300">Diagnóstico e correção de erros, limpeza e ajustes finos.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <div class="text-white font-semibold">Redes</div>
            <p class="mt-2 text-slate-300">Wi-Fi estável, cabos, roteador, configuração e performance.</p>
        </div>
    </div>
</section>
@endsection
