@extends('layouts.site')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6 relative overflow-hidden">
    
    {{-- Luz de fundo --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10">
        <div class="text-[150px] font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-500/20 to-cyan-400/20 leading-none select-none">
            404
        </div>
        
        <h1 class="text-3xl font-bold text-white mt-4">Ops! Página não encontrada.</h1>
        <p class="text-slate-400 mt-2 max-w-md mx-auto">
            Parece que você tentou acessar um link que não existe ou foi movido.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('home') }}" 
               class="px-8 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 font-bold text-slate-950 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:scale-105 transition-all">
                Voltar ao Início
            </a>
            
            <a href="{{ route('contact') }}" 
               class="px-8 py-3 rounded-2xl bg-white/5 border border-white/10 font-semibold text-white hover:bg-white/10 transition">
                Reportar Problema
            </a>
        </div>
    </div>
</div>
@endsection