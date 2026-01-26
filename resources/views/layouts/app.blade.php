<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- 1. FONTE OUTFIT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        /* 2. SCROLLBAR DARK */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 relative selection:bg-cyan-500 selection:text-white">
    
    {{-- 3. MESH GRADIENT (Luzes de Fundo) --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[30%] h-[30%] rounded-full bg-cyan-600/10 blur-[120px]"></div>
    </div>

    {{-- Conteúdo (z-10 para ficar acima das luzes) --}}
    <div class="relative z-10">
        @include('navigation-menu')

        <div class="min-h-screen">
            @if (isset($header))
                <header class="border-b border-white/10 bg-slate-950/60 backdrop-blur-md">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="text-white font-semibold text-xl">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endif

            <main class="py-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{-- Glassmorphism aplicado no card principal --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl shadow-xl">
                        <div class="p-6">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
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
                            <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-white">
                            {{ session('success') ? 'Sucesso!' : 'Atenção' }}
                        </p>
                        <p class="mt-1 text-sm text-slate-400">
                            {{ session('success') ?? session('error') }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-slate-400 hover:text-white focus:outline-none">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                {{-- Barra de Progresso --}}
                <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r {{ session('success') ? 'from-emerald-500 to-green-400' : 'from-red-500 to-orange-400' }}"
                     style="width: 100%; animation: shrink 5s linear forwards;"></div>
            </div>
        </div>

        <style>
            @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        </style>
    @endif

    @livewireScripts
</body>
</html>