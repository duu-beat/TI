<x-guest-layout>
    <div class="w-full max-w-5xl">
        <div class="grid lg:grid-cols-2 rounded-3xl overflow-hidden border border-white/10 bg-slate-900/50 backdrop-blur-xl shadow-2xl relative">
            
            {{-- Background Glows --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
                <div class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-64 h-64 bg-cyan-500/10 rounded-full blur-[80px]"></div>
            </div>

            {{-- Brand Panel (Esquerda) --}}
            <div class="relative p-8 lg:p-12 flex flex-col justify-between z-10 border-r border-white/5 bg-slate-950/30">
                
                {{-- Neon Line Top --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-cyan-400 opacity-50"></div>

                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-8 group">
                        <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI" class="h-10 w-auto object-contain group-hover:scale-105 transition duration-300">
                        <div class="leading-tight">
                            <div class="text-white font-bold tracking-tight text-xl leading-none group-hover:text-cyan-400 transition">Suporte TI</div>
                            <div class="text-xs text-slate-400 font-medium uppercase tracking-wider mt-0.5">Portal do Cliente</div>
                        </div>
                    </a>

                    <h1 class="mt-8 text-3xl md:text-4xl font-extrabold tracking-tight text-white leading-tight">
                        Bem-vindo de volta!
                    </h1>
                    <p class="mt-4 text-lg text-slate-400 leading-relaxed">
                        Acesse sua conta para abrir chamados, ver o histórico e falar com o suporte.
                    </p>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400">
                                🚀
                            </div>
                            <span>Acesso rápido ao suporte técnico.</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400">
                                🔒
                            </div>
                            <span>Ambiente seguro e criptografado.</span>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-xs font-medium text-slate-500 border-t border-white/5 pt-6">
                    Se tiver problemas para entrar, contate o administrador.
                </div>
            </div>

            {{-- Form Panel (Direita) --}}
            <div class="relative p-8 lg:p-12 flex flex-col justify-center bg-slate-900/40 z-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Login</h2>
                        <p class="text-sm text-slate-400 mt-1">Entre com suas credenciais.</p>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-white transition underline">Voltar</a>
                </div>

                <x-validation-errors class="mb-4 text-red-200 bg-red-500/10 p-3 rounded-xl border border-red-500/20 text-sm" />

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-emerald-400 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Social Login Buttons --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button type="button" class="flex items-center justify-center gap-2 py-2.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition text-sm font-medium text-white group">
                        <svg class="h-5 w-5 opacity-70 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 py-2.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition text-sm font-medium text-white group">
                        <svg class="h-5 w-5 opacity-70 group-hover:opacity-100 transition" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.083-.729.083-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.42-1.305.763-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12"/>
                        </svg>
                        GitHub
                    </button>
                </div>

                <div class="relative flex py-2 items-center mb-6">
                    <div class="flex-grow border-t border-white/10"></div>
                    <span class="flex-shrink-0 mx-4 text-slate-500 text-xs uppercase tracking-widest">Ou com email</span>
                    <div class="flex-grow border-t border-white/10"></div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Email</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-4 py-3 text-slate-100 placeholder:text-slate-600 focus:border-cyan-400/50 focus:ring-4 focus:ring-cyan-400/10 transition-all outline-none"
                                placeholder="seu@email.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Senha</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-cyan-400 hover:text-cyan-300 hover:underline transition" href="{{ route('password.request') }}">
                                    Esqueceu?
                                </a>
                            @endif
                        </div>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-10 py-3 text-slate-100 placeholder:text-slate-600 focus:border-cyan-400/50 focus:ring-4 focus:ring-cyan-400/10 transition-all outline-none"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-white focus:outline-none">
                                <svg x-show="!showPassword" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="showPassword" style="display: none;" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.591-2.714M9.828 9.828a3 3 0 114.242 4.242M3 3l18 18" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="block">
                        <label for="remember_me" class="flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-white/20 bg-slate-900 text-cyan-500 shadow-sm focus:ring-cyan-500/50 focus:ring-offset-0 transition cursor-pointer">
                            <span class="ms-2 text-sm text-slate-400 group-hover:text-slate-300 transition">Lembrar de mim</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-4 font-bold text-slate-950 text-lg hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                        Entrar na Conta
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-slate-400">
                    Ainda não tem conta? 
                    <a href="{{ route('register') }}" class="text-white font-semibold hover:text-cyan-400 transition">Criar agora</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>