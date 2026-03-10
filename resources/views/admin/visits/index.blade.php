<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    {{ __('Agenda de Visitas Técnicas') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON LOADER --}}
            <div x-show="!loaded" class="space-y-4 animate-pulse">
                <div class="h-16 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- TABELA --}}
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 overflow-hidden shadow-xl backdrop-blur-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5 text-xs uppercase tracking-wider text-slate-400 font-bold">
                                    <th class="px-6 py-4">Data/Hora</th>
                                    <th class="px-6 py-4">Chamado</th>
                                    <th class="px-6 py-4">Técnico</th>
                                    <th class="px-6 py-4">Endereço</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @forelse($visits as $visit)
                                    <tr class="group hover:bg-white/[0.02] transition duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-white">{{ $visit->scheduled_at->format('d/m/Y') }}</div>
                                            <div class="text-[10px] text-slate-500 uppercase tracking-widest mt-0.5">{{ $visit->scheduled_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.tickets.show', $visit->ticket_id) }}" class="text-indigo-400 hover:text-indigo-300 transition font-medium text-sm">
                                                #{{ $visit->ticket_id }} - {{ Str::limit($visit->ticket->title, 20) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="h-7 w-7 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-white border border-white/5">
                                                    {{ substr($visit->technician->name, 0, 1) }}
                                                </div>
                                                <span class="text-slate-300 font-medium">{{ $visit->technician->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-slate-400 max-w-xs truncate text-xs" title="{{ $visit->address }}">
                                                {{ $visit->address }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-{{ $visit->getStatusColor() }}-500/10 text-{{ $visit->getStatusColor() }}-400 border border-{{ $visit->getStatusColor() }}-500/20">
                                                {{ $visit->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.visits.update-status', $visit) }}" method="POST" class="inline-flex gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($visit->status === 'scheduled')
                                                        <button name="status" value="in_transit" class="p-2 rounded-lg bg-slate-800 hover:bg-yellow-500/20 text-slate-400 hover:text-yellow-400 border border-white/5 hover:border-yellow-500/30 transition-all" title="Iniciar Deslocamento">
                                                            🚗
                                                        </button>
                                                    @elseif($visit->status === 'in_transit')
                                                        <button name="status" value="in_service" class="p-2 rounded-lg bg-slate-800 hover:bg-purple-500/20 text-slate-400 hover:text-purple-400 border border-white/5 hover:border-purple-500/30 transition-all" title="Iniciar Atendimento">
                                                            🛠️
                                                        </button>
                                                    @elseif($visit->status === 'in_service')
                                                        <button name="status" value="completed" class="p-2 rounded-lg bg-slate-800 hover:bg-green-500/20 text-slate-400 hover:text-green-400 border border-white/5 hover:border-green-500/30 transition-all" title="Concluir Visita">
                                                            ✅
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="h-16 w-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-white/5">
                                                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <h3 class="text-white font-medium mb-1">Nenhuma visita agendada</h3>
                                                <p class="text-slate-500 text-sm">As visitas técnicas aparecerão aqui após serem agendadas nos chamados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($visits, 'hasPages') && $visits->hasPages())
                        <div class="px-6 py-4 border-t border-white/5 bg-slate-900/50">
                            {{ $visits->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>