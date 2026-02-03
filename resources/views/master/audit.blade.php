<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight">
            {{ __('Logs de Auditoria') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON --}}
            <div x-show="!loaded" class="bg-black/80 rounded-xl border border-white/10 p-4 h-[600px] space-y-4 animate-pulse">
                <div class="flex gap-2 mb-4 border-b border-white/5 pb-2">
                    <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                    <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                    <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                </div>
                <x-skeleton type="text" lines="12" class="opacity-50" />
            </div>

            {{-- CONTEÚDO --}}
            <div x-show="loaded" style="display: none;"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="bg-black/80 rounded-xl border border-white/10 shadow-2xl overflow-hidden font-mono text-sm">
                    <div class="bg-white/5 px-4 py-2 flex items-center gap-2 border-b border-white/5">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                        </div>
                        <div class="text-xs text-slate-500 ml-2">root@server:~/logs/security.log</div>
                    </div>

                    <div class="p-6 h-[600px] overflow-y-auto">
                        @forelse($logs as $log)
                            @php
                                $color = match($log->level) {
                                    'DANGER' => 'text-red-500',
                                    'WARNING' => 'text-yellow-400',
                                    'SUCCESS' => 'text-green-400',
                                    default => 'text-blue-400',
                                };
                            @endphp
                            <div class="flex gap-4 hover:bg-white/5 p-1 rounded transition mb-1">
                                <span class="text-slate-600 shrink-0">[{{ $log->created_at->format('Y-m-d H:i:s') }}]</span>
                                <span class="{{ $color }} font-bold shrink-0 w-20 uppercase">[{{ $log->level }}]</span>
                                <span class="text-slate-300">
                                    <span class="text-white font-bold">{{ $log->user->name ?? 'System' }}:</span> 
                                    {{ $log->action }} 
                                    @if($log->description) <span class="text-slate-500">- {{ $log->description }}</span> @endif
                                    <span class="text-slate-700 text-xs ml-2">IP: {{ $log->ip_address }}</span>
                                </span>
                            </div>
                        @empty
                            <div class="text-slate-600">Nenhum log registrado ainda.</div>
                        @endforelse
                        
                        {{-- Paginação --}}
                        <div class="mt-4 pt-4 border-t border-white/10">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>