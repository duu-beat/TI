<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span>Detalhes do Chamado</span>
            <a href="{{ route('client.tickets.index') }}" 
               class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
                Voltar
            </a>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="space-y-6">

        {{-- 💀 SKELETON --}}
        <div x-show="!loaded" class="space-y-6 animate-pulse">
            <div class="rounded-2xl bg-white/5 border border-white/5 p-6 h-32 w-full"></div>
            <div class="rounded-2xl bg-slate-950/20 border border-white/5 p-6 space-y-6 h-64"></div>
        </div>
    
        {{-- ✅ CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;"
             class="space-y-6"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
             
            {{-- HEADER INFO --}}
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
                    
                    {{-- 🔥 USO DO SMART ENUM AQUI --}}
                    <x-ticket-status :status="$ticket->status"/>
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
    
            {{-- ÁREA DE MENSAGENS --}}
            <div class="rounded-2xl border border-white/10 bg-slate-950/20 p-6 flex flex-col space-y-6">
                <div class="text-sm text-slate-400 mb-2 text-center">Início da conversa</div>
    
                @foreach($ticket->messages as $message)
                    @if(!$message->is_internal)
                        @php
                            $isMe = $message->user_id === auth()->id();
                        @endphp
    
                        <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="flex max-w-[85%] md:max-w-[70%] gap-3 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                                <div class="shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold overflow-hidden shadow-lg {{ $isMe ? 'bg-cyan-500 text-slate-900' : 'bg-slate-700 text-slate-300' }}">
                                    @if($message->user->profile_photo_url)
                                        <img src="{{ $message->user->profile_photo_url }}" class="h-full w-full object-cover">
                                    @else
                                        {{ substr($message->user->name, 0, 2) }}
                                    @endif
                                </div>
    
                                <div class="relative p-4 text-sm shadow-md transition hover:scale-[1.01] {{ $isMe ? 'bg-cyan-600/90 text-white rounded-2xl rounded-tr-none shadow-cyan-900/20' : 'bg-slate-800 text-slate-200 rounded-2xl rounded-tl-none border border-white/5' }}">
                                    <div class="flex items-center gap-2 mb-1 opacity-70 text-[10px] uppercase font-bold tracking-wider {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                        <span>{{ $message->user->name }}</span>
                                        <span>•</span>
                                        <span>{{ $message->created_at->format('H:i') }}</span>
                                    </div>
    
                                    <p class="whitespace-pre-line leading-relaxed">{{ $message->message }}</p>
    
                                    @if($message->attachments->count() > 0)
                                        <div class="mt-3 pt-2 border-t {{ $isMe ? 'border-white/20' : 'border-slate-600' }}">
                                            @foreach($message->attachments as $attachment)
                                                <a href="{{ $attachment->url }}" target="_blank" class="flex items-center gap-2 p-2 rounded-lg transition {{ $isMe ? 'hover:bg-white/10' : 'hover:bg-slate-700' }}">
                                                    <span class="text-lg">📎</span>
                                                    <span class="text-xs underline truncate">{{ $attachment->file_name }}</span>
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
    
            {{-- LÓGICA DE INTERAÇÃO (Resposta ou Avaliação) --}}
            @if(!in_array($ticket->status, [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED]))
                
                {{-- MODO RESPOSTA (Se aberto) --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                    <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-sm text-slate-300 font-medium ml-1">Sua resposta</label>
                            <textarea name="message" rows="3" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400/60 focus:ring-cyan-400/20 transition-all duration-300" placeholder="Escreva uma mensagem..." required></textarea>
                        </div>
    
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div class="relative">
                                <input type="file" name="attachments[]" multiple id="mini-upload" class="hidden" onchange="document.getElementById('file-count').innerText = this.files.length + ' arquivos'">
                                <label for="mini-upload" class="cursor-pointer inline-flex items-center gap-2 text-xs text-slate-400 hover:text-cyan-400 transition">
                                    <span class="text-lg">📎</span> Anexar arquivos
                                </label>
                                <span id="file-count" class="text-[10px] text-slate-500 ml-2"></span>
                            </div>
    
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-2.5 font-bold text-slate-950 hover:shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:scale-105 transition-all">
                                Enviar 🚀
                            </button>
                        </div>
                    </form>
                </div>
    
            @else
                
                {{-- MODO AVALIAÇÃO (Se fechado) --}}
                @if(session('success') && $ticket->rating)
                    <div class="mb-6 p-8 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-center animate-fade-in shadow-xl">
                        <div class="text-5xl mb-4">🎉</div>
                        <h3 class="text-2xl font-bold text-emerald-400 mb-2">Avaliação Enviada!</h3>
                        <p class="text-emerald-200/70 mb-4">Obrigado pelo seu feedback.</p>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 font-bold text-sm">
                            <span>Você deu {{ $ticket->rating }} estrelas</span>
                        </div>
                    </div>
    
                @elseif(!$ticket->rating)
                    <div class="mb-6 p-6 rounded-2xl bg-gradient-to-r from-indigo-500/20 to-purple-500/20 border border-indigo-500/30 text-center animate-fade-in shadow-xl">
                        <h3 class="text-xl font-bold text-white mb-2">Como foi o nosso atendimento?</h3>
                        <p class="text-slate-300 text-sm mb-6">A sua opinião ajuda-nos a melhorar.</p>
    
                        <form action="{{ route('client.tickets.rate', $ticket) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="flex justify-center gap-4 text-3xl flex-row-reverse">
                                @foreach(range(5, 1) as $i)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="peer hidden" required>
                                    <label for="star{{ $i }}" class="cursor-pointer text-slate-600 peer-checked:text-yellow-400 hover:text-yellow-400 transition transform hover:scale-125">★</label>
                                @endforeach
                            </div>
                            
                            <textarea name="rating_comment" rows="2" placeholder="Deixe um comentário (opcional)..." class="w-full max-w-lg mx-auto block rounded-xl bg-slate-950/50 border border-white/10 text-slate-200 text-sm p-3 focus:border-indigo-500 outline-none"></textarea>
                            
                            <button type="submit" class="rounded-xl bg-white text-indigo-900 px-6 py-2 font-bold hover:bg-indigo-50 transition shadow-lg hover:scale-105">
                                Enviar Avaliação
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                        <div class="text-emerald-400 font-bold mb-1">Chamado Encerrado</div>
                        <div class="text-xs text-emerald-300/70">Você avaliou este atendimento com {{ $ticket->rating }} estrelas ★</div>
                    </div>
                @endif
    
            @endif
        </div>
    </div>
</x-app-layout>