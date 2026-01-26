@extends('layouts.site')

@section('content')
<div class="relative py-20 min-h-[80vh] flex items-center">
    
    {{-- Background Glow --}}
    <div class="absolute top-1/2 left-3/4 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            
            {{-- Lado Esquerdo: Texto e Infos --}}
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-bold uppercase tracking-widest mb-6">
                    💬 Vamos conversar
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">
                    Tem um problema? <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Nós temos a solução.</span>
                </h1>
                <p class="text-lg text-slate-400 mb-10 leading-relaxed">
                    Sem "tecniquês" complicado, sem sustos no orçamento. Descreva o que está acontecendo e responderemos com o melhor caminho.
                </p>

                <div class="space-y-6">
                    {{-- Info Card 1 --}}
                    <div class="flex items-start gap-4 p-5 rounded-2xl border border-white/5 bg-white/[0.02]">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xl shrink-0">
                            📍
                        </div>
                        <div>
                            <div class="text-white font-bold text-lg">Atendimento Híbrido</div>
                            <p class="text-slate-400 text-sm mt-1">Suporte remoto para todo o Brasil ou presencial (dependendo da sua região).</p>
                        </div>
                    </div>

                    {{-- Info Card 2 --}}
                    <div class="flex items-start gap-4 p-5 rounded-2xl border border-white/5 bg-white/[0.02]">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center text-cyan-400 text-xl shrink-0">
                            ⏰
                        </div>
                        <div>
                            <div class="text-white font-bold text-lg">Horários Flexíveis</div>
                            <p class="text-slate-400 text-sm mt-1">Atendemos de Seg a Sáb. Mande sua mensagem e combinamos o melhor horário.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lado Direito: Formulário Glass --}}
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-3xl blur opacity-20"></div>
                
                <div class="relative rounded-3xl border border-white/10 bg-slate-900/80 backdrop-blur-xl p-8 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-6">Mande uma mensagem</h3>
                    
                    <form class="space-y-5" method="POST" action="#">
                        @csrf

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Seu Nome</label>
                            <input name="name" type="text"
                                class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 placeholder:text-slate-600 focus:border-cyan-400/50 focus:ring-4 focus:ring-cyan-400/10 transition-all outline-none"
                                placeholder="Como prefere ser chamado?" required />
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Seu Email</label>
                            <input name="email" type="email"
                                class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 placeholder:text-slate-600 focus:border-cyan-400/50 focus:ring-4 focus:ring-cyan-400/10 transition-all outline-none"
                                placeholder="exemplo@email.com" required />
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">O Problema</label>
                            <textarea name="message" rows="4"
                                class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 placeholder:text-slate-600 focus:border-cyan-400/50 focus:ring-4 focus:ring-cyan-400/10 transition-all outline-none resize-none"
                                placeholder="Descreva brevemente o que está acontecendo..." required></textarea>
                        </div>

                        <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-4 font-bold text-slate-950 text-lg hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                            Enviar Mensagem 🚀
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection