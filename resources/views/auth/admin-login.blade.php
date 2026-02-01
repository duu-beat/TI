<x-auth.brand-layout
    title="Login - Admin"
    subtitle="Área Administrativa"
    headline="Gestão de Atendimento"
    description="Acesso restrito para colaboradores da equipe de suporte."
    panelHint="Sessão monitorada. Utilize credenciais corporativas."
>
    {{-- Texto Personalizado da Esquerda --}}
    <x-slot:features>
        <div class="flex items-center gap-3 text-sm text-slate-300">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 border border-cyan-500/20">🎫</div>
            <span>Gestão avançada de chamados e SLA.</span>
        </div>
        <div class="flex items-center gap-3 text-sm text-slate-300">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 border border-cyan-500/20">📊</div>
            <span>Painéis e relatórios de produtividade.</span>
        </div>
    </x-slot:features>

    {{-- Efeitos de Fundo (Ciano) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-3xl">
        <div class="absolute inset-0 bg-[linear-gradient(rgba(6,182,212,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(6,182,212,0.03)_1px,transparent_1px)] bg-[size:20px_20px]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-cyan-900/5 to-transparent h-full w-full animate-scan"></div>
    </div>

    {{-- Lógica do Formulário --}}
    <div x-data="{ 
            showPassword: false, 
            capsLockOn: false, 
            loading: false,
            checkCapsLock(e) { 
                if (e.getModifierState('CapsLock')) { this.capsLockOn = true; } else { this.capsLockOn = false; }
            }
         }" 
         @keydown.window="checkCapsLock($event)" 
         @keyup.window="checkCapsLock($event)"
         class="relative z-10"
    >
        {{-- Terminal Header --}}
        <div class="mb-8 border-b border-cyan-900/30 pb-4 font-mono text-[10px] text-cyan-500/60 flex justify-between items-end select-none">
            <div class="space-y-1">
                <div>GATEWAY: <span class="text-cyan-400">ONLINE</span></div>
                <div>PROTOCOL: <span class="text-white">TLS 1.3</span></div>
            </div>
            <div class="text-right">
                <div class="text-cyan-500 font-bold animate-pulse">● STAFF ACCESS</div>
                <div>NODE: BR-RJ-01</div>
            </div>
        </div>

        <x-validation-errors class="mb-6 p-3 bg-cyan-950/30 border border-cyan-500/50 rounded-lg text-cyan-400 font-mono text-xs shadow-[0_0_15px_rgba(6,182,212,0.2)]" />

        @if (session('status'))
            <div class="mb-6 p-3 bg-green-950/30 border border-green-500/50 rounded-lg font-mono text-xs text-green-400">
                > {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6" @submit="loading = true">
            @csrf

            <div class="group">
                <label class="block text-[10px] font-bold text-cyan-500 uppercase tracking-[0.2em] mb-2 font-mono">Email Corporativo</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-cyan-900/60 group-focus-within:text-cyan-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                    </div>
                    <input type="email" name="email" required autofocus tabindex="1" class="w-full pl-12 pr-4 py-3.5 bg-black border border-cyan-900/40 rounded-lg text-cyan-100 placeholder-cyan-900/30 font-mono text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all duration-300" placeholder="admin@suporte.com">
                </div>
            </div>

            <div class="group">
                <label class="block text-[10px] font-bold text-cyan-500 uppercase tracking-[0.2em] mb-2 font-mono">Senha</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-cyan-900/60 group-focus-within:text-cyan-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" name="password" required tabindex="2" class="w-full pl-12 pr-12 py-3.5 bg-black border border-cyan-900/40 rounded-lg text-cyan-100 placeholder-cyan-900/30 font-mono text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all duration-300 tracking-widest" placeholder="••••••••">
                    <button type="button" @click="showPassword = !showPassword" tabindex="-1" class="absolute inset-y-0 right-0 pr-4 flex items-center text-cyan-900/60 hover:text-cyan-500 transition cursor-pointer">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" name="remember" class="peer h-4 w-4 rounded border-cyan-900 bg-black text-cyan-600 focus:ring-offset-black focus:ring-cyan-600 transition">
                    <span class="ml-2 text-xs font-bold text-slate-500 group-hover:text-cyan-400 transition font-mono uppercase">Manter Sessão</span>
                </label>
            </div>

            <button type="submit" :disabled="loading" :class="loading ? 'opacity-75' : 'hover:bg-cyan-600 hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]'" class="w-full relative overflow-hidden rounded-lg bg-cyan-700 px-6 py-4 font-bold text-white shadow-lg border border-cyan-500/30 group transition-all duration-300">
                <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-cyan-300/50 to-transparent"></div>
                <span x-show="!loading" class="flex items-center justify-center gap-3 uppercase tracking-[0.2em] text-xs font-mono">
                    <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    Acessar Painel
                </span>
                <span x-show="loading" style="display: none;" class="flex items-center justify-center gap-3 text-xs uppercase tracking-[0.2em] font-mono">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Verificando...
                </span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-cyan-900/20 text-center font-mono">
            <a href="{{ route('login') }}" class="text-[10px] text-slate-600 hover:text-cyan-400 transition uppercase tracking-widest flex items-center justify-center gap-2 group">
                <span class="group-hover:-translate-x-1 transition-transform">&larr;</span>
                Voltar para Login de Cliente
            </a>
        </div>
    </div>
    <style>
        .animate-scan { animation: scan 4s linear infinite; }
    </style>
</x-auth.brand-layout>