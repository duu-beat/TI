@props([
    'title' => 'Acesso',
    'subtitle' => 'Portal do cliente',
    'headline' => 'Acompanhe seus chamados em um só lugar.',
    'description' => 'Abra solicitações, envie mensagens e receba atualizações do atendimento com segurança.',
    'panelHint' => 'Dica: descreva bem o problema no chamado e mande print quando puder.',
])

<x-guest-layout>
    <div class="w-full max-w-5xl">
        <div class="grid lg:grid-cols-2 rounded-3xl overflow-hidden border border-white/10 bg-white/5 shadow-sm">

            {{-- Brand panel --}}
            <div class="relative p-8 lg:p-10 bg-slate-950">
                <div class="absolute inset-0">
                    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>
                    <div class="absolute inset-0 bg-gradient-to-b from-white/0 to-white/5"></div>
                </div>

                <div class="relative">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <img
                            src="{{ asset('images/logosuporteTI.png') }}"
                            alt="Suporte TI"
                            class="h-11 w-auto object-contain"
                        >
                        <div class="leading-tight">
                            <div class="text-white font-extrabold tracking-tight text-lg leading-none">
                                Suporte TI
                            </div>
                            <div class="text-xs text-slate-300 leading-snug">
                                {{ $subtitle }}
                            </div>
                        </div>
                    </a>

                    <h1 class="mt-10 text-3xl font-extrabold tracking-tight text-white">
                        {{ $headline }}
                    </h1>
                    <p class="mt-3 text-slate-300">
                        {{ $description }}
                    </p>

                    <div class="mt-8 space-y-3 text-sm text-slate-200">
                        <div class="flex items-start gap-3">
                            <div class="mt-1.5 h-2 w-2 rounded-full bg-cyan-400"></div>
                            <p><span class="font-semibold text-white">Histórico completo</span> de mensagens e status.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1.5 h-2 w-2 rounded-full bg-indigo-400"></div>
                            <p><span class="font-semibold text-white">Atendimento rápido</span> remoto ou presencial.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1.5 h-2 w-2 rounded-full bg-emerald-400"></div>
                            <p><span class="font-semibold text-white">Foco em performance</span> e segurança.</p>
                        </div>
                    </div>

                    <div class="mt-10 text-xs text-slate-400">
                        {{ $panelHint }}
                    </div>
                </div>
            </div>

            {{-- Form panel --}}
            <div class="p-8 lg:p-10 bg-slate-950/40">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-white font-bold text-xl">{{ $title }}</div>

                        @isset($slotSubtitle)
                            <div class="text-sm text-slate-400">{{ $slotSubtitle }}</div>
                        @else
                            <div class="text-sm text-slate-400">Conclua a etapa para continuar.</div>
                        @endisset
                    </div>

                    <a href="{{ route('home') }}" class="text-sm text-slate-300 hover:text-white underline">
                        Voltar ao site
                    </a>
                </div>

                <div class="mt-6">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
