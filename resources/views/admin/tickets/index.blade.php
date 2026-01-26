@extends('layouts.portal')

@section('menu')
    <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">📊 Dashboard</a>
    <a href="{{ route('admin.tickets.index') }}" class="block rounded-xl px-4 py-2 bg-white/10 text-white font-medium shadow-lg shadow-black/20">🎫 Chamados</a>
@endsection

@section('title', 'Quadro de Chamados')

@section('actions')
    <div class="flex gap-2">
        <a href="{{ route('admin.tickets.report') }}" target="_blank"
           class="flex items-center gap-2 rounded-xl bg-slate-800/80 px-4 py-2 text-sm font-semibold text-white border border-white/5 hover:bg-slate-700 hover:-translate-y-0.5 transition-all duration-300">
            📄 Baixar PDF
        </a>
        <a href="{{ route('admin.dashboard') }}"
           class="rounded-xl bg-white/5 px-4 py-2 text-sm text-slate-300 border border-white/5 hover:bg-white/10 hover:text-white transition-all">
            Voltar
        </a>
    </div>
@endsection

@section('content')
<div class="h-full overflow-x-auto pb-4" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">
    
    {{-- 💀 SKELETON KANBAN (3 Colunas) --}}
    <div x-show="!loaded" class="flex gap-6 min-w-[1000px] animate-pulse">
        @foreach(range(1, 3) as $col)
            <div class="w-1/3 space-y-4">
                {{-- Header da Coluna --}}
                <div class="h-10 bg-slate-800/50 rounded-xl w-full"></div>
                {{-- Cards --}}
                <div class="h-32 bg-slate-800/30 rounded-xl border border-white/5"></div>
                <div class="h-32 bg-slate-800/30 rounded-xl border border-white/5"></div>
                <div class="h-32 bg-slate-800/30 rounded-xl border border-white/5"></div>
            </div>
        @endforeach
    </div>

    {{-- ✅ CONTEÚDO REAL --}}
    <div x-show="loaded" style="display: none;"
         class="flex gap-6 min-w-[1000px]"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Coluna 1: NOVOS (Indigo) --}}
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-indigo-500/10 border border-indigo-500/10 backdrop-blur-sm">
                <h3 class="font-bold text-indigo-200 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-indigo-400 shadow-[0_0_10px_rgba(129,140,248,0.8)]"></span>
                    Novos
                </h3>
                <span class="bg-indigo-500/20 text-indigo-300 text-xs font-bold px-2 py-0.5 rounded-lg border border-indigo-500/20">
                    {{ $tickets->where('status', \App\Enums\TicketStatus::NEW)->count() }}
                </span>
            </div>
            
            <div class="space-y-3 p-1">
                @foreach($tickets->where('status', \App\Enums\TicketStatus::NEW) as $ticket)
                    <x-kanban-card :ticket="$ticket" color="border-indigo-500" glow="shadow-indigo-500/20" />
                @endforeach
                @if($tickets->where('status', \App\Enums\TicketStatus::NEW)->count() === 0)
                   <div class="text-center py-8 opacity-40 border-2 border-dashed border-indigo-500/20 rounded-xl">
                       <div class="text-2xl mb-1">📭</div>
                       <div class="text-xs text-indigo-200">Sem novos chamados</div>
                   </div>
                @endif
            </div>
        </div>

        {{-- Coluna 2: EM ANDAMENTO (Cyan) --}}
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-cyan-500/10 border border-cyan-500/10 backdrop-blur-sm">
                <h3 class="font-bold text-cyan-200 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-cyan-400 shadow-[0_0_10px_rgba(34,211,238,0.8)] animate-pulse"></span>
                    Em Progresso
                </h3>
                <span class="bg-cyan-500/20 text-cyan-300 text-xs font-bold px-2 py-0.5 rounded-lg border border-cyan-500/20">
                    {{ $tickets->whereIn('status', [\App\Enums\TicketStatus::IN_PROGRESS, \App\Enums\TicketStatus::WAITING_CLIENT])->count() }}
                </span>
            </div>

            <div class="space-y-3 p-1">
                @foreach($tickets->whereIn('status', [\App\Enums\TicketStatus::IN_PROGRESS, \App\Enums\TicketStatus::WAITING_CLIENT]) as $ticket)
                    <x-kanban-card :ticket="$ticket" color="border-cyan-500" glow="shadow-cyan-500/20" />
                @endforeach
            </div>
        </div>

        {{-- Coluna 3: FINALIZADOS (Emerald) --}}
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/10 backdrop-blur-sm">
                <h3 class="font-bold text-emerald-200 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)]"></span>
                    Resolvidos
                </h3>
                <span class="bg-emerald-500/20 text-emerald-300 text-xs font-bold px-2 py-0.5 rounded-lg border border-emerald-500/20">
                    {{ $tickets->whereIn('status', [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED])->count() }}
                </span>
            </div>

            <div class="space-y-3 p-1 opacity-70 hover:opacity-100 transition duration-500">
                @foreach($tickets->whereIn('status', [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED]) as $ticket)
                    <x-kanban-card :ticket="$ticket" color="border-emerald-500" glow="shadow-emerald-500/10" />
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection