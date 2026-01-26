@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10">
        🏠 Início
    </a>

    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2 bg-white/10 text-white">
        🎫 Meus chamados
    </a>
@endsection

@section('title')
    {{ $ticket->subject }}
@endsection

@section('actions')
    <a href="{{ route('client.tickets.index') }}"
       class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
        Voltar
    </a>
@endsection

@section('content')
@php
    $statusColors = [
        \App\Enums\TicketStatus::NEW->value => 'bg-indigo-500/20 text-indigo-300',
        \App\Enums\TicketStatus::IN_PROGRESS->value => 'bg-cyan-500/20 text-cyan-300',
        \App\Enums\TicketStatus::WAITING_CLIENT->value => 'bg-yellow-500/20 text-yellow-300',
        \App\Enums\TicketStatus::RESOLVED->value => 'bg-emerald-500/20 text-emerald-300',
        \App\Enums\TicketStatus::CLOSED->value => 'bg-slate-500/20 text-slate-300',
    ];

    // Se estiver usando Enums, pode usar ->label(), senão usa o array manual
    $statusLabels = [
        'new' => 'Novo',
        'in_progress' => 'Em andamento',
        'waiting_client' => 'Aguardando você',
        'resolved' => 'Resolvido',
        'closed' => 'Fechado',
    ];
@endphp

{{-- Área de Mensagens (Chat Style) --}}
<div class="rounded-2xl border border-white/10 bg-slate-950/20 p-6 flex flex-col space-y-6">
    <div class="text-sm text-slate-400 mb-2 text-center">Início da conversa</div>

    @foreach($ticket->messages as $message)
        @php
            $isMe = $message->user_id === auth()->id();
        @endphp

        <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
            <div class="flex max-w-[85%] md:max-w-[70%] gap-3 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                
                {{-- Avatar (Iniciais) --}}
                <div class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold
                    {{ $isMe ? 'bg-cyan-500 text-slate-900' : 'bg-slate-700 text-slate-300' }}">
                    {{ substr($message->user->name, 0, 2) }}
                </div>

                {{-- Balão da Mensagem --}}
                <div class="relative p-4 text-sm shadow-md
                    {{ $isMe 
                        ? 'bg-cyan-600/90 text-white rounded-2xl rounded-tr-none' 
                        : 'bg-slate-800 text-slate-200 rounded-2xl rounded-tl-none' 
                    }}">
                    
                    {{-- Nome e Data --}}
                    <div class="flex items-center gap-2 mb-1 opacity-70 text-[10px] uppercase font-bold tracking-wider
                        {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <span>{{ $message->user->name }}</span>
                        <span>•</span>
                        <span>{{ $message->created_at->format('H:i') }}</span>
                    </div>

                    {{-- Texto --}}
                    <p class="whitespace-pre-line leading-relaxed">{{ $message->message }}</p>

                    {{-- Anexos --}}
                    @if($message->attachments->count() > 0)
                        <div class="mt-3 pt-2 border-t {{ $isMe ? 'border-white/20' : 'border-slate-600' }}">
                            @foreach($message->attachments as $attachment)
                                <a href="{{ $attachment->url }}" target="_blank" 
                                   class="flex items-center gap-2 p-2 rounded-lg transition
                                   {{ $isMe ? 'hover:bg-white/10' : 'hover:bg-slate-700' }}">
                                    <span class="text-lg">📎</span>
                                    <span class="text-xs underline truncate">{{ $attachment->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection