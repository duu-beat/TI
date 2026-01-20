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
                            <div class="text-xs text-slate-300 leading-snug">Criar conta</div>
                        </div>
                    </a>

                    <h1 class="mt-10 text-3xl font-extrabold tracking-tight text-white">
                        Comece seu atendimento em minutos.
                    </h1>
                    <p class="mt-3 text-slate-300">
                        Crie sua conta para abrir chamados e acompanhar tudo pelo portal.
                    </p>

                    <div class="mt-8 space-y-3 text-sm text-slate-200">
                        <div class="flex items-start gap-3">
                            <div class="mt-1.5 h-2 w-2 rounded-full bg-cyan-400"></div>
                            <p><span class="font-semibold text-white">Chamados organizados</span> por status.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1.5 h-2 w-2 rounded-full bg-indigo-400"></div>
                            <p><span class="font-semibold text-white">Mensagens no histórico</span> do chamado.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1.5 h-2 w-2 rounded-full bg-emerald-400"></div>
                            <p><span class="font-semibold text-white">Transparência</span> no acompanhamento.</p>
                        </div>
                    </div>

                    <div class="mt-10 text-xs text-slate-400">
                        Você pode usar o mesmo email do WhatsApp/contato que você costuma usar.
                    </div>
                </div>
            </div>

            {{-- Form panel --}}
            <div class="p-8 lg:p-10 bg-slate-950/40">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-white font-bold text-xl">Cadastro</div>
                        <div class="text-sm text-slate-400">Crie sua conta para abrir chamados.</div>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm text-slate-300 hover:text-white underline">
                        Voltar ao site
                    </a>
                </div>

                <x-validation-errors class="mt-6 mb-4 text-red-200" />

                <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="text-sm text-slate-300">Nome</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                      focus:border-cyan-400/60 focus:ring-cyan-400/20">
                    </div>

                    <div>
                        <label for="email" class="text-sm text-slate-300">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                      focus:border-cyan-400/60 focus:ring-cyan-400/20">
                    </div>

                    <div>
                        <label for="password" class="text-sm text-slate-300">Senha</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                      focus:border-cyan-400/60 focus:ring-cyan-400/20">
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-sm text-slate-300">Confirmar senha</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                      focus:border-cyan-400/60 focus:ring-cyan-400/20">
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                            <label for="terms" class="inline-flex items-start gap-2">
                                <input id="terms" name="terms" type="checkbox" required
                                       class="mt-1 rounded border-white/20 bg-slate-950/60 text-cyan-400 focus:ring-cyan-400/30">
                                <span>
                                    Eu concordo com os
                                    <a target="_blank" href="{{ route('terms.show') }}" class="underline text-white hover:opacity-90">Termos</a>
                                    e a
                                    <a target="_blank" href="{{ route('policy.show') }}" class="underline text-white hover:opacity-90">Política</a>.
                                </span>
                            </label>
                        </div>
                    @endif

                    <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                        Criar conta
                    </button>

                    <div class="mt-2 text-sm text-slate-400">
                        Já tem conta?
                        <a href="{{ route('login') }}" class="text-white underline hover:opacity-90">
                            Entrar
                        </a>
                    </div>
                </form>

                <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4 text-xs text-slate-300">
                    Segurança: sua senha é criptografada e não é visível nem pra gente.
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
