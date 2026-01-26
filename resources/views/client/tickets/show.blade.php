@extends('layouts.portal')

@section('menu')
    <a href="{{ route('client.dashboard') }}" class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">🏠 Início</a>
    <a href="{{ route('client.tickets.index') }}" class="block rounded-xl px-4 py-2 text-slate-300 hover:bg-white/10 transition">🎫 Meus chamados</a>
@endsection

@section('title', 'Detalhes do Chamado')

@section('actions')
    <a href="{{ route('client.tickets.index') }}" class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">Voltar</a>
@endsection

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="space-y-6">

    {{-- 💀 SKELETON CHAT --}}
    <div x-show="!loaded" class="space-y-6 animate-pulse">
        {{-- Header Info --}}
        <div class="rounded-2xl bg-white/5 border border-white/5 p-6 h-32 w-full"></div>
        
        {{-- Chat Area --}}
        <div class="rounded-2xl bg-slate-950/20 border border-white/5 p-6 space-y-6">
            {{-- Balão Esquerda --}}
            <div class="flex justify-start">
                <div class="h-20 w-2/3 bg-slate-800/50 rounded-2xl rounded-tl-none"></div>
            </div>
            {{-- Balão Direita --}}
            <div class="flex justify-end">
                <div class="h-16 w-1/2 bg-slate-700/50 rounded-2xl rounded-tr-none"></div>
            </div>
             {{-- Balão Esquerda --}}
             <div class="flex justify-start">
                <div class="h-24 w-3/4 bg-slate-800/50 rounded-2xl rounded-tl-none"></div>
            </div>
        </div>
    </div>

    {{-- ✅ CONTEÚDO REAL --}}
    <div x-show="loaded" style="display: none;"
         class="space-y-6"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">
         
         {{-- COPIAR O CONTEÚDO ORIGINAL DO ARQUIVO show.blade.php AQUI --}}
         {{-- (Header Info, Descrição, Chat e Formulário) --}}
         {{-- Se quiseres que eu cole o código COMPLETO do show.blade.php aqui também, avisa! --}}
         {{-- Mas basicamente é envolver todo o teu conteúdo atual nesta div --}}
         
         {{-- Vou colar o HEADER + CHAT atualizado que já tens --}}
         @php
            $colorClass = match($ticket->status->value) {
                'new' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                'in_progress' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',
                'waiting_client' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                'resolved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                'closed' => 'bg-slate-500/20 text-slate-300 border-slate-500/30',
                default => 'bg-white/10 text-slate-200 border-white/10',
            };
        @endphp

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-xl font-bold text-white mb-2">{{ $ticket->subject }}</h2>
                    <div class="flex items-center gap-3 text-sm text-slate-400">
                        <span>#{{ $ticket->id }}</span>
                        <span>•</span>
                        <span>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border {{ $colorClass }}">
                    {{ $ticket->status->label() }}
                </span>
            </div>
            <div class="mt-6 pt-6 border-t border-white/10">
                <div class="text-sm font-semibold text-slate-300 mb-2">Descrição original</div>
                <p class="text-slate-200 leading-relaxed whitespace-pre-line">{{ $ticket->description }}</p>
                
                @if($ticket->attachment)
                    <div class="mt-4">
                        <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900/50 border border-white/10 hover:bg-slate-900 hover:border-cyan-500/50 transition group">
                            <span class="text-xl">📎</span>
                            <span class="text-sm text-cyan-400 group-hover:underline">Ver anexo original</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Área de Mensagens (Chat Style) --}}
        <div class="rounded-2xl border border-white/10 bg-slate-950/20 p-6 flex flex-col space-y-6">
            <div class="text-sm text-slate-400 mb-2 text-center">Início da conversa</div>

            @foreach($ticket->messages as $message)
                @php
                    $isMe = $message->user_id === auth()->id();
                @endphp

                <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="flex max-w-[85%] md:max-w-[70%] gap-3 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                        <div class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold overflow-hidden shadow-lg
                            {{ $isMe ? 'bg-cyan-500 text-slate-900' : 'bg-slate-700 text-slate-300' }}">
                             @if($message->user->profile_photo_url)
                                <img src="{{ $message->user->profile_photo_url }}" class="h-full w-full object-cover">
                             @else
                                {{ substr($message->user->name, 0, 2) }}
                             @endif
                        </div>

                        <div class="relative p-4 text-sm shadow-md transition hover:scale-[1.01]
                            {{ $isMe 
                                ? 'bg-cyan-600/90 text-white rounded-2xl rounded-tr-none shadow-cyan-900/20' 
                                : 'bg-slate-800 text-slate-200 rounded-2xl rounded-tl-none border border-white/5' 
                            }}">
                            
                            <div class="flex items-center gap-2 mb-1 opacity-70 text-[10px] uppercase font-bold tracking-wider
                                {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                <span>{{ $message->user->name }}</span>
                                <span>•</span>
                                <span>{{ $message->created_at->format('H:i') }}</span>
                            </div>

                            <p class="whitespace-pre-line leading-relaxed">{{ $message->message }}</p>

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

        {{-- Formulário de Resposta --}}
        @if($ticket->status !== \App\Enums\TicketStatus::CLOSED)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm text-slate-300 font-medium ml-1">Sua resposta</label>
                        <textarea name="message" rows="3"
                                  class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                                         focus:border-cyan-400/60 focus:ring-cyan-400/20 transition-all duration-300"
                                  placeholder="Escreva uma mensagem..." required></textarea>
                    </div>

                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        {{-- Mini Upload --}}
                        <div class="relative">
                            <input type="file" name="attachments[]" multiple id="mini-upload" class="hidden" onchange="document.getElementById('file-count').innerText = this.files.length + ' arquivos'">
                            <label for="mini-upload" class="cursor-pointer inline-flex items-center gap-2 text-xs text-slate-400 hover:text-cyan-400 transition">
                                <span class="text-lg">📎</span> Anexar arquivos
                            </label>
                            <span id="file-count" class="text-[10px] text-slate-500 ml-2"></span>
                        </div>

                        <button type="submit"
                                class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-2.5 font-bold text-slate-950 hover:shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:scale-105 transition-all">
                            Enviar 🚀
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/5 text-center text-slate-400">
                Este chamado foi encerrado e não aceita mais respostas.
            </div>
        @endif
    </div>
</div>
@endsection