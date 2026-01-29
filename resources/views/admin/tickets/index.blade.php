<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span>Gerenciar Chamados</span>
            {{-- Botão Gerar Relatório --}}
            <a href="{{ route('admin.tickets.report') }}" target="_blank"
               class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition flex items-center gap-2">
                📄 <span class="hidden sm:inline">Gerar Relatório</span>
            </a>
        </div>
    </x-slot>

    {{-- BARRA DE FILTROS --}}
    <div class="mb-8 mt-4">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="p-4 rounded-2xl bg-slate-900/50 border border-white/10 flex flex-col md:flex-row gap-4 items-center shadow-lg">
            
            {{-- Busca --}}
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por ID, Assunto ou Cliente..." 
                       class="w-full pl-4 pr-4 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-slate-200 focus:border-cyan-500/50 outline-none">
            </div>

            {{-- Filtro Status --}}
            <select name="status" class="w-full md:w-48 py-2.5 px-4 rounded-xl bg-slate-950 border border-white/10 text-slate-200 outline-none cursor-pointer">
                <option value="">Todos Status</option>
                @foreach(\App\Enums\TicketStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow-lg">
                Filtrar
            </button>
            
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.tickets.index') }}" class="text-sm text-slate-400 hover:text-white underline whitespace-nowrap">Limpar</a>
            @endif
        </form>
    </div>

    {{-- LISTAGEM --}}
    <div class="grid gap-3">
        @forelse($tickets as $ticket)
            <a href="{{ route('admin.tickets.show', $ticket) }}"
               class="block rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10 hover:border-white/20 transition group">
                
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-500">#{{ $ticket->id }}</span>
                            <div class="font-semibold text-white text-lg group-hover:text-cyan-400 transition">
                                {{ $ticket->subject }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                            <span class="text-slate-300">👤 {{ $ticket->user->name }}</span>
                            <span>•</span>
                            <span>{{ $ticket->user->email }}</span>
                        </div>
                    </div>

                    <div class="shrink-0 flex flex-col items-end gap-2">
                        {{-- 🔥 AQUI ESTÁ A MAGIA: Usamos ->color() e ->label() do Enum --}}
                        <span class="px-3 py-1 rounded-full text-[11px] uppercase tracking-wider font-bold border {{ $ticket->status->color() }}">
                            {{ $ticket->status->label() }}
                        </span>
                        <span class="text-xs text-slate-500">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16 rounded-2xl border border-dashed border-white/10 bg-white/5">
                <p class="text-slate-400">Nenhum chamado encontrado com estes filtros.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</x-app-layout>