<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    {{-- ✅ WRAPPER ALPINE --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 400)">
        
        {{-- 💀 SKELETON --}}
        <div x-show="!loaded" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 animate-pulse space-y-10">
            {{-- Seção de Criar --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="h-6 w-32 bg-white/5 rounded mb-2"></div>
                    <div class="h-4 w-48 bg-white/5 rounded"></div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white/5 rounded-2xl h-64 border border-white/5"></div>
                </div>
            </div>
            
            {{-- Seção de Listar --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="h-6 w-32 bg-white/5 rounded mb-2"></div>
                    <div class="h-4 w-48 bg-white/5 rounded"></div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white/5 rounded-2xl h-40 border border-white/5"></div>
                </div>
            </div>
        </div>

        {{-- ✅ CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;" 
             class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>