@extends('layouts.portal')

@section('menu')
    <a href="{{ route('admin.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        📊 Dashboard
    </a>

    <a href="{{ route('admin.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-white/10 text-white">
        🎫 Chamados
    </a>
@endsection

@section('title', 'Chamados')

@section('actions')
    <a href="{{ route('admin.dashboard') }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm hover:bg-white/15">
        Voltar
    </a>
@endsection

@section('content')
<div class="space-y-4">

    @forelse($tickets as $ticket)
        <a href="{{ route('admin.tickets.show', $ticket) }}"
           class="block rounded-2xl border border-white/10 bg-white/5 p-5 hover:bg-white/10 transition">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-white font-semibold">
                        {{ $ticket->subject }}
                    </div>

                    <div class="mt-1 text-sm text-slate-400">
                        Cliente: {{ $ticket->user->name }}
                        • {{ $ticket->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                {{-- Status --}}
                @php
                    $statusColors = [
                        'new' => 'bg-indigo-500/20 text-indigo-300',
                        'in_progress' => 'bg-cyan-500/20 text-cyan-300',
                        'waiting_client' => 'bg-yellow-500/20 text-yellow-300',
                        'resolved' => 'bg-emerald-500/20 text-emerald-300',
                        'closed' => 'bg-slate-500/20 text-slate-300',
                    ];
                @endphp

                <span class="text-xs rounded-full px-3 py-1 font-medium
                    {{ $statusColors[$ticket->status] ?? 'bg-white/10 text-slate-200' }}">
                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                </span>
            </div>
        </a>
    @empty
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-slate-300">
            Nenhum chamado por aqui ainda.
        </div>
    @endforelse

    {{-- Paginação --}}
    @if(method_exists($tickets, 'links'))
        <div class="pt-4">
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
