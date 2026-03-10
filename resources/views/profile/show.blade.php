<x-app-layout>
    @php
        $user = auth()->user();
        
        // --- LÓGICA DE NÍVEIS ---
        $isMaster = method_exists($user, 'isMaster') ? $user->isMaster() : ($user->role === 'master');
        $isAdmin  = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin');
        
        // Define Classes Completas para o Tailwind não remover em produção (Purge)
        if ($isMaster) {
            $roleLabel  = 'Segurança (Master)';
            $badgeClass = 'bg-red-500/10 border-red-500/20 text-red-400 shadow-[0_0_15px_rgba(220,38,38,0.2)]';
            $glowClass  = 'from-red-500/10 group-hover:from-red-500/20';
            $ringClass  = 'ring-red-500/30';
            $stripeClass = 'via-red-500';
        } elseif ($isAdmin) {
            $roleLabel  = 'Administrador';
            $badgeClass = 'bg-cyan-500/10 border-cyan-500/20 text-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.2)]';
            $glowClass  = 'from-cyan-500/10 group-hover:from-cyan-500/20';
            $ringClass  = 'ring-cyan-500/30';
            $stripeClass = 'via-cyan-500';
        } else {
            $roleLabel  = 'Cliente';
            $badgeClass = 'bg-slate-800 border-white/10 text-slate-300';
            $glowClass  = 'from-indigo-500/10 group-hover:from-indigo-500/20';
            $ringClass  = 'ring-indigo-500/30';
            $stripeClass = 'via-indigo-500';
        }
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
                
                {{-- Grid Skeleton --}}
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
                
                {{-- HEADER BANNER DINÂMICO --}}
                <div class="relative bg-slate-900/80 border border-white/10 rounded-[2rem] p-6 mb-8 flex flex-col md:flex-row items-center gap-6 overflow-hidden shadow-2xl group">
                    {{-- Glow de fundo baseado no cargo --}}
                    <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-l {{ $glowClass }} pointer-events-none transition duration-700"></div>

                    <img class="relative h-24 w-24 rounded-full object-cover border-4 border-slate-950 shadow-lg ring-2 {{ $ringClass }}" 
                         src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->name }}">

                    <div class="relative text-center md:text-left z-10 flex-1">
                        <h1 class="text-3xl font-black text-white tracking-tight flex items-center justify-center md:justify-start gap-3">
                            {{ $user->name }}
                            @if($isMaster) <span title="Acesso Root" class="text-2xl drop-shadow-md">🛡️</span> @endif
                        </h1>
                        
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-2">
                            <span class="text-slate-400 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $user->email }}
                            </span>
                            
                            {{-- Badge Cargo --}}
                            <span class="px-3 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- GRID DE CONFIGURAÇÕES --}}
                <div class="grid lg:grid-cols-2 gap-8">
                    
                    {{-- 1. Informações --}}
                    <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl hover:border-white/10 transition">
                        @livewire('profile.update-profile-information-form')
                    </div>

                    {{-- 2. Senha --}}
                    <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl hover:border-white/10 transition">
                        @livewire('profile.update-password-form')
                    </div>

                    {{-- 3. 2FA --}}
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl hover:border-white/10 transition">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    @endif

                    {{-- 4. Sessões --}}
                    <div class="bg-slate-900/50 rounded-[2rem] border border-white/5 p-2 shadow-xl hover:border-white/10 transition">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>

                    {{-- 5. Deletar Conta (Protegido para Master/Admin) --}}
                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <div class="lg:col-span-2 mt-4">
                            @if(!$isAdmin && !$isMaster)
                                {{-- Cliente pode deletar --}}
                                <div class="bg-red-500/5 rounded-[2rem] border border-red-500/10 p-2">
                                    @livewire('profile.delete-user-form')
                                </div>
                            @else
                                {{-- Admin/Master protegidos --}}
                                <div class="flex flex-col justify-center items-center bg-slate-900/50 border border-white/10 rounded-[2rem] p-8 text-center opacity-75 hover:opacity-100 transition relative overflow-hidden">
                                    {{-- Listra de Atenção --}}
                                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent {{ $stripeClass }} to-transparent opacity-50"></div>
                                    
                                    <div class="text-4xl mb-4 grayscale opacity-50">🛡️</div>
                                    <h3 class="text-white font-bold text-lg mb-2">Conta Protegida</h3>
                                    <p class="text-sm text-slate-400 max-w-md mx-auto">
                                        Esta conta possui privilégios de <strong class="text-white">{{ $roleLabel }}</strong> e não pode ser excluída por este painel para garantir a integridade do sistema.
                                    </p>
                                    @if($isMaster)
                                        <p class="text-xs text-red-400/60 mt-4 font-mono uppercase tracking-widest border border-red-500/20 bg-red-500/10 px-3 py-1 rounded-md">Root Access Lock: Enabled</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>