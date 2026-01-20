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
                            <div class="text-white font-extrabold tracking-tight text-lg leading-none">Suporte TI</div>
                            <div class="text-xs text-slate-300 leading-snug">Portal do cliente</div>
                        </div>
                    </a>

                    <h1 class="mt-10 text-3xl font-extrabold tracking-tight text-white">
                        Acompanhe seus chamados em um só lugar.
                    </h1>
                    <p class="mt-3 text-slate-300">
                        Abra solicitações, envie mensagens e receba atualizações do atendimento com segurança.
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
                        Dica: descreva bem o problema no chamado e mande print quando puder.
                    </div>
                </div>
            </div>

            {{-- Form panel --}}
            <div class="p-8 lg:p-10 bg-slate-950/40">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-white font-bold text-xl">Entrar</div>
                        <div class="text-sm text-slate-400">Acesse sua conta para continuar.</div>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm text-slate-300 hover:text-white underline">
                        Voltar ao site
                    </a>
                </div>

                <x-validation-errors class="mt-6 mb-4 text-red-200" />

                @session('status')
                    <div class="mb-4 text-sm text-emerald-200">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="text-sm text-slate-300">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                      focus:border-cyan-400/60 focus:ring-cyan-400/20">
                    </div>

                    <div>
                        <label for="password" class="text-sm text-slate-300">Senha</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                      focus:border-cyan-400/60 focus:ring-cyan-400/20">
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-300">
                            <input id="remember_me" name="remember" type="checkbox"
                                   class="rounded border-white/20 bg-slate-950/60 text-cyan-400 focus:ring-cyan-400/30">
                            Lembrar de mim
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-slate-300 hover:text-white underline" href="{{ route('password.request') }}">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                        Entrar
                    </button>

                    <div class="mt-2 text-sm text-slate-400">
                        Não tem conta?
                        <a href="{{ route('register') }}" class="text-white underline hover:opacity-90">
                            Criar conta
                        </a>
                    </div>
                </form>

                <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4 text-xs text-slate-300">
                    Ao continuar, você concorda em usar o portal apenas para atendimento e suporte.
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
