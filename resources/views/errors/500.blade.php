@extends('layouts.site')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6 relative overflow-hidden">
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-red-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10">
        <div class="text-[150px] font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500/20 to-orange-400/20 leading-none select-none">
            500
        </div>
        
        <h1 class="text-3xl font-bold text-white mt-4">Erro Interno no Servidor</h1>
        <p class="text-slate-400 mt-2 max-w-md mx-auto">
            Algo deu errado do nosso lado. Já notificamos a equipe técnica e estamos trabalhando nisso.
        </p>

        <div class="mt-8">
            <a href="{{ url()->previous() }}" 
               class="px-8 py-3 rounded-2xl bg-white/10 border border-white/10 font-semibold text-white hover:bg-white/20 transition">
                Tentar Novamente
            </a>
        </div>
    </div>
</div>
@endsection