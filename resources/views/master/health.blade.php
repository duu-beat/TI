<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            {{ __('Saúde e Diagnóstico da Infraestrutura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Painel Principal --}}
            <div class="bg-slate-800 rounded-2xl border border-white/10 overflow-hidden shadow-xl p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white">Status dos Componentes do Sistema</h3>
                    <p class="text-sm text-slate-400">Monitoramento em tempo real dos serviços críticos da aplicação.</p>
                </div>

                {{-- Grid de Diagnósticos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($health as $key => $item)
                        @if(is_array($item))
                            @php
                                $isOk = ($item['status'] === 'ok');
                            @endphp
                            <div class="p-4 rounded-xl border transition {{ $isOk ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-400' }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold uppercase text-xs tracking-wider text-slate-300">{{ $key }}</span>
                                    <span class="px-2 py-0.5 text-xs font-bold rounded uppercase tracking-wider border {{ $isOk ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30' }}">
                                        {{ strtoupper($item['status']) }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-300 font-medium">{{ $item['message'] }}</p>
                            </div>
                        @else
                            <div class="p-4 rounded-xl border border-white/10 bg-slate-900/50">
                                <span class="font-bold uppercase text-xs tracking-wider text-slate-400">{{ $key }}</span>
                                <p class="mt-2 text-sm text-slate-200 font-mono">{{ $item }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>