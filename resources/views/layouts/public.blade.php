<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Suporte TI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased">

<header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/70 backdrop-blur">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/logosuporteTI.png') }}"
                alt="Suporte TI"
                class="h-10 md:h-11 w-auto object-contain"
            >

            <div class="leading-tight">
                <div class="font-bold tracking-tight text-white leading-none">
                    Suporte TI
                </div>
                <div class="text-xs text-slate-300 leading-snug">
                    Soluções rápidas e seguras
                </div>
            </div>
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden md:flex items-center gap-6 text-sm text-slate-200">
            <a class="hover:text-white transition" href="{{ route('services') }}">Serviços</a>
            <a class="hover:text-white transition" href="{{ route('portfolio') }}">Portfólio</a>
            <a class="hover:text-white transition" href="{{ route('contact') }}">Contato</a>

            <div class="h-5 w-px bg-white/10"></div>

            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white/10 px-4 py-2 hover:bg-white/15 transition">
                        Painel de Administração
                    </a>
                @else
                    <a href="{{ route('client.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white/10 px-4 py-2 hover:bg-white/15 transition">
                        Portal
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="hover:text-white transition">Login</a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-4 py-2 font-semibold text-slate-950 hover:opacity-95 transition">
                    Cadastro
                </a>
            @endauth
        </nav>

        {{-- Mobile actions --}}
        <div class="md:hidden flex items-center gap-2">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white/10 px-3 py-2 text-sm hover:bg-white/15 transition">
                        Admin
                    </a>
                @else
                    <a href="{{ route('client.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white/10 px-3 py-2 text-sm hover:bg-white/15 transition">
                        Portal
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center rounded-xl bg-white/10 px-3 py-2 text-sm hover:bg-white/15 transition">
                    Login
                </a>
            @endauth

            <a href="{{ route('contact') }}"
               class="inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-3 py-2 text-sm font-semibold text-slate-950 hover:opacity-95 transition">
                Contato
            </a>
        </div>
    </div>
</header>


<main class="min-h-[70vh]">
    @yield('content')
</main>

<footer class="border-t border-white/10 bg-slate-950">
    <div class="max-w-7xl mx-auto px-6 py-10 text-sm text-slate-300">
        <div class="flex flex-col md:flex-row gap-6 md:items-center md:justify-between">
            <div>
                <div class="font-semibold text-white">Suporte TI</div>
                <div class="mt-1">Atendimento, manutenção, suporte e consultoria.</div>
            </div>
            <div class="text-slate-400">
                © {{ date('Y') }} • Feito por Du
            </div>
        </div>
    </div>
</footer>

</body>
</html>
