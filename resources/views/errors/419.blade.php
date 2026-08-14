@extends('layouts.site')

@section('title', 'Sessão expirada')

@section('content')
<div class="relative flex min-h-[70vh] items-center justify-center overflow-hidden px-6 text-center">
    <div class="pointer-events-none absolute h-96 w-96 rounded-full bg-amber-500/10 blur-[110px]"></div>
    <div class="relative z-10 max-w-lg">
        <p class="text-[120px] font-black leading-none text-amber-400/20 select-none">419</p>
        <p class="mt-2 text-xs font-bold uppercase tracking-[0.24em] text-amber-400">Sessão expirada</p>
        <h1 class="mt-3 text-3xl font-black text-white">Por segurança, esta ação expirou.</h1>
        <p class="mt-3 text-slate-400">Atualize a página e tente novamente. Nenhuma alteração foi aplicada enquanto a sessão estava expirada.</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ url()->current() }}" class="rounded-xl bg-amber-400 px-6 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300">Atualizar página</a>
            <a href="{{ route('home') }}" class="rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Voltar ao início</a>
        </div>
    </div>
</div>
@endsection
