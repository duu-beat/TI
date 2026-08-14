@extends('layouts.site')

@section('title', 'Muitas solicitações')

@section('content')
<div class="relative flex min-h-[70vh] items-center justify-center overflow-hidden px-6 text-center">
    <div class="pointer-events-none absolute h-96 w-96 rounded-full bg-violet-500/10 blur-[110px]"></div>
    <div class="relative z-10 max-w-lg">
        <p class="text-[120px] font-black leading-none text-violet-400/20 select-none">429</p>
        <p class="mt-2 text-xs font-bold uppercase tracking-[0.24em] text-violet-400">Ritmo de segurança</p>
        <h1 class="mt-3 text-3xl font-black text-white">Muitas solicitações em pouco tempo.</h1>
        <p class="mt-3 text-slate-400">Para proteger a conta e a plataforma, limitamos temporariamente esta ação. Aguarde alguns instantes antes de tentar novamente.</p>
        <div class="mt-8">
            <a href="{{ route('home') }}" class="rounded-xl bg-violet-500 px-6 py-3 text-sm font-bold text-white transition hover:bg-violet-400">Voltar ao início</a>
        </div>
    </div>
</div>
@endsection
