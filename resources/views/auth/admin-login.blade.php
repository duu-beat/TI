<x-auth.brand-layout
    title="Acesso administrativo"
    subtitle="Painel interno"
    headline="Área restrita"
    description="Somente administradores autorizados."
    panelHint="Tentativas indevidas podem ser registradas."
>
    {{-- Alpine.js para controlar interatividade local (Senha, CapsLock, Loading) --}}
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
    >
        <x-validation-errors class="mb-4 text-red-200" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5" @submit="loading = true">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-slate-400 mb-1">Email Corporativo</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-orange-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input type="email" name="email" required autofocus tabindex="1"
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-white/10 bg-slate-950/60 text-slate-100 placeholder:text-slate-600
                                  focus:border-orange-400/50 focus:ring-4 focus:ring-orange-400/10 outline-none transition-all duration-300"
                           placeholder="admin@suporte.com">
                </div>
            </div>

            {{-- Senha com Toggle --}}
            <div>
                <label class="block text-sm font-medium text-slate-400 mb-1">Senha de Acesso</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-orange-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    
                    <input :type="showPassword ? 'text' : 'password'" name="password" required tabindex="2"
                           class="w-full pl-10 pr-12 py-3 rounded-2xl border border-white/10 bg-slate-950/60 text-slate-100 placeholder:text-slate-600
                                  focus:border-orange-400/50 focus:ring-4 focus:ring-orange-400/10 outline-none transition-all duration-300"
                           placeholder="••••••••">

                    {{-- Botão Olho --}}
                    <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-white transition cursor-pointer">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>

                {{-- Aviso de Caps Lock --}}
                <div x-show="capsLockOn" style="display: none;" 
                     class="mt-2 text-xs font-bold text-orange-400 flex items-center gap-1 animate-pulse">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    CAPS LOCK ATIVADO
                </div>
            </div>

            {{-- Remember & Forgot Password --}}
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <div class="relative flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" 
                               class="peer h-4 w-4 rounded border-slate-700 bg-slate-900 text-orange-500 focus:ring-offset-slate-900 focus:ring-orange-500/50 transition cursor-pointer">
                    </div>
                    <span class="ml-2 text-sm text-slate-400 group-hover:text-slate-300 transition select-none">Manter conectado</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-orange-500 hover:text-orange-400 transition underline-offset-4 hover:underline">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>

            {{-- Botão Submit com Loading --}}
            <button type="submit" 
                    :disabled="loading"
                    :class="loading ? 'opacity-75 cursor-not-allowed' : 'hover:opacity-95 hover:scale-[1.02] active:scale-[0.98]'"
                    class="w-full relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 px-6 py-3.5 font-bold text-white shadow-lg shadow-orange-500/20 transition-all duration-300">
                
                <span x-show="!loading" class="flex items-center justify-center gap-2">
                    Entrar <span aria-hidden="true"></span>
                </span>

                <span x-show="loading" style="display: none;" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Autenticando...
                </span>
            </button>

            {{-- Voltar --}}
            <div class="pt-2 text-center">
                <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-white transition flex items-center justify-center gap-1 group">
                    <svg class="w-3 h-3 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Acesso para clientes
                </a>
            </div>
        </form>
    </div>
</x-auth.brand-layout>