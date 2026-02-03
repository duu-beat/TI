<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                {{ __('Logs do Sistema (Backend)') }}
            </h2>
            
            {{-- ✅ CORREÇÃO 1: Usamos $dispatch para enviar um aviso global de abrir modal --}}
            <button @click="$dispatch('open-clear-modal')" class="px-4 py-2 bg-slate-800 hover:bg-red-900/50 text-slate-300 hover:text-red-200 rounded-lg text-xs font-bold uppercase tracking-wider transition border border-white/10 flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Limpar Arquivo
            </button>
        </div>
    </x-slot>

    {{-- ✅ CORREÇÃO 2: Adicionamos o listener @open-clear-modal.window para capturar o clique --}}
    <div class="py-12" 
         x-data="{ loaded: false, showClearModal: false }" 
         x-init="setTimeout(() => loaded = true, 300)"
         @open-clear-modal.window="showClearModal = true">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 pb-20">
            
            {{-- SKELETON --}}
            <div x-show="!loaded" class="bg-[#0d1117] rounded-xl border border-slate-700 p-4 h-[600px] animate-pulse space-y-4">
                <div class="h-6 w-1/3 bg-slate-800 rounded mb-4"></div>
                <x-skeleton type="text" lines="15" class="opacity-30" />
            </div>

            <div x-show="loaded" style="display: none;"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <div class="bg-[#0d1117] rounded-xl border border-slate-700 shadow-2xl overflow-hidden font-mono text-xs md:text-sm leading-relaxed">
                    
                    {{-- Terminal Bar --}}
                    <div class="bg-slate-800 px-4 py-2 flex items-center justify-between border-b border-slate-700 select-none">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="text-slate-400 ml-3">storage/logs/laravel.log</div>
                        </div>
                        <div class="text-slate-500 text-[10px] uppercase">Read-Only Mode</div>
                    </div>

                    {{-- Log Content --}}
                    <div class="p-4 h-[600px] overflow-y-auto custom-scrollbar space-y-1 bg-[#0d1117] text-slate-300">
                        @forelse($logs as $line)
                            @php
                                $class = 'text-slate-400'; 
                                if (str_contains($line, '.ERROR') || str_contains($line, '.CRITICAL')) $class = 'text-red-400 font-bold bg-red-900/10';
                                elseif (str_contains($line, '.WARNING')) $class = 'text-yellow-400';
                                elseif (str_contains($line, '.INFO')) $class = 'text-blue-400';
                                elseif (str_contains($line, 'Stack trace:')) $class = 'text-slate-600 pl-4';
                                elseif (str_contains($line, '#')) $class = 'text-slate-600 pl-8';
                            @endphp
                            
                            <div class="{{ $class }} break-all hover:bg-white/5 px-2 py-0.5 rounded -mx-2">
                                {{ $line }}
                            </div>
                        @empty
                            <div class="text-center py-20 text-slate-600">
                                <div class="text-4xl mb-2">✨</div>
                                Arquivo de log limpo. Nenhum erro registrado recentemente.
                            </div>
                        @endforelse
                        
                        @if(count($logs) > 0)
                            <div class="text-slate-700 mt-4 pt-4 border-t border-slate-800 text-center italic">
                                ... Exibindo as últimas {{ count($logs) }} linhas ...
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center text-slate-500 text-xs">
                    Dica: Erros críticos geralmente aparecem no topo desta lista (ordem inversa).
                </div>
            </div>

            {{-- MODAL DE CONFIRMAÇÃO --}}
            <div x-show="showClearModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
                 x-transition>
                
                <div class="bg-slate-900 border border-red-500/30 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden relative" 
                     @click.away="showClearModal = false">
                    
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 to-orange-600"></div>

                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/10 mb-4 border border-red-500/20">
                            <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">Limpar Logs?</h3>
                        
                        <p class="text-slate-400 text-sm mb-8">
                            Você está prestes a apagar <strong>todo o histórico</strong> de erros do sistema.
                            <br><br>
                            <span class="text-red-400 font-bold uppercase text-xs">Isso não pode ser desfeito.</span>
                        </p>

                        <form action="{{ route('master.system-logs.clear') }}" method="POST">
                            @csrf
                            <div class="flex justify-center gap-3 mt-2">
                                <button type="button" @click="showClearModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold border border-transparent hover:border-white/10">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 transition text-sm flex items-center gap-2">
                                    Sim, Limpar Tudo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>