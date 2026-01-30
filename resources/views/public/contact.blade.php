@extends('layouts.site')

{{-- Define o Título da Aba --}}
@section('title', 'Contato - Suporte TI')

{{-- Define a descrição para o Google/WhatsApp --}}
@section('meta_description', 'Precisa de suporte técnico? Entre em contato agora. Atendimento rápido via WhatsApp, E-mail ou Telefone.')


@section('content')
<div class="relative py-20 min-h-screen flex items-center">
    
    {{-- Background --}}
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-white/5 to-transparent pointer-events-none hidden lg:block"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-start">
            
            {{-- Lado Esquerdo: Infos --}}
            <div class="space-y-8">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-6">
                        Vamos resolver <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">o seu problema?</span>
                    </h1>
                    <p class="text-lg text-slate-400 leading-relaxed">
                        Preencha o formulário ou utilize um de nossos canais diretos. Nossa equipa está pronta para agir.
                    </p>
                </div>

                <div class="space-y-4">
                    {{-- Card Email --}}
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xl">📧</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">E-mail</div>
                            <div class="text-white font-medium">contato@suporteti.com</div>
                        </div>
                    </div>

                    {{-- Card WhatsApp --}}
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center text-green-400 text-xl">📱</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">WhatsApp / Telefone</div>
                            <div class="text-white font-medium">(21) 99999-9999</div>
                        </div>
                    </div>

                    {{-- Card Endereço --}}
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center text-cyan-400 text-xl">📍</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Localização</div>
                            <div class="text-white font-medium">Seropédica, RJ</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lado Direito: Formulário --}}
            <div class="bg-slate-900/80 border border-white/10 rounded-[2.5rem] p-8 shadow-2xl backdrop-blur-xl relative">
                {{-- Decorative border glow --}}
                <div class="absolute inset-0 rounded-[2.5rem] border border-white/10 pointer-events-none"></div>

                {{-- Feedback de Sucesso --}}
@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-3 text-emerald-400 animate-fade-in">
        <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
@endif

{{-- O Formulário Ativo --}}
<form action="{{ route('contact.submit') }}" method="POST" class="space-y-5 relative z-10">
    @csrf {{-- ⚠️ OBRIGATÓRIO PARA FUNCIONAR --}}
    
    <div class="grid md:grid-cols-2 gap-5">
        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-400 uppercase ml-1">Nome</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" 
                   placeholder="Seu nome" required>
            @error('name') <span class="text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
        </div>
        <div class="space-y-1">
            <label class="text-xs font-bold text-slate-400 uppercase ml-1">Telefone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" 
                   class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" 
                   placeholder="(00) 00000-0000">
        </div>
    </div>

    <div class="space-y-1">
        <label class="text-xs font-bold text-slate-400 uppercase ml-1">E-mail</label>
        <input type="email" name="email" value="{{ old('email') }}" 
               class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" 
               placeholder="seu@email.com" required>
        @error('email') <span class="text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs font-bold text-slate-400 uppercase ml-1">Mensagem</label>
        <textarea name="message" rows="4" 
                  class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition resize-none" 
                  placeholder="Como podemos ajudar?" required>{{ old('message') }}</textarea>
        @error('message') <span class="text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold shadow-lg shadow-cyan-500/20 hover:scale-[1.02] transition">
        Enviar Mensagem 
    </button>
</form>
            </div>

        </div>
    </div>
</div>
@endsection