@extends('layouts.site')

@section('content')
<div class="relative py-20 overflow-hidden">
    {{-- Luzes de fundo decorativas --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[10%] left-[20%] w-96 h-96 bg-indigo-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[20%] right-[20%] w-96 h-96 bg-cyan-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        {{-- Header --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-cyan-400 uppercase tracking-wider mb-4">
                💡 Base de Conhecimento
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight mb-4">
                Perguntas Frequentes
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                Tire as suas dúvidas rapidamente. Se não encontrar o que procura, a nossa equipa está pronta para ajudar.
            </p>
        </div>

        {{-- Lista de FAQs (Acordeão) --}}
        <div class="space-y-4">
            @forelse($faqs as $faq)
                <div x-data="{ open: false }" 
                     class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md overflow-hidden transition-all duration-300 hover:border-white/20 hover:bg-white/[0.07]">
                    
                    {{-- Pergunta (Botão Clicável) --}}
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left group">
                        <span class="font-semibold text-slate-200 text-lg group-hover:text-white transition">
                            {{ $faq->question }}
                        </span>
                        
                        {{-- Ícone + / - Animado --}}
                        <span class="ml-6 flex h-10 w-10 items-center justify-center rounded-full bg-white/5 border border-white/5 transition-all duration-300 group-hover:border-cyan-500/30"
                              :class="open ? 'rotate-180 bg-cyan-500/20 text-cyan-400 border-cyan-500/50' : 'text-slate-400'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>

                    {{-- Resposta (Expansível) --}}
                    <div x-show="open" 
                         x-collapse 
                         class="border-t border-white/5 bg-slate-950/30 px-6 pb-8 pt-4">
                        <div class="prose prose-invert prose-p:text-slate-400 max-w-none">
                            {{ $faq->answer }}
                        </div>
                        
                        <div class="mt-4 flex items-center gap-2 text-xs text-slate-500">
                            <span>Isso foi útil?</span>
                            <button class="hover:text-cyan-400 transition">👍 Sim</button>
                            <button class="hover:text-red-400 transition">👎 Não</button>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="text-2xl mb-2">🤔</div>
                    <h3 class="text-white font-medium">Ainda não há perguntas</h3>
                    <p class="text-slate-500 text-sm">Volte mais tarde ou entre em contato.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer CTA --}}
        <div class="mt-20 text-center p-8 rounded-3xl border border-white/10 bg-gradient-to-b from-white/5 to-transparent">
            <h3 class="text-xl font-bold text-white mb-2">Ainda precisa de ajuda?</h3>
            <p class="text-slate-400 mb-6">Não encontrou a sua resposta? Abra um chamado e responderemos o mais rápido possível.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('client.tickets.create') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-8 py-3 font-bold text-slate-950 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:brightness-110 transition-all">
                    Abrir Chamado
                </a>
                <a href="{{ route('contact') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-white/5 border border-white/10 px-8 py-3 font-semibold text-white hover:bg-white/10 transition">
                    Entrar em Contato
                </a>
            </div>
        </div>
    </div>
</div>
@endsection