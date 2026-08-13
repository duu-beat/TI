@php
    /**
     * Componente Sidebar (Menu Lateral)
     * 
     * Este componente renderiza o menu lateral dinamicamente com base no papel (role) do usuário logado.
     * Ele gerencia badges de identificação, rotas de perfil e inclui os menus parciais específicos.
     */
    $user = auth()->user();
    
    // Identificação de níveis de acesso para lógica de UI
    $isMaster = $user?->isMaster() ?? false;
    $isAdmin = $user?->isAdmin() ?? false;
    
    // Configuração visual do Badge e definição da rota de perfil correta por hierarquia
    if ($isMaster) {
        $badge = 'Segurança';
        $badgeClass = 'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.2)] font-bold';
        $profileRoute = route('master.profile');
    } elseif ($isAdmin) {
        $badge = 'Admin';
        $badgeClass = 'bg-cyan-500/10 text-cyan-200 border-cyan-500/20';
        $profileRoute = route('admin.profile');
    } else {
        $badge = 'Cliente';
        $badgeClass = 'bg-slate-700/50 text-slate-400 border-white/5';
        $profileRoute = route('client.profile');
    }
@endphp

<aside role="navigation" 
       class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950/80 backdrop-blur-xl border-r border-white/10 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto"
       aria-label="Menu lateral principal"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    {{-- Seção de Logo e Identificação do Sistema --}}
    <div class="p-6 border-b border-white/10 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-4 group">
            <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI" 
                 class="h-12 w-12 rounded-xl object-contain shrink-0 group-hover:scale-105 transition" />
            <div class="flex-1">
                <div class="font-bold text-white leading-tight tracking-tight">Suporte TI</div>
                <div class="mt-1 inline-flex items-center">
                    <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded border {{ $badgeClass }}">
                        {{ $badge }}
                    </span>
                </div>
            </div>
        </a>
        {{-- Botão de fechar visível apenas em dispositivos móveis --}}
        <button type="button"
                x-ref="sidebarCloseButton"
                @click="closeSidebar()"
                class="lg:hidden text-slate-400 hover:text-white"
                aria-label="Fechar menu lateral">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Navegação Dinâmica: Inclui o menu parcial correspondente ao nível de acesso --}}
    <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">
        @if($isMaster)
            @include('layouts.partials.master-menu')
        @elseif($isAdmin)
            @include('layouts.partials.admin-menu')
        @else
            @include('layouts.partials.client-menu')
        @endif
    </nav>

    {{-- Conta: o avatar é o acesso principal ao perfil e à segurança. --}}
    <div class="p-4 border-t border-white/10 bg-white/5" x-data="{ accountMenuOpen: false }" @keydown.escape.window="accountMenuOpen = false">
        <div class="relative">
            <button type="button"
                    @click="accountMenuOpen = !accountMenuOpen"
                    :aria-expanded="accountMenuOpen.toString()"
                    aria-haspopup="menu"
                    aria-controls="account-menu"
                    class="w-full flex items-center gap-3 rounded-2xl p-2 text-left hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-cyan-400/60 transition group">
                <img src="{{ $user->profile_photo_url }}"
                     alt="{{ __('Abrir opções da conta de :name', ['name' => $user->name]) }}"
                     class="h-11 w-11 rounded-xl object-cover border border-white/10 bg-slate-800 group-hover:border-cyan-400/50 transition" />
                <span class="min-w-0 flex-1">
                    <span class="block text-sm text-slate-200 font-semibold truncate group-hover:text-white transition">{{ $user->name }}</span>
                    <span class="block text-xs text-slate-500 truncate">{{ $badge }}</span>
                </span>
                <svg class="h-4 w-4 shrink-0 text-slate-500 transition-transform" :class="accountMenuOpen ? 'rotate-180 text-cyan-300' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="account-menu"
                 x-cloak
                 x-show="accountMenuOpen"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 @click.outside="accountMenuOpen = false"
                 class="absolute bottom-full left-0 right-0 z-[60] mb-3 rounded-2xl border border-white/10 bg-slate-900/95 p-2 shadow-2xl backdrop-blur-xl"
                 role="menu"
                 aria-label="{{ __('Opções da conta') }}">
                <a href="{{ $profileRoute }}"
                   role="menuitem"
                   @click="accountMenuOpen = false"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 hover:bg-white/10 hover:text-white transition">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-300">👤</span>
                    <span>
                        <span class="block font-semibold">{{ __('Meu Perfil') }}</span>
                        <span class="block text-xs text-slate-500">{{ __('Dados e foto da conta') }}</span>
                    </span>
                </a>

                <a href="{{ $profileRoute }}#seguranca-da-conta"
                   role="menuitem"
                   @click="accountMenuOpen = false"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 hover:bg-white/10 hover:text-white transition">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-300">🛡️</span>
                    <span>
                        <span class="block font-semibold">{{ __('Segurança da Conta') }}</span>
                        <span class="block text-xs text-slate-500">{{ __('Senha, 2FA e sessões') }}</span>
                    </span>
                </a>

                <div class="my-2 h-px bg-white/10"></div>

                <button type="button"
                        role="menuitem"
                        aria-controls="logout-modal"
                        :aria-expanded="logoutModalOpen.toString()"
                        @click="accountMenuOpen = false; openLogoutModal($event.currentTarget)"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-red-300 hover:bg-red-500/10 hover:text-red-200 transition">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-500/10">↪</span>
                    <span class="font-semibold">{{ __('Sair da conta') }}</span>
                </button>
            </div>
        </div>
    </div>
</aside>
