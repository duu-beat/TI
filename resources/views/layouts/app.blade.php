<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Suporte TI') }}</title>
    
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
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-950 text-slate-100 antialiased relative selection:bg-cyan-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    {{-- Lógica de Perfil --}}
    @php
        $user = auth()->user();
        $isAdmin = $user && ($user->role === 'admin');
        $badge = $isAdmin ? 'Admin' : 'Cliente';
        $badgeClass = $isAdmin ? 'bg-red-500/10 text-red-200 border-red-500/20' : 'bg-cyan-500/10 text-cyan-200 border-cyan-500/20';
        
        // Classes para links ativos/inativos
        $activeClass = 'bg-white/10 text-white font-medium shadow-lg border border-white/5';
        $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition';
    @endphp

    {{-- Background Mesh Gradient --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[100px]"></div>
        <div class="absolute top-[20%] right-[0%] w-[30%] h-[30%] rounded-full bg-cyan-600/10 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] rounded-full bg-purple-600/10 blur-[100px]"></div>
    </div>

    <div class="min-h-screen flex relative z-10">

        {{-- SIDEBAR FIXA --}}
        <aside class="w-72 bg-slate-950/80 backdrop-blur-xl border-r border-white/10 hidden lg:flex flex-col fixed inset-y-0 z-50">
            <div class="p-6 border-b border-white/10">
                <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                    <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI" class="h-14 w-14 rounded-2xl object-contain shrink-0 group-hover:scale-105 transition" />
                    <div class="flex-1">
                        <div class="font-bold text-white leading-tight tracking-tight">Suporte TI</div>
                        <div class="mt-2 inline-flex items-center gap-2 flex-wrap">
                            <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded border {{ $badgeClass }}">
                                {{ $badge }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- MENU INTELIGENTE --}}
            <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">
                
                @if($isAdmin)
                    <div class="px-2 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider mt-2">Gestão</div>
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-4 py-3 mb-1 flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                        <span>📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.tickets.index') }}" class="block rounded-xl px-4 py-3 mb-1 flex items-center gap-3 {{ request()->routeIs('admin.tickets.*') ? $activeClass : $inactiveClass }}">
                        <span>🎫</span> Chamados
                    </a>
                @else
                    <div class="px-2 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider mt-2">Menu</div>
                    <a href="{{ route('client.dashboard') }}" class="block rounded-xl px-4 py-3 mb-1 flex items-center gap-3 {{ request()->routeIs('client.dashboard') ? $activeClass : $inactiveClass }}">
                        <span>🏠</span> Início
                    </a>
                    <a href="{{ route('client.tickets.index') }}" class="block rounded-xl px-4 py-3 mb-1 flex items-center gap-3 {{ request()->routeIs('client.tickets.*') ? $activeClass : $inactiveClass }}">
                        <span>🎫</span> Meus Chamados
                    </a>
                @endif

                <div class="px-2 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider mt-6">Conta</div>
                <a href="{{ route('profile.show') }}" class="block rounded-xl px-4 py-3 mb-1 flex items-center gap-3 {{ request()->routeIs('profile.show') ? $activeClass : $inactiveClass }}">
                    <span>👤</span> Meu Perfil
                </a>
            </nav>

            <div class="p-4 border-t border-white/10 bg-white/5">
                <div class="flex items-center justify-between gap-3">
                    <div class="overflow-hidden">
                        <div class="text-sm text-slate-200 font-semibold truncate">{{ explode(' ', $user->name)[0] }}</div>
                        <div class="text-xs text-slate-500 truncate max-w-[120px]">{{ $user->email }}</div>
                    </div>
                    <button @click="$dispatch('open-logout')" class="rounded-xl bg-white/10 px-3 py-2 text-xs text-slate-200 hover:bg-white/20 transition" title="Sair">
                        Sair
                    </button>
                </div>
            </div>
        </aside>

        {{-- MOBILE OVERLAY --}}
        <div x-show="mobileMenuOpen" class="fixed inset-0 bg-slate-950/80 z-40 lg:hidden backdrop-blur-sm" @click="mobileMenuOpen = false" style="display: none;"></div>

        {{-- CONTEÚDO PRINCIPAL --}}
        <main class="flex-1 flex flex-col min-h-screen lg:ml-72 transition-all duration-300">
            
            {{-- Topbar --}}
            <header class="sticky top-0 z-40 bg-slate-950/70 backdrop-blur-md border-b border-white/10">
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-1 rounded-lg hover:bg-white/10">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <h1 class="text-xl font-bold text-white tracking-tight">
                            @if (isset($header)) {{ $header }} @else Perfil @endif
                        </h1>
                    </div>
                </div>
            </header>

            {{-- Injeção do Slot (Aqui entra o conteúdo do perfil) --}}
            <section class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full">
                {{ $slot }}
            </section>
        </main>
    </div>

    {{-- MODAL DE LOGOUT --}}
    <div x-data="{ open: false }" @open-logout.window="open = true" x-show="open" style="display: none;" class="fixed inset-0 z-[999]">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-6 pointer-events-none">
            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 shadow-2xl p-1 pointer-events-auto">
                <div class="bg-white/5 rounded-[20px] p-6 border border-white/5">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center text-2xl">👋</div>
                        <div>
                            <div class="text-lg font-bold text-white">Sair da conta?</div>
                            <div class="text-sm text-slate-400">Você precisará fazer login novamente.</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="open = false" class="rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition">Cancelar</button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-bold text-slate-950 hover:opacity-90 transition shadow-lg shadow-cyan-500/20">Sair agora</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>s