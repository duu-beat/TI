@extends('layouts.portal')

@section('title', 'Gerenciar Chamados')

@section('actions')
    {{-- Botão para gerar relatório (Exemplo) --}}
    <a href="{{ route('admin.tickets.report') }}" target="_blank"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition flex items-center gap-2">
        📄 Gerar Relatório
    </a>
@endsection

@section('content')
@php
    // Definição de cores para status
    $statusColors = [
        'new' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
        'in_progress' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',
        'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
        'resolved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
        'closed' => 'bg-slate-500/20 text-slate-300 border-slate-500/30',
    ];
@endphp

{{-- BARRA DE FILTROS --}}
<div class="mb-8 mt-4">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="p-4 rounded-2xl bg-slate-900/50 border border-white/10 flex flex-col md:flex-row gap-4 items-center shadow-lg">
        
        {{-- Busca --}}
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Buscar por assunto, ID ou nome do cliente..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-slate-200 placeholder-slate-600 focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 outline-none transition">
        </div>

        {{-- Filtro Status --}}
        <div class="relative w-full md:w-64">
            <select name="status" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-white/10 text-slate-200 focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 outline-none cursor-pointer">
                <option value="">Todos os status</option>
                @foreach(\App\Enums\TicketStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow-lg">
            Filtrar
        </button>

        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.tickets.index') }}" class="text-sm text-slate-400 hover:text-white underline whitespace-nowrap">
                Limpar
            </a>
        @endif
    </form>
</div>

{{-- LISTA DE TICKETS --}}
<div class="space-y-4">
    @forelse($tickets as $ticket)
        <a href="{{ route('admin.tickets.show', $ticket) }}"
           class="relative block rounded-2xl border border-white/10 bg-white/5 p-5 group
                  transition-all duration-300 
                  hover:-translate-y-1 hover:bg-slate-800/80 hover:border-cyan-500/30">
            
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">#{{ $ticket->id }}</span>
                        <div class="font-semibold text-white text-lg group-hover:text-cyan-400 transition">
                            {{ $ticket->subject }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-400">
                        <span class="text-slate-300">👤 {{ $ticket->user->name }}</span>
                        <span>•</span>
                        <span>{{ $ticket->user->email }}</span>
                    </div>
                </div>

                <div class="shrink-0 flex flex-col items-end gap-2">
                    <span class="px-3 py-1 rounded-full text-[11px] uppercase tracking-wider font-bold border {{ $statusColors[$ticket->status->value] ?? '' }}">
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
@endsection