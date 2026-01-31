@extends('layouts.site')

{{-- Define o Título da Aba --}}
@section('title', 'Contato - Suporte TI')
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
                    {{-- Cards de Contato (Email, Zap, Endereço) --}}
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xl">📧</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">E-mail</div>
                            <div class="text-white font-medium">contato@suporteti.com</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center text-green-400 text-xl">📱</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">WhatsApp / Telefone</div>
                            <div class="text-white font-medium">(21) 99999-9999</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center text-cyan-400 text-xl">📍</div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Localização</div>
                            <div class="text-white font-medium">Seropédica, RJ</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lado Direito: Lógica de Exibição --}}
            <div class="bg-slate-900/80 border border-white/10 rounded-[2.5rem] p-8 shadow-2xl backdrop-blur-xl relative">
                {{-- Decorative border glow --}}
                <div class="absolute inset-0 rounded-[2.5rem] border border-white/10 pointer-events-none"></div>

                @auth
                    @if(auth()->user()->role === 'admin')
                        {{-- VISÃO DO ADMIN --}}
                        <div class="flex flex-col items-center justify-center text-center h-full min-h-[400px] space-y-6">
                            <div class="w-20 h-20 rounded-full bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white mb-2">Painel Administrativo</h3>
                                <p class="text-slate-400 max-w-xs mx-auto text-sm">
                                    Administradores devem gerenciar chamados pelo painel.
                                </p>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-xl bg-indigo-500 hover:bg-indigo-400 text-white font-bold transition shadow-lg shadow-indigo-500/20">
                                Ir para Dashboard
                            </a>
                        </div>

                    @else
                        {{-- VISÃO DO CLIENTE --}}
                        <div class="flex flex-col items-center justify-center text-center h-full min-h-[400px] space-y-6">
                            <div class="w-20 h-20 rounded-full bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                                <svg class="w-10 h-10 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white mb-2">Já é Cliente?</h3>
                                <p class="text-slate-400 max-w-xs mx-auto text-sm">
                                    Para maior agilidade e histórico, abra seu chamado diretamente pelo Portal do Cliente.
                                </p>
                            </div>
                            <a href="{{ route('client.tickets.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold transition hover:scale-105 shadow-lg shadow-cyan-500/20">
                                Abrir Chamado no Portal
                            </a>
                        </div>
                    @endif

                @else
                    {{-- VISÃO DO VISITANTE (Formulário Ativo) --}}
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-3 text-emerald-400 animate-fade-in">
                            <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="font-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5 relative z-10">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Nome</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" placeholder="Seu nome" required>
                                @error('name') <span class="text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Telefone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" placeholder="(00) 00000-0000">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase ml-1">E-mail</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" placeholder="seu@email.com" required>
                            @error('email') <span class="text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase ml-1">Mensagem</label>
                            <textarea name="message" rows="4" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition resize-none" placeholder="Como podemos ajudar?" required>{{ old('message') }}</textarea>
                            @error('message') <span class="text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-bold shadow-lg shadow-cyan-500/20 hover:scale-[1.02] transition">
                            Enviar Mensagem 
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </div>
</div>
@endsection