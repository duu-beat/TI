<x-app-layout>
    @php
        $user = auth()->user();
        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin'); 
        $accent = $isAdmin ? 'red' : 'cyan';
    @endphp

    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight">
            {{ __('Minha Conta') }}
        </h2>
    </x-slot>

    {{-- ⚡ ALPINE.JS CONTROLLER --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 💀 SKELETON LOADING --}}
            <div x-show="!loaded" class="space-y-8 animate-pulse">
                {{-- Banner Skeleton --}}
                <div class="h-40 w-full bg-white/5 rounded-[2rem] border border-white/5"></div>
                
                {{-- Grid Skeleton (Agora 2 Colunas mais largas) --}}
                <div class="grid lg:grid-cols-2 gap-8">
                    <div class="h-80 bg-white/5 rounded-[2rem] border border-white/5"></div>
                    <div class="h-80 bg-white/5 rounded-[2rem] border border-white/5"></div>
                    <div class="h-64 bg-white/5 rounded-[2rem] border border-white/5"></div>
                    <div class="h-64 bg-white/5 rounded-[2rem] border border-white/5"></div>
                </div>
            </div>

            {{-- ✅ CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                {{-- HEADER BANNER (Mais compacto e elegante) --}}
                <div class="relative bg-slate-900/80 border border-white/10 rounded-[2rem] p-6 mb-8 flex flex-col md:flex-row items-center gap-6 overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-l from-{{ $accent }}-500/10 to-transparent pointer-events-none"></div>

                    <img class="relative h-24 w-24 rounded-full object-cover border-4 border-slate-950 shadow-lg" 
                         src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->name }}">

                    <div class="relative text-center md:text-left z-10 flex-1">
                        <h1 class="text-3xl font-black text-white tracking-tight">
                            {{ $user->name }}
                        </h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-2">
                            <span class="text-slate-400 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $user->email }}
                            </span>
                            
                            {{-- Badge Cargo --}}
                            <span class="px-3 py-1 rounded-full bg-slate-800 border border-white/10 text-[10px] font-bold uppercase tracking-wider text-{{ $accent }}-400">
                                {{ $isAdmin ? 'Administrador' : 'Cliente' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- GRID DE CONFIGURAÇÕES (2 Colunas = Mais espaço para os forms) --}}
                <div class="grid lg:grid-cols-2 gap-8">
                    
                    {{-- 1. Informações --}}
                    <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl">
                        @livewire('profile.update-profile-information-form')
                    </div>

                    {{-- 2. Senha --}}
                    <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl">
                        @livewire('profile.update-password-form')
                    </div>

                    {{-- 3. 2FA (Ocupa largura total se quiser destaque, ou coluna) --}}
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    @endif

                    {{-- 4. Sessões --}}
                    <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>

                    {{-- 5. Deletar Conta (Full Width para segurança e destaque) --}}
                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <div class="lg:col-span-2 mt-4">
                            @if(!$isAdmin)
                                <div class="bg-red-500/5 rounded-[2rem] border border-red-500/10 p-2">
                                    @livewire('profile.delete-user-form')
                                </div>
                            @else
                                <div class="flex flex-col justify-center items-center bg-slate-900/50 border border-white/10 rounded-[2rem] p-8 text-center opacity-75 hover:opacity-100 transition">
                                    <div class="text-3xl mb-2 grayscale opacity-50">🛡️</div>
                                    <h3 class="text-slate-400 font-bold text-sm">Conta Protegida</h3>
                                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                                        Contas administrativas possuem proteção extra contra exclusão acidental.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>