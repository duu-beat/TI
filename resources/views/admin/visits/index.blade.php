<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                📅 Agenda de Visitas Técnicas
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/50 backdrop-blur-xl border border-white/10 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6">
                    @if($visits->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-slate-500 text-5xl mb-4">📅</div>
                            <h3 class="text-lg font-medium text-slate-300">Nenhuma visita agendada</h3>
                            <p class="text-slate-500">As visitas técnicas aparecerão aqui após serem agendadas nos chamados.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-white/5 text-slate-400 text-xs uppercase tracking-wider">
                                        <th class="px-6 py-4 font-medium">Data/Hora</th>
                                        <th class="px-6 py-4 font-medium">Chamado</th>
                                        <th class="px-6 py-4 font-medium">Técnico</th>
                                        <th class="px-6 py-4 font-medium">Endereço</th>
                                        <th class="px-6 py-4 font-medium">Status</th>
                                        <th class="px-6 py-4 font-medium text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($visits as $visit)
                                        <tr class="hover:bg-white/5 transition group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $visit->scheduled_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-slate-500">{{ $visit->scheduled_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('admin.tickets.show', $visit->ticket_id) }}" class="text-cyan-400 hover:underline text-sm">
                                                    #{{ $visit->ticket_id }} - {{ Str::limit($visit->ticket->title, 20) }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-white">
                                                        {{ substr($visit->technician->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-sm text-slate-300">{{ $visit->technician->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-slate-400 max-w-xs truncate" title="{{ $visit->address }}">
                                                    {{ $visit->address }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-{{ $visit->getStatusColor() }}-500/10 text-{{ $visit->getStatusColor() }}-400 border border-{{ $visit->getStatusColor() }}-500/20">
                                                    {{ $visit->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <form action="{{ route('admin.visits.update-status', $visit) }}" method="POST" class="inline-flex gap-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($visit->status === 'scheduled')
                                                            <button name="status" value="in_transit" class="p-1.5 rounded-lg bg-white/5 text-slate-400 hover:text-yellow-400 hover:bg-yellow-400/10 transition" title="Iniciar Deslocamento">
                                                                🚗
                                                            </button>
                                                        @elseif($visit->status === 'in_transit')
                                                            <button name="status" value="in_service" class="p-1.5 rounded-lg bg-white/5 text-slate-400 hover:text-purple-400 hover:bg-purple-400/10 transition" title="Iniciar Atendimento">
                                                                🛠️
                                                            </button>
                                                        @elseif($visit->status === 'in_service')
                                                            <button name="status" value="completed" class="p-1.5 rounded-lg bg-white/5 text-slate-400 hover:text-green-400 hover:bg-green-400/10 transition" title="Concluir Visita">
                                                                ✅
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $visits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
