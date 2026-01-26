@extends('layouts.site')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6 relative overflow-hidden">
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-yellow-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10">
        <div class="text-6xl mb-4">⛔</div>
        
        <h1 class="text-3xl font-bold text-white mt-4">Acesso Negado</h1>
        <p class="text-slate-400 mt-2 max-w-md mx-auto">
            Você não tem permissão para acessar esta área. Se acha que isso é um erro, contate o administrador.
        </p>

        <div class="mt-8">
            <a href="{{ route('home') }}" 
               class="px-8 py-3 rounded-2xl bg-gradient-to-r from-yellow-500 to-orange-400 font-bold text-slate-950 hover:shadow-[0_0_20px_rgba(234,179,8,0.4)] transition-all">
                Voltar para Segurança
            </a>
        </div>
    </div>
</div>
@endsection