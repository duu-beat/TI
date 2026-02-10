<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @auth
            {{ auth()->user()->isMaster() ? 'Segurança' : (auth()->user()->isAdmin() ? 'Admin' : 'Cliente') }} &middot; {{ config('app.name', 'Suporte TI') }}
        @else
            {{ config('app.name', 'Suporte TI') }}
        @endauth
    </title>

    <link rel="icon" href="{{ asset('images/logosuporteTI.png') }}" type="image/png">
    
    {{-- Fonts & Styles --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-950 text-slate-100 antialiased relative selection:bg-cyan-500 selection:text-white font-sans">

    {{-- Skip Link para Acessibilidade --}}
    <x-skip-link />

    {{-- ✅ 1. DETECTOR DE OFFLINE (NOVIDADE) --}}
    <div x-data="{ online: navigator.onLine }" 
         @online.window="online = true" 
         @offline.window="online = false" 
         x-show="!online" 
         x-cloak
         class="bg-red-600 text-white text-center text-xs font-bold py-1 fixed top-0 w-full z-[100] shadow-lg">
        📡 VOCÊ ESTÁ OFFLINE - Verifique sua conexão
    </div>

    {{-- ✅ 2. BANNER GLOBAL (dados compartilhados no AppServiceProvider) --}}

    @if(!empty($globalMsg))
        <div class="{{ $bannerClasses }} border-b px-4 py-3 text-center text-sm font-bold relative z-50 backdrop-blur-md">
            <span class="mr-2">{{ $icon }}</span> {{ $globalMsg }}
        </div>
    @endif

    {{-- Background Mesh --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[100px]"></div>
        <div class="absolute top-[20%] right-[0%] w-[30%] h-[30%] rounded-full bg-cyan-600/10 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] rounded-full bg-purple-600/10 blur-[100px]"></div>
    </div>

    <div class="min-h-screen flex relative z-10"
         x-data="{
             sidebarOpen: false,
             logoutModalOpen: false,
             lastFocusedElement: null,
             openSidebar(triggerEl) {
                 this.lastFocusedElement = triggerEl ?? document.activeElement;
                 this.sidebarOpen = true;
                 this.$nextTick(() => this.$refs.sidebarCloseButton?.focus());
             },
             closeSidebar() {
                 if (!this.sidebarOpen) return;
                 this.sidebarOpen = false;
                 this.$nextTick(() => this.lastFocusedElement?.focus?.());
             },
             openLogoutModal(triggerEl) {
                 this.lastFocusedElement = triggerEl ?? document.activeElement;
                 this.logoutModalOpen = true;
                 this.$nextTick(() => this.$refs.logoutCancelButton?.focus());
             },
             closeLogoutModal() {
                 if (!this.logoutModalOpen) return;
                 this.logoutModalOpen = false;
                 this.$nextTick(() => this.lastFocusedElement?.focus?.());
             }
         }"
         @keydown.escape.window="closeSidebar(); closeLogoutModal()">

        <x-sidebar />

        <main id="main-content" role="main" class="flex-1 flex flex-col min-h-screen overflow-hidden bg-transparent">
            {{-- Topbar --}}
            <header class="sticky top-0 z-40 bg-slate-950/70 backdrop-blur-md border-b border-white/10 h-16 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button type="button"
                            @click="openSidebar($event.currentTarget)"
                            :aria-expanded="sidebarOpen.toString()"
                            aria-label="Abrir menu lateral"
                            class="lg:hidden text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    @if (isset($header))
                        <div class="font-semibold text-xl text-white leading-tight">
                            {{ $header }}
                        </div>
                    @endif
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                {{ $slot }}
            </div>
        </main>

        <div x-show="sidebarOpen" @click="closeSidebar()" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 lg:hidden"></div>

        {{-- Modal de Logout --}}
        <div id="logout-modal"
             x-show="logoutModalOpen"
             style="display: none;"
             class="fixed inset-0 z-[999] flex items-center justify-center p-6"
             role="dialog"
             aria-modal="true"
             aria-labelledby="logout-modal-title"
             x-cloak>
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="closeLogoutModal()"></div>

            <div class="relative w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 shadow-2xl p-1"
                 x-show="logoutModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="bg-white/5 rounded-[20px] p-6 border border-white/5">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center text-2xl">👋</div>
                        <div>
                            <div id="logout-modal-title" class="text-lg font-bold text-white">Confirmar saída</div>
                            <div class="text-sm text-slate-400">Deseja encerrar a sessão?</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button"
                                x-ref="logoutCancelButton"
                                @click="closeLogoutModal()"
                                class="rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition">
                            Cancelar
                        </button>

                        <form method="POST" action="{{ (request()->routeIs('admin.*') || request()->routeIs('master.*')) ? route('admin.logout') : route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-bold text-slate-950 hover:opacity-90 transition shadow-lg shadow-cyan-500/20">
                                Sair agora
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    @if (session('success') || session('error') || session('status'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.move
             class="fixed bottom-6 right-6 z-[100] max-w-sm w-full bg-slate-900 border border-white/10 shadow-2xl rounded-2xl p-4 flex items-start gap-3">
            <div class="shrink-0 text-2xl">
                {{ session('error') ? '⚠️' : '✅' }}
            </div>
            <div>
                <div class="font-bold text-white">
                    {{ session('error') ? 'Atenção' : 'Sucesso' }}
                </div>
                <div class="text-sm text-slate-400">
                    {{ session('success') ?? session('error') ?? session('status') }}
                </div>
            </div>
        </div>
    @endif

    @stack('modals')
    @livewireScripts
</body>
</html>
