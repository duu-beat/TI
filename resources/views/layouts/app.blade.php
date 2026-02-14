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



    {{-- ✨ SPOTLIGHT SEARCH (CTRL + K) --}}
    <div x-data="spotlight()" 
         x-init="init()"
         @keydown.window.prevent.ctrl.k="toggle()"
         @keydown.window.prevent.cmd.k="toggle()"
         @keydown.escape.window="isOpen = false"
         class="relative z-[9999]" 
         style="display: none;" 
         x-show="isOpen">
        
        {{-- Backdrop Escuro --}}
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" 
             x-show="isOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isOpen = false"></div>

        {{-- Janela de Busca --}}
        <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
            <div class="mx-auto max-w-2xl transform divide-y divide-white/5 overflow-hidden rounded-2xl bg-slate-900 shadow-2xl ring-1 ring-white/10 transition-all"
                 x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                {{-- Campo de Input --}}
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           x-ref="searchInput"
                           x-model="query"
                           @input.debounce.300ms="search()"
                           class="h-14 w-full border-0 bg-transparent pl-12 pr-4 text-white placeholder:text-slate-500 focus:ring-0 sm:text-sm" 
                           placeholder="Buscar chamados, clientes... (Pressione ESC para sair)">
                </div>

                {{-- Lista de Resultados --}}
                <ul x-show="results.length > 0" class="max-h-96 scroll-py-3 overflow-y-auto p-2">
                    <template x-for="(item, index) in results" :key="index">
                        <li class="group flex cursor-pointer select-none rounded-xl p-3 hover:bg-white/5 transition"
                            @click="window.location = item.url">
                            <div class="flex h-10 w-10 flex-none items-center justify-center rounded-lg border border-white/10"
                                 :class="item.type === 'ticket' ? 'bg-indigo-500/20 text-indigo-400' : 'bg-emerald-500/20 text-emerald-400'">
                                <template x-if="item.type === 'ticket'">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                                </template>
                                <template x-if="item.type === 'user'">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </template>
                            </div>
                            <div class="ml-4 flex-auto">
                                <p class="truncate text-sm font-medium text-white" x-text="item.title"></p>
                                <p class="truncate text-xs text-slate-400" x-text="item.subtitle"></p>
                            </div>
                            <svg class="ml-3 h-5 w-5 flex-none text-slate-600 opacity-0 group-hover:opacity-100 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </li>
                    </template>
                </ul>

                {{-- Estado Vazio / Instruções --}}
                <div x-show="query.length < 2" class="px-6 py-14 text-center sm:px-14">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-white/5 border border-white/10 text-xs text-slate-400 mb-4">
                        <kbd class="font-sans font-bold">CTRL</kbd> + <kbd class="font-sans font-bold">K</kbd>
                    </div>
                    <p class="text-sm text-slate-500">Digite para buscar em todo o sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function spotlight() {
            return {
                isOpen: false,
                query: '',
                results: [],
                toggle() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.$nextTick(() => this.$refs.searchInput.focus());
                        this.query = '';
                        this.results = [];
                    }
                },
                search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    // Faz a busca na rota que criamos
                    fetch(`{{ route('admin.global-search') }}?query=${encodeURIComponent(this.query)}`)
                        .then(res => res.json())
                        .then(data => { this.results = data; });
                }
            }
        }
    </script>

</body>
</html>
