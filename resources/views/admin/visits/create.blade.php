<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tickets.show', $ticket) }}" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                📅 Agendar Visita Técnica - Chamado #{{ $ticket->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/50 backdrop-blur-xl border border-white/10 overflow-hidden shadow-xl sm:rounded-2xl">
                <form action="{{ route('admin.visits.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Data e Hora --}}
                        <div class="space-y-2">
                            <label for="scheduled_at" class="block text-sm font-medium text-slate-400">Data e Hora da Visita</label>
                            <input type="datetime-local" 
                                   name="scheduled_at" 
                                   id="scheduled_at" 
                                   required
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-white focus:ring-cyan-500 focus:border-cyan-500 transition">
                            @error('scheduled_at') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Endereço --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-slate-400">Endereço Completo</label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="3" 
                                      required
                                      placeholder="Rua, número, bairro, cidade e pontos de referência..."
                                      class="w-full bg-slate-950 border-white/10 rounded-xl text-white focus:ring-cyan-500 focus:border-cyan-500 transition"></textarea>
                            @error('address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Observações --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-slate-400">Observações Internas (Opcional)</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="2" 
                                      placeholder="Instruções para o técnico ou detalhes do problema..."
                                      class="w-full bg-slate-950 border-white/10 rounded-xl text-white focus:ring-cyan-500 focus:border-cyan-500 transition"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:text-white transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-8 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold shadow-lg shadow-cyan-900/20 transition">
                            Confirmar Agendamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
