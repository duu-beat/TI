<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
    @include('navigation-menu')

    <div class="min-h-screen">
        @if (isset($header))
            <header class="border-b border-white/10 bg-slate-950/60 backdrop-blur">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="text-white">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endif

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/5 border border-white/10 rounded-2xl shadow-sm">
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
