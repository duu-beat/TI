<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Suporte TI'))</title>
    
    {{-- Fonte Outfit --}}
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

    {{-- Background Mesh --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[100px]"></div>
        <div class="absolute top-[20%] right-[0%] w-[30%] h-[30%] rounded-full bg-cyan-600/10 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] rounded-full bg-purple-600/10 blur-[100px]"></div>
    </div>

    @php
        $user = auth()->user();
        $isAdmin = $user && ($user->role === 'admin');
        $badge = $isAdmin ? 'Admin' : 'Cliente';
        $badgeClass = $isAdmin
            ? 'bg-red-500/10 text-red-200 border-red-500/20'
            : 'bg-cyan-500/10 text-cyan-200 border-cyan-500/20';
    @endphp

    {{-- Wrapper Principal com Alpine.js --}}
    <div class="min-h-screen flex relative z-10" x-data="{ sidebarOpen: false, logoutModalOpen: false }">

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950/80 backdrop-blur-xl border-r border-white/10 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                    <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI"
                         class="h-14 w-14 rounded-2xl object-contain shrink-0 group-hover:scale-105 transition" />

                    <div class="flex-1">
                        <div class="font-bold text-white leading-tight tracking-tight">Suporte TI</div>
                        <div class="mt-2 inline-flex items-center gap-2 flex-wrap">
                            <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded border {{ $badgeClass }}">
                                {{ $badge }}
                            </span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- ✅ MENU CORRIGIDO: Usa @include em vez de @yield --}}
            <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">
                @if(auth()->user()->role === 'admin')
                    @include('layouts.partials.admin-menu')
                @else
                    @include('layouts.partials.client-menu')
                @endif
            </nav>

            <div class="p-4 border-t border-white/10 bg-white/5">
                <div class="flex items-center justify-between gap-3">
                    <div class="overflow-hidden">
                        @if($user)
                            <div class="text-sm text-slate-200 font-semibold truncate">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
                        @endif
                    </div>

                    {{-- Botão Sair (Abre Modal Alpine) --}}
                    <button type="button" 
                            @click="logoutModalOpen = true"
                            class="rounded-xl bg-white/10 px-3 py-2 text-xs text-slate-200 hover:bg-white/20 hover:text-red-400 transition"
                            title="Sair">
                        Sair
                    </button>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-transparent">
            {{-- Topbar --}}
            <header class="sticky top-0 z-40 bg-slate-950/70 backdrop-blur-md border-b border-white/10">
                <div class="px-6 py-4 flex items-center justify-between gap-4">

                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="lg:hidden text-slate-400 hover:text-white mr-2">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>

                        <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI"
                             class="h-10 w-10 rounded-xl object-contain opacity-90 shrink-0 lg:hidden" />

                        <div>
                            <div class="text-xs text-slate-400 lg:hidden">
                                {{ $isAdmin ? 'Painel Administrativo' : 'Portal do Cliente' }}
                            </div>
                            <h1 class="text-xl font-bold text-white tracking-tight">
                                @yield('title')
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @yield('actions')
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <section class="flex-1 overflow-y-auto p-6 scroll-smooth">
                @yield('content')
            </section>
        </main>

        {{-- Backdrop Mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 lg:hidden"></div>

        {{-- ✅ MODAL DE LOGOUT (Com Animação Fade) --}}
        <div x-show="logoutModalOpen" 
             style="display: none;" 
             class="fixed inset-0 z-[999] flex items-center justify-center p-6"
             x-cloak>
            
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" 
                 x-show="logoutModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="logoutModalOpen = false"></div>

            <div class="relative w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 shadow-2xl p-1"
                 x-show="logoutModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                
                <div class="bg-white/5 rounded-[20px] p-6 border border-white/5">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center text-2xl">
                            👋
                        </div>
                        <div>
                            <div class="text-lg font-bold text-white">Confirmar saída</div>
                            <div class="text-sm text-slate-400">
                                {{ $isAdmin ? 'Deseja sair do painel administrativo?' : 'Deseja sair do seu portal?' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="logoutModalOpen = false"
                                class="rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition">
                            Cancelar
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-bold text-slate-950 hover:opacity-90 transition shadow-lg shadow-cyan-500/20">
                                Sair agora
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Toast Notification --}}
    @if (session('success') || session('error'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 5000)"
             x-show="show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed bottom-6 right-6 z-[100] max-w-sm w-full bg-slate-900/90 backdrop-blur-md border border-white/10 shadow-2xl rounded-2xl pointer-events-auto overflow-hidden ring-1 ring-black ring-opacity-5">
            
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if(session('success'))
                            <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-white">{{ session('success') ? 'Sucesso!' : 'Atenção' }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ session('success') ?? session('error') }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-slate-400 hover:text-white focus:outline-none">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r {{ session('success') ? 'from-emerald-500 to-green-400' : 'from-red-500 to-orange-400' }}" style="width: 100%; animation: shrink 5s linear forwards;"></div>
            </div>
        </div>
        <style>@keyframes shrink { from { width: 100%; } to { width: 0%; } }</style>
    @endif

    @livewireScripts
</body>
</html>

{{-- FIM DO LAYOUT PORTAL.BLADE.PHP --}}