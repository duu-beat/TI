@props([
    'title' => 'Acesso',
    'subtitle' => 'Portal do cliente',
    'headline' => 'Acompanhe seus chamados em um só lugar.',
    'description' => 'Abra solicitações, envie mensagens e receba atualizações do atendimento com segurança.',
    'panelHint' => 'Dica: descreva bem o problema no chamado e mande print quando puder.',
])

{{-- 🔥 SEO INJECTION: Envia os dados para o <head> do guest.blade.php --}}
@section('title', $title . ' · ' . config('app.name', 'Suporte TI'))
@section('meta_description', $description)

<x-guest-layout>
    <div class="w-full max-w-5xl">
        <div class="grid lg:grid-cols-2 rounded-3xl overflow-hidden border border-white/10 bg-slate-900/50 backdrop-blur-xl shadow-2xl relative">
            
            {{-- Background Glows --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
                <div class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-64 h-64 bg-cyan-500/10 rounded-full blur-[80px]"></div>
            </div>

            {{-- Brand panel (Esquerda) --}}
            <div class="relative p-8 lg:p-12 flex flex-col justify-between z-10 border-r border-white/5 bg-slate-950/30">
                
                {{-- Linha Neon no Topo --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-cyan-400 opacity-50"></div>

                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-8 group">
                        <img
                            src="{{ asset('images/logosuporteTI.png') }}"
                            alt="Suporte TI"
                            class="h-10 w-auto object-contain group-hover:scale-105 transition duration-300"
                        >
                        <div class="leading-tight">
                            <div class="text-white font-bold tracking-tight text-xl leading-none group-hover:text-cyan-400 transition">
                                Suporte TI
                            </div>
                            <div class="text-xs text-slate-400 font-medium uppercase tracking-wider mt-0.5">
                                {{ $subtitle }}
                            </div>
                        </div>
                    </a>

                    <h1 class="mt-8 text-3xl md:text-4xl font-extrabold tracking-tight text-white leading-tight">
                        {{ $headline }}
                    </h1>
                    <p class="mt-4 text-lg text-slate-400 leading-relaxed">
                        {{ $description }}
                    </p>

                    <div class="mt-8 space-y-3">
                        {{-- Exibe features específicas ou padrão --}}
                        @if(isset($features))
                            {{ $features }}
                        @else
                            <div class="flex items-center gap-3 text-sm text-slate-300">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400">🚀</div>
                                <span>Atendimento ágil e sem burocracia.</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-300">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400">🔒</div>
                                <span>Ambiente seguro e criptografado.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-12 text-xs font-medium text-slate-500 border-t border-white/5 pt-6">
                    {{ $panelHint }}
                </div>
            </div>

            {{-- Form panel (Direita) --}}
            <div class="relative p-8 lg:p-12 flex flex-col justify-center bg-slate-900/40 z-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $title }}</h2>
                        <div class="text-sm text-slate-400 mt-1">
                            Preencha suas credenciais.
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="p-2 rounded-xl hover:bg-white/5 text-slate-400 hover:text-white transition" title="Voltar ao site">✕</a>
                </div>

                <div class="auth-form-content">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>