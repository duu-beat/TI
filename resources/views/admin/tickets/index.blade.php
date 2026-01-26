@extends('layouts.portal')

@section('menu')
    <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">📊 Dashboard</a>
    <a href="{{ route('admin.tickets.index') }}" class="block rounded-xl px-4 py-2 bg-white/10 text-white">🎫 Chamados</a>
@endsection

@section('title', 'Quadro de Chamados')

@section('actions')
    <div class="flex gap-2">
        <a href="{{ route('admin.tickets.report') }}" target="_blank"
           class="flex items-center gap-2 rounded-xl bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600 transition">
            📄 Baixar PDF
        </a>
        
        <a href="{{ route('admin.dashboard') }}"
           class="rounded-xl bg-white/10 px-4 py-2 text-sm hover:bg-white/15 text-white">
            Voltar
        </a>
    </div>
@endsection

@section('content')
<div class="h-full overflow-x-auto pb-4">
    <div class="flex gap-6 min-w-[1000px]"> {{-- Coluna 1: NOVOS --}}
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="font-bold text-indigo-300">🆕 Novos</h3>
                <span class="bg-indigo-500/20 text-indigo-300 text-xs px-2 py-1 rounded-full">{{ $tickets->where('status', \App\Enums\TicketStatus::NEW)->count() }}</span>
            </div>
            
            <div class="space-y-3">
                @foreach($tickets->where('status', \App\Enums\TicketStatus::NEW) as $ticket)
                    <x-kanban-card :ticket="$ticket" color="border-indigo-500/50" />
                @endforeach
            </div>
        </div>

        {{-- Coluna 2: EM ANDAMENTO --}}
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="font-bold text-cyan-300">⚡ Em Progresso</h3>
                <span class="bg-cyan-500/20 text-cyan-300 text-xs px-2 py-1 rounded-full">
                    {{ $tickets->whereIn('status', [\App\Enums\TicketStatus::IN_PROGRESS, \App\Enums\TicketStatus::WAITING_CLIENT])->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @foreach($tickets->whereIn('status', [\App\Enums\TicketStatus::IN_PROGRESS, \App\Enums\TicketStatus::WAITING_CLIENT]) as $ticket)
                    <x-kanban-card :ticket="$ticket" color="border-cyan-500/50" />
                @endforeach
            </div>
        </div>

        {{-- Coluna 3: FINALIZADOS --}}
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="font-bold text-emerald-300">✅ Resolvidos</h3>
                <span class="bg-emerald-500/20 text-emerald-300 text-xs px-2 py-1 rounded-full">
                    {{ $tickets->whereIn('status', [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED])->count() }}
                </span>
            </div>

            <div class="space-y-3 opacity-60 hover:opacity-100 transition">
                @foreach($tickets->whereIn('status', [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED]) as $ticket)
                    <x-kanban-card :ticket="$ticket" color="border-emerald-500/50" />
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- Paginação --}}
<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endsection