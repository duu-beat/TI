@extends('layouts.portal')

@section('title', 'Gerenciar Chamado')

@section('actions')
    <a href="{{ route('admin.tickets.index') }}" class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">Voltar</a>
@endsection

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="space-y-6">

    {{-- ⭐ AVALIAÇÃO DO CLIENTE (VISÍVEL APENAS SE AVALIADO) --}}
    @if($ticket->rating)
        <div class="rounded-2xl bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 p-4 flex items-center gap-4 animate-fade-in">
            <div class="text-4xl text-yellow-400 tracking-widest">
                {{ str_repeat('★', $ticket->rating) }}<span class="text-slate-600 opacity-30">{{ str_repeat('★', 5 - $ticket->rating) }}</span>
            </div>
            <div>
                <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Avaliação do Cliente</div>
                <div class="text-slate-300 text-sm italic">"{{ $ticket->rating_comment ?? 'Sem comentário adicional.' }}"</div>
            </div>
        </div>
    @endif

    {{-- HEADER INFO --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex justify-between items-start gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-700 text-slate-300">#{{ $ticket->id }}</span>
                    <span class="text-sm text-slate-400">{{ $ticket->user->email }}</span>
                </div>
                <h2 class="text-2xl font-bold text-white">{{ $ticket->subject }}</h2>
            </div>
            
            {{-- MUDAR STATUS RAPIDAMENTE --}}
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
                @csrf
                <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-white/20 text-white text-xs rounded-lg px-3 py-2 focus:ring-indigo-500 cursor-pointer hover:bg-slate-800 transition">
                    @foreach(\App\Enums\TicketStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mt-6 p-4 rounded-xl bg-slate-950/30 border border-white/5">
            <p class="text-slate-300 leading-relaxed whitespace-pre-line">{{ $ticket->description }}</p>
        </div>
    </div>

    {{-- CHAT --}}
    <div class="rounded-2xl border border-white/10 bg-slate-950/20 p-6 flex flex-col space-y-6">
        @foreach($ticket->messages as $message)
            @php
                $isMe = $message->user_id === auth()->id();
                $isInternal = $message->is_internal;
                
                // Cores dinâmicas para distinguir notas internas
                $bubbleClass = $isInternal 
                    ? 'bg-yellow-500/10 border border-yellow-500/30 text-yellow-100' // Estilo Nota Interna
                    : ($isMe ? 'bg-indigo-600 text-white border border-indigo-500/50' : 'bg-slate-800 text-slate-200 border border-white/5'); // Normal
            @endphp

            <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="flex max-w-[85%] gap-3 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                    {{-- Avatar --}}
                    <div class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold overflow-hidden border border-white/10
                        {{ $isInternal ? 'bg-yellow-500/20 text-yellow-500' : 'bg-slate-700 text-slate-300' }}">
                        {{ substr($message->user->name, 0, 2) }}
                    </div>

                    {{-- Balão --}}
                    <div class="relative p-4 rounded-2xl text-sm shadow-md {{ $bubbleClass }} {{ $isMe ? 'rounded-tr-none' : 'rounded-tl-none' }}">
                        
                        {{-- Badge de Nota Interna --}}
                        @if($isInternal)
                            <div class="absolute -top-3 left-0 bg-yellow-500 text-slate-900 text-[9px] font-black px-2 py-0.5 rounded shadow-sm flex items-center gap-1">
                                🔒 NOTA INTERNA
                            </div>
                        @endif

                        <div class="flex items-center gap-2 mb-1 opacity-60 text-[10px] uppercase font-bold tracking-wider {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <span>{{ $message->user->name }}</span> • <span>{{ $message->created_at->format('H:i') }}</span>
                        </div>

                        <p class="whitespace-pre-line leading-relaxed">{{ $message->message }}</p>

                        @if($message->attachments->count() > 0)
                            <div class="mt-3 pt-2 border-t border-white/10">
                                @foreach($message->attachments as $attachment)
                                    <a href="{{ $attachment->url }}" target="_blank" class="flex items-center gap-2 p-1.5 rounded hover:bg-black/20 transition">
                                        📎 <span class="underline text-xs">{{ $attachment->file_name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- FORMULÁRIO DE RESPOSTA (ADMIN) --}}
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data">
            @csrf
            
            {{-- CHECKBOX NOTA INTERNA --}}
            <div class="mb-4">
                <label class="inline-flex items-center gap-2 p-2 pr-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 cursor-pointer hover:bg-yellow-500/20 transition select-none">
                    <input type="checkbox" name="is_internal" value="1" class="rounded border-yellow-500/50 bg-slate-900 text-yellow-500 focus:ring-yellow-500 focus:ring-offset-0">
                    <span class="text-xs font-bold text-yellow-500 uppercase tracking-wide">🔒 Nota Interna (Cliente não vê)</span>
                </label>
            </div>

            <textarea name="message" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-indigo-500/20 transition-all" placeholder="Escreva a resposta..." required></textarea>

            <div class="mt-4 flex justify-between items-center">
                <input type="file" name="attachments[]" multiple class="text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20">
                
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 font-bold text-white hover:bg-indigo-500 hover:shadow-lg transition-all">
                    Enviar Resposta
                </button>
            </div>
        </form>
    </div>

</div>
@endsection