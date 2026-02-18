@extends('layouts.site')

@section('title', 'FAQ - Suporte TI')
@section('meta_description', 'Tire suas dúvidas sobre nossos serviços de suporte técnico.')

@section('content')
{{-- ✅ WRAPPER ALPINE ADICIONADO --}}
<div class="relative py-24 min-h-screen" 
     x-data="{
         loaded: false,
         active: null,
         toggleItem(index) {
             this.active = this.active === index ? null : index;
         },
         // ... funções de foco mantidas ...
     }"
     x-init="setTimeout(() => loaded = true, 400)"
     @keydown.escape.window="active = null">
    
    {{-- Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[10%] right-[20%] w-[600px] h-[600px] bg-cyan-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[20%] left-[10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        
        {{-- Header com Skeleton --}}
        <div x-show="!loaded" class="text-center mb-16 animate-pulse">
             <div class="h-8 w-32 bg-cyan-500/20 rounded-full mx-auto mb-6"></div>
             <div class="h-16 w-3/4 bg-white/5 rounded-2xl mx-auto mb-6"></div>
             <div class="h-4 w-1/2 bg-white/5 rounded mx-auto"></div>
        </div>

        <div x-show="loaded" style="display: none;" class="text-center mb-16"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-widest mb-6 hover:bg-cyan-500/20 transition cursor-default">
                ❓ Tira-Dúvidas
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-6">
                Perguntas <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Frequentes.</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                As respostas para as dúvidas mais comuns sobre os nossos serviços e processos.
            </p>
        </div>

        {{-- Lista FAQ com Skeleton --}}
        <div x-show="!loaded" class="space-y-4 animate-pulse">
            @for($i=0; $i<5; $i++)
            <div class="h-20 rounded-2xl border border-white/5 bg-slate-900/50"></div>
            @endfor
        </div>

        <div x-show="loaded" style="display: none;" class="space-y-4"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            @forelse($faqs as $index => $faq)
                <div class="rounded-2xl border border-white/10 bg-slate-900/50 hover:bg-slate-900/80 transition overflow-hidden">
                    <button @click="toggleItem({{ $index }})"
                            class="w-full flex items-center justify-between p-6 text-left focus:outline-none group">
                        <span class="text-lg font-bold text-white group-hover:text-cyan-400 transition pr-8">
                            {{ $faq->question }}
                        </span>
                        <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-400 transition-transform duration-300"
                              :class="active === {{ $index }} ? 'rotate-180 bg-cyan-500/20 text-cyan-400' : ''">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="active === {{ $index }}"
                         x-collapse
                         x-cloak
                         class="border-t border-white/5 bg-white/[0.02]">
                        <div class="p-6 pt-2 text-slate-400 leading-relaxed">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 rounded-3xl border border-dashed border-white/10 bg-white/5">
                    <div class="text-4xl mb-4 grayscale opacity-50">🔍</div>
                    <h3 class="text-white font-bold mb-2">Ainda não há perguntas</h3>
                    <p class="text-slate-500 text-sm">Estamos a atualizar a nossa base de conhecimento.</p>
                </div>
            @endforelse
        </div>

        {{-- CTA FINAL --}}
        @unless(auth()->check() && auth()->user()->isAdmin())
            <div class="mt-20 text-center p-10 rounded-3xl border border-white/10 bg-gradient-to-b from-white/5 to-transparent backdrop-blur-md">
                {{-- Conteúdo mantido igual ao original --}}
                <h3 class="text-2xl font-bold text-white mb-3">
                    @auth Precisa de suporte especializado? @else Não encontrou o que procura? @endauth
                </h3>
                <p class="text-slate-400 mb-8 max-w-lg mx-auto">
                    @auth
                        Como cliente da Suporte TI, você tem acesso prioritário através do nosso Portal. Utilize-o para abrir e acompanhar os seus chamados com maior agilidade.
                    @else
                        A nossa equipa está pronta para ajudar com dúvidas específicas ou problemas complexos.
                    @endauth
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('client.tickets.create') }}" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold shadow-lg shadow-indigo-500/20 hover:scale-105 transition">
                            Acessar Portal do Cliente
                        </a>
                    @else
                        <a href="{{ route('contact') }}" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold shadow-lg shadow-indigo-500/20 hover:scale-105 transition">
                            Abrir Chamado
                        </a>
                        <a href="{{ route('contact') }}" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition">
                            Fale Conosco
                        </a>
                    @endauth
                </div>
            </div>
        @endunless

    </div>
</div>
@endsection