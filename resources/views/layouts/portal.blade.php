<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Suporte TI'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-100 antialiased">

@php
    $user = auth()->user();
    $isAdmin = $user && ($user->role === 'admin');

    $badge = $isAdmin ? 'Admin' : 'Cliente';
    $badgeClass = $isAdmin
        ? 'bg-red-500/15 text-red-200 border-red-500/30'
        : 'bg-cyan-500/15 text-cyan-200 border-cyan-500/30';
@endphp

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-72 bg-slate-900 border-r border-white/10 hidden lg:flex flex-col">
        <div class="p-6 border-b border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-4">

                <img
    src="{{ asset('images/logosuporteTI.png') }}"
    alt="Suporte TI"
    class="h-16 w-16 rounded-2xl object-contain shrink-0"

/>


                <div class="flex-1">
                    <div class="font-bold text-white leading-tight">Suporte TI</div>

                    <div class="mt-1 inline-flex items-center gap-2">
                        <span class="text-xs text-slate-400">
                            {{ $isAdmin ? 'Painel Administrativo' : 'Portal do Cliente' }}
                        </span>

                        <span class="text-[11px] px-2 py-0.5 rounded-full border {{ $badgeClass }}">
                            {{ $badge }}
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-1 text-sm">
            @yield('menu')
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center justify-between gap-3">
                <div>
                    @if($user)
                        <div class="text-sm text-slate-200 font-semibold">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                    @endif
                </div>

                <button
                    type="button"
                    data-open-logout
                    class="rounded-xl bg-white/10 px-3 py-2 text-xs text-slate-200 hover:bg-white/15 transition"
                    title="Sair"
                >
                    Sair
                </button>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1">
        {{-- Topbar --}}
        <header class="sticky top-0 z-40 bg-slate-950/70 backdrop-blur border-b border-white/10">
            <div class="px-6 py-4 flex items-center justify-between gap-4">

                {{-- Left: logo + title --}}
                <div class="flex items-center gap-3">
                    <img
    src="{{ asset('images/logosuporteTI.png') }}"
    alt="Suporte TI"
    class="h-10 w-10 rounded-xl object-contain opacity-90 shrink-0"
/>


                    <div>
                        <div class="text-xs text-slate-400">
                            {{ $isAdmin ? 'Painel Administrativo' : 'Portal do Cliente' }}
                        </div>

                        <h1 class="text-xl font-semibold text-white">
                            @yield('title')
                        </h1>
                    </div>
                </div>

                {{-- Right: actions --}}
                <div class="flex items-center gap-3">
                    @yield('actions')
                </div>
            </div>
        </header>

        {{-- Content --}}
        <section class="p-6">
            @yield('content')
        </section>
    </main>
</div>

{{-- Logout Modal --}}
<div id="logoutModal" class="fixed inset-0 z-[999] hidden">
    <div class="absolute inset-0 bg-black/60" data-backdrop></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 shadow-2xl">
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/logosuporteTI.png') }}"
                        alt="Suporte TI"
                        class="h-9 w-9 rounded-xl object-contain shrink-0"
                    >
                    <div>
                        <div class="text-lg font-semibold text-white">Confirmar saída</div>
                        <div class="mt-0.5 text-sm text-slate-300">
                            {{ $isAdmin ? 'Deseja sair do painel administrativo?' : 'Deseja sair do seu portal?' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 flex items-center justify-end gap-3">
                <button
                    type="button"
                    data-close-logout
                    class="rounded-2xl bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/15 transition"
                >
                    Cancelar
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:opacity-95 transition"
                    >
                        Sair agora
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('logoutModal');
        const openBtn = document.querySelector('[data-open-logout]');
        const closeBtn = document.querySelector('[data-close-logout]');

        if (!modal) return;

        const open = () => modal.classList.remove('hidden');
        const close = () => modal.classList.add('hidden');

        if (openBtn) openBtn.addEventListener('click', open);
        if (closeBtn) closeBtn.addEventListener('click', close);

        // Fecha ao clicar no backdrop
        modal.addEventListener('click', (e) => {
            if (e.target && e.target.dataset && e.target.dataset.backdrop !== undefined) {
                close();
            }
        });

        // ESC fecha
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                close();
            }
        });
    });
</script>

</body>
</html>
