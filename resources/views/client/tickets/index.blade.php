@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 text-white border border-white/10">
        🎫 Meus chamados
    </a>
@endsection

@section('title', 'Meus Chamados')

@section('actions')
    <a href="{{ route('client.tickets.create') }}"
       class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:opacity-95 transition">
        + Novo Chamado
    </a>
@endsection

@section('content')
    @if($tickets->count() > 0)
        <div class="space-y-4">
            @foreach($tickets as $ticket)
                @php
                    // Definição de cores baseada no valor do Enum
                    $colorClass = match($ticket->status->value) {
                        'new' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                        'in_progress' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',
                        'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                        'resolved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'closed' => 'bg-slate-500/20 text-slate-300 border-slate-500/30',
                        default => 'bg-white/10 text-slate-200 border-white/10',
                    };
                @endphp

                <a href="{{ route('client.tickets.show', $ticket) }}"
                   class="block rounded-2xl border border-white/10 bg-white/5 p-5 hover:bg-white/10 hover:border-white/20 transition group">
                    
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <div class="font-semibold text-white group-hover:text-cyan-400 transition">
                                {{ $ticket->subject }}
                            </div>
                            <div class="text-sm text-slate-400 line-clamp-1">
                                {{ Str::limit($ticket->description, 80) }}
                            </div>
                        </div>

                        {{-- Status Pill --}}
                        <div class="shrink-0">
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $colorClass }}">
                                {{ $ticket->status->label() }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-4 text-xs text-slate-500">
                        <div class="flex items-center gap-1">
                            📅 {{ $ticket->created_at->format('d/m/Y') }}
                        </div>
                        
                        @if($ticket->priority)
                            <div class="flex items-center gap-1">
                                @if($ticket->priority->value === 'high')
                                    🔥 <span class="text-red-400">Alta Prioridade</span>
                                @elseif($ticket->priority->value === 'medium')
                                    ⚠️ Média Prioridade
                                @else
                                    🟢 Baixa Prioridade
                                @endif
                            </div>
                        @endif

                        <div class="ml-auto flex items-center gap-1 group-hover:translate-x-1 transition duration-300">
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
        {{-- Empty State --}}
        <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-800/50">
                🎫
            </div>
            <h3 class="mt-4 text-lg font-semibold text-white">Nenhum chamado encontrado</h3>
            <p class="mt-2 text-sm text-slate-400">
                Precisa de ajuda? Abra um novo chamado agora mesmo.
            </p>
            <div class="mt-6">
                <a href="{{ route('client.tickets.create') }}"
                   class="inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:opacity-95 transition">
                    + Abrir Chamado
                </a>
            </div>
        </div>
    @endif
@endsection