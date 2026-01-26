@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-white/10 text-white font-medium shadow-lg shadow-black/20">
        🎫 Meus chamados
    </a>
@endsection

@section('title', 'Meus Chamados')

@section('actions')
    <a href="{{ route('client.tickets.create') }}"
       class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-2.5 text-sm font-bold text-slate-950 
              transition-all duration-300 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:brightness-110 active:scale-95">
        + Novo Chamado
    </a>
@endsection

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">

    {{-- 💀 SKELETON (Lista Vertical) --}}
    <div x-show="!loaded" class="space-y-4 animate-pulse">
        @foreach(range(1, 4) as $i)
            <div class="rounded-2xl border border-white/5 bg-white/5 p-5 h-32">
                <div class="flex justify-between">
                    <div class="space-y-3 w-1/2">
                        <div class="h-6 bg-slate-700/50 rounded w-3/4"></div>
                        <div class="h-4 bg-slate-700/50 rounded w-full"></div>
                    </div>
                    <div class="h-6 w-20 bg-slate-700/50 rounded-full"></div>
                </div>
                <div class="mt-6 flex gap-4">
                    <div class="h-4 w-24 bg-slate-700/50 rounded"></div>
                    <div class="h-4 w-24 bg-slate-700/50 rounded"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ✅ CONTEÚDO REAL --}}
    <div x-show="loaded" style="display: none;"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">
         
        @if($tickets->count() > 0)
            <div class="grid gap-4">
                @foreach($tickets as $ticket)
                    @php
                        $colorClass = match($ticket->status->value) {
                            'new' => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/20',
                            'in_progress' => 'bg-cyan-500/10 text-cyan-300 border-cyan-500/20',
                            'waiting_client' => 'bg-yellow-500/10 text-yellow-300 border-yellow-500/20',
                            'resolved' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                            'closed' => 'bg-slate-500/10 text-slate-300 border-slate-500/20',
                            default => 'bg-white/5 text-slate-200 border-white/10',
                        };
                    @endphp

                    <a href="{{ route('client.tickets.show', $ticket) }}"
                       class="relative block rounded-2xl border border-white/10 bg-white/5 p-5 group
                              transition-all duration-300 
                              hover:-translate-y-1 hover:bg-slate-800/80 hover:border-cyan-500/30 
                              hover:shadow-[0_10px_30px_-10px_rgba(6,182,212,0.15)]">
                        
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="font-semibold text-white text-lg group-hover:text-cyan-400 transition">
                                    {{ $ticket->subject }}
                                </div>
                                <div class="text-sm text-slate-400 line-clamp-1 group-hover:text-slate-300">
                                    {{ Str::limit($ticket->description, 80) }}
                                </div>
                            </div>

                            <div class="shrink-0">
                                <span class="px-3 py-1 rounded-full text-[11px] uppercase tracking-wider font-bold border {{ $colorClass }}">
                                    {{ $ticket->status->label() }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-4 text-xs text-slate-500 border-t border-white/5 pt-3">
                            <div class="flex items-center gap-1.5">
                                <span class="text-slate-600">📅</span> 
                                {{ $ticket->created_at->format('d/m/Y') }}
                            </div>
                            
                            @if($ticket->priority)
                                <div class="flex items-center gap-1.5">
                                    @if($ticket->priority->value === 'high')
                                        🔥 <span class="text-red-400 font-medium">Alta Prioridade</span>
                                    @elseif($ticket->priority->value === 'medium')
                                        ⚠️ <span class="text-yellow-400">Média</span>
                                    @else
                                        🟢 <span class="text-emerald-400">Normal</span>
                                    @endif
                                </div>
                            @endif

                            <div class="ml-auto flex items-center gap-1 text-cyan-500/0 group-hover:text-cyan-400 transition-all duration-300 transform translate-x-[-10px] group-hover:translate-x-0">
                                Ver detalhes →
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-16 text-center rounded-3xl border border-dashed border-white/10 bg-white/5">
                <div class="bg-slate-800/50 p-4 rounded-full mb-4 ring-1 ring-white/10 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-cyan-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">Nenhum chamado encontrado</h3>
                <p class="text-sm text-slate-400 max-w-xs mx-auto mt-1 mb-6">
                    Está tudo tranquilo por aqui! Se precisar de ajuda, estamos à disposição.
                </p>
                
                <a href="{{ route('client.tickets.create') }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-white/10 px-5 py-2.5 text-sm font-bold text-white hover:bg-white/20 hover:scale-105 transition-all duration-300">
                    <span>✨</span> Abrir primeiro chamado
                </a>
            </div>
        @endif
    </div>
</div>
@endsection