<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('client.tickets.index') }}" 
                   class="group flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-400 transition hover:bg-indigo-500 hover:text-white hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-500/30">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-white leading-tight">Chamado #{{ $ticket->id }}</h2>
                    <p class="text-xs text-slate-400 hidden sm:block">Acompanhe o andamento da sua solicitação</p>
                </div>
            </div>
            
            {{-- Status no Header --}}
                <div class="scale-110 ml-4">
                    <x-ticket-status :status="$ticket->status"/>
                </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false, replying: false }" x-init="setTimeout(() => loaded = true, 300)" class="pb-24 relative min-h-screen">

        {{-- SKELETON LOADER --}}
        <div x-show="!loaded" class="space-y-6 animate-pulse">
            <div class="h-56 bg-white/5 rounded-3xl w-full border border-white/5"></div>
            <div class="space-y-8 px-4">
                <div class="h-16 bg-white/5 rounded-2xl w-2/3 mr-auto"></div>
                <div class="h-16 bg-white/5 rounded-2xl w-2/3 ml-auto"></div>
                <div class="h-16 bg-white/5 rounded-2xl w-2/3 mr-auto"></div>
            </div>
        </div>

        {{-- CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;"
             class="space-y-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
             
            {{-- 1. CARD PRINCIPAL (Estilo Holográfico) --}}
            <div class="relative group rounded-3xl bg-slate-900/60 border border-white/10 p-1 backdrop-blur-xl shadow-2xl">
                {{-- Borda Gradiente Animada --}}
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 via-cyan-500/20 to-purple-500/20 rounded-3xl opacity-50 group-hover:opacity-100 transition duration-700"></div>
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 rounded-[2rem] blur-xl opacity-0 group-hover:opacity-50 transition duration-700"></div>
                
                <div class="relative bg-slate-950/80 rounded-[1.4rem] p-6 sm:p-8 overflow-hidden">
                    {{-- Badge Categoria --}}
                    <div class="absolute top-0 right-0 p-6 opacity-20 pointer-events-none">
                        <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                            @php
                                $iconPath = match($ticket->category ?? 'other') {
                                    'hardware' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', // Monitor
                                    'software' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', // Code
                                    'network'  => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', // Network
                                    default    => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' // Doc
                                };
                            @endphp
                            <path d="{{ $iconPath }}"></path>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-wider shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                                {{ $ticket->category ?? 'Geral' }}
                            </span>
                            <span class="text-slate-500 text-sm font-medium flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $ticket->created_at->format('d/m/Y \à\s H:i') }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6 leading-tight drop-shadow-md">
                            {{ $ticket->subject }}
                        </h1>

                        <div class="prose prose-invert max-w-none text-slate-300 leading-relaxed bg-black/20 p-5 rounded-2xl border border-white/5">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>

                        {{-- Anexos Originais --}}
                        @if(count($ticket->messages) > 0 && $ticket->messages->first()->attachments->count() > 0)
                            <div class="mt-6 pt-6 border-t border-white/5">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Anexos Originais</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($ticket->messages->first()->attachments as $attachment)
                                        <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                           class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-900 border border-white/10 hover:border-cyan-500/50 hover:shadow-[0_0_15px_rgba(6,182,212,0.15)] transition-all duration-300">
                                            <div class="h-10 w-10 rounded-lg bg-slate-800 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-200 group-hover:text-white transition">Ver Anexo</div>
                                                <div class="text-[10px] text-slate-500 uppercase">Download</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. CHAT / TIMELINE --}}
            <div class="relative py-4 space-y-8">
                
                {{-- Divisor --}}
                <div class="flex items-center justify-center gap-4 opacity-50">
                    <div class="h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent w-full max-w-xs"></div>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-widest bg-slate-950 px-2">Histórico</span>
                    <div class="h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent w-full max-w-xs"></div>
                </div>

                @foreach($ticket->messages->skip(1) as $message)
                    @if(!$message->is_internal)
                        @php $isMe = $message->user_id === auth()->id(); @endphp

                        <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }} group animate-fade-in-up">
                            
                            {{-- Layout da Mensagem --}}
                            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[90%] md:max-w-[75%] gap-2">
                                
                                {{-- Cabeçalho da Mensagem --}}
                                <div class="flex items-center gap-3 px-1 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                                    
                                    {{-- Avatar --}}
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold border border-white/10 shadow-lg overflow-hidden
                                                {{ $isMe ? 'bg-gradient-to-br from-indigo-500 to-cyan-600 text-white' : 'bg-slate-800 text-slate-300' }}">
                                        @if($message->user->profile_photo_url)
                                            <img src="{{ $message->user->profile_photo_url }}" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($message->user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    
                                    <span class="text-xs font-bold {{ $isMe ? 'text-indigo-400' : 'text-slate-300' }}">
                                        {{ $isMe ? 'Você' : $message->user->name }}
                                        @if(!$isMe) <span class="ml-1 px-1.5 py-0.5 rounded bg-slate-800 text-[10px] text-slate-500 uppercase border border-white/5">Suporte</span> @endif
                                    </span>
                                    
                                    <span class="text-[10px] text-slate-600">{{ $message->created_at->format('H:i') }}</span>
                                </div>

                                {{-- Bolha da Mensagem --}}
                                <div class="relative px-6 py-4 rounded-2xl shadow-lg transition-all hover:scale-[1.01] duration-200 border
                                            {{ $isMe 
                                                ? 'bg-gradient-to-br from-indigo-600 to-indigo-800 border-indigo-500/30 text-white rounded-tr-sm shadow-indigo-900/20' 
                                                : 'bg-slate-900 border-white/10 text-slate-200 rounded-tl-sm shadow-black/30' }}">
                                    
                                    <p class="whitespace-pre-wrap leading-relaxed text-sm sm:text-base">{{ $message->message }}</p>

                                    @if($message->attachments->count() > 0)
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($message->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                                   class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-black/30 hover:bg-black/50 border border-white/10 transition text-xs font-medium backdrop-blur-sm group/file">
                                                    <svg class="w-4 h-4 text-white/70 group-hover/file:text-cyan-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                    <span class="truncate max-w-[120px]">{{ $attachment->file_name ?? 'Anexo' }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- 3. ÁREA DE AÇÃO FLUTUANTE --}}
            @if(!in_array($ticket->status, [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED]))
                
                {{-- Barra de Resposta Flutuante --}}
                <div class="fixed bottom-6 left-0 right-0 z-40 px-4 sm:px-6 pointer-events-none">
                    <div class="max-w-4xl mx-auto pointer-events-auto">
                        <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" enctype="multipart/form-data" 
                              class="relative rounded-[20px] bg-slate-900/90 border border-white/10 shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] backdrop-blur-xl transition-all focus-within:border-indigo-500/50 focus-within:shadow-[0_0_30px_rgba(99,102,241,0.2)]">
                            @csrf
                            
                            <div class="flex items-end gap-2 p-2">
                                {{-- Upload Button --}}
                                <div class="relative shrink-0">
                                    <input type="file" name="attachments[]" multiple id="file-upload" class="hidden" 
                                           onchange="document.getElementById('upload-icon').classList.add('text-emerald-400');">
                                    
                                    <label for="file-upload" class="flex h-10 w-10 items-center justify-center rounded-xl hover:bg-white/10 text-slate-400 cursor-pointer transition active:scale-95" title="Anexar Arquivos">
                                        <svg id="upload-icon" class="w-6 h-6 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </label>
                                </div>

                                {{-- Text Area --}}
                                <textarea name="message" rows="1" 
                                          class="w-full bg-transparent border-0 text-white placeholder:text-slate-500 focus:ring-0 resize-none py-3 max-h-32 overflow-y-auto custom-scrollbar"
                                          placeholder="Escreva sua resposta aqui..." 
                                          required
                                          oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 128) + 'px'"></textarea>

                                {{-- Send Button --}}
                                <button type="submit" 
                                        class="shrink-0 h-10 w-10 sm:w-auto sm:px-6 flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all hover:scale-105 active:scale-95 group">
                                    <span class="hidden sm:block font-bold text-sm">Enviar</span>
                                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Espaçador para o rodapé flutuante não tapar o conteúdo --}}
                <div class="h-24"></div>

            @else
                
                {{-- Área de Avaliação (Estática no final) --}}
                <div class="mt-12 max-w-2xl mx-auto">
                    @if(session('success') && $ticket->rating)
                        <div class="p-8 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 text-center animate-fade-in shadow-[0_0_40px_rgba(16,185,129,0.1)]">
                            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-emerald-500/20 text-4xl mb-6 shadow-inner animate-bounce">🎉</div>
                            <h3 class="text-3xl font-bold text-white mb-2">Obrigado!</h3>
                            <p class="text-emerald-200/80 mb-6">Sua avaliação foi registrada com sucesso.</p>
                            <div class="inline-flex gap-2 p-3 bg-black/20 rounded-2xl border border-white/5">
                                @foreach(range(1, 5) as $i)
                                    <span class="text-3xl {{ $i <= $ticket->rating ? 'text-yellow-400 drop-shadow-md' : 'text-slate-700' }}">★</span>
                                @endforeach
                            </div>
                        </div>
                    @elseif(!$ticket->rating)
                        <div class="relative overflow-hidden rounded-[2rem] bg-slate-900 border border-white/10 p-10 text-center shadow-2xl">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>
                            
                            <h3 class="text-2xl font-bold text-white mb-3">Chamado Encerrado</h3>
                            <p class="text-slate-400 mb-8">Por favor, avalie a qualidade do nosso atendimento.</p>

                            <form action="{{ route('client.tickets.rate', $ticket) }}" method="POST" class="space-y-8 relative z-10">
                                @csrf
                                <div class="flex justify-center gap-4 flex-row-reverse group">
                                    @foreach(range(5, 1) as $i)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="peer hidden" required>
                                        <label for="star{{ $i }}" 
                                               class="cursor-pointer text-5xl text-slate-700 transition-all duration-300 
                                                      peer-checked:text-yellow-400 peer-checked:scale-110 peer-checked:drop-shadow-[0_0_10px_rgba(250,204,21,0.5)]
                                                      peer-hover:text-yellow-400 peer-hover:scale-110 hover:text-yellow-400 hover:scale-110">
                                            ★
                                        </label>
                                    @endforeach
                                </div>

                                <div class="relative max-w-lg mx-auto">
                                    <textarea name="rating_comment" rows="2" 
                                              class="w-full rounded-2xl bg-slate-950/50 border border-white/10 text-white placeholder:text-slate-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition px-5 py-4 resize-none"
                                              placeholder="Deixe um comentário opcional sobre sua experiência..."></textarea>
                                </div>

                                <button type="submit" class="px-10 py-3 rounded-xl bg-white text-slate-900 font-bold hover:bg-indigo-50 hover:scale-105 transition-all shadow-lg hover:shadow-white/20">
                                    Enviar Avaliação
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center justify-center gap-3 p-4 rounded-full bg-slate-900/50 border border-white/5 text-slate-400 text-sm">
                            <span>Avaliação enviada:</span>
                            <span class="flex text-yellow-400">
                                @foreach(range(1, $ticket->rating) as $i) ★ @endforeach
                            </span>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>