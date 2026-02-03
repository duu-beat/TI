<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.tickets.index') }}" 
                   class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-500 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-2">
                        Gerenciar Chamado <span class="text-slate-500">#{{ $ticket->id }}</span>
                    </h2>
                    <p class="text-xs text-slate-400 hidden sm:block">Painel de Atendimento Técnico</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if($ticket->is_escalated)
                    <span class="px-3 py-1 rounded-full bg-red-500/20 border border-red-500/50 text-red-400 text-xs font-bold uppercase tracking-wider animate-pulse">
                        ⚠️ Escalonado
                    </span>
                @endif
                {{-- ✅ ADICIONEI 'ml-4' AQUI PARA EMPURRAR PARA A DIREITA --}}
                <div class="scale-110 ml-4">
                    <x-ticket-status :status="$ticket->status"/>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8 pb-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON --}}
            <div x-show="!loaded" class="grid lg:grid-cols-3 gap-8 animate-pulse">
                <div class="lg:col-span-2 space-y-6">
                    <div class="h-40 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                    <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                </div>
                <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;" 
                 class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- 1. FEEDBACK DO CLIENTE (Se houver) --}}
                @if($ticket->rating)
                    <div class="relative overflow-hidden rounded-2xl bg-slate-900 border border-white/10 p-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 via-transparent to-transparent pointer-events-none"></div>
                        <div class="relative flex flex-col md:flex-row items-center gap-6 p-6">
                            <div class="text-center md:text-left shrink-0">
                                <div class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Satisfação do Cliente</div>
                                <div class="flex items-center gap-1 text-3xl text-yellow-400 drop-shadow-md">
                                    {{ str_repeat('★', $ticket->rating) }}
                                    <span class="text-slate-700">{{ str_repeat('★', 5 - $ticket->rating) }}</span>
                                </div>
                            </div>
                            @if($ticket->rating_comment)
                                <div class="flex-1 w-full md:border-l md:border-white/10 md:pl-6">
                                    <div class="text-xs text-slate-500 mb-1">Comentário:</div>
                                    <p class="text-slate-300 italic text-sm">"{{ $ticket->rating_comment }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                    
                    {{-- 🗨️ COLUNA PRINCIPAL: Detalhes e Chat --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- CARD DE DESCRIÇÃO DO PROBLEMA --}}
                        <div class="rounded-2xl bg-slate-900/80 border border-white/10 overflow-hidden shadow-xl backdrop-blur-sm">
                            {{-- Header do Card --}}
                            <div class="bg-white/5 p-5 border-b border-white/5 flex items-start justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    {{-- Avatar Cliente --}}
                                    <div class="relative">
                                        <div class="h-12 w-12 rounded-xl bg-slate-800 flex items-center justify-center text-lg font-bold text-white border border-white/10 shadow-inner">
                                            {{ substr($ticket->user->name, 0, 1) }}
                                        </div>
                                        {{-- Badge Role --}}
                                        <div class="absolute -bottom-2 -right-2 bg-slate-700 text-[9px] text-slate-300 px-1.5 py-0.5 rounded border border-slate-600">
                                            CLIENTE
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-base font-bold text-white">{{ $ticket->user->name }}</h3>
                                        <div class="text-xs text-slate-400 flex items-center gap-2">
                                            <span>{{ $ticket->user->email }}</span>
                                            <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                                            <span>Criado {{ $ticket->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right hidden sm:block">
                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest">Categoria</div>
                                    <div class="text-sm font-medium text-indigo-400">{{ $ticket->category ?? 'Geral' }}</div>
                                </div>
                            </div>

                            {{-- Corpo do Card --}}
                            <div class="p-6 sm:p-8">
                                <h1 class="text-xl sm:text-2xl font-bold text-white mb-4 leading-tight">{{ $ticket->subject }}</h1>
                                <div class="prose prose-invert max-w-none text-slate-300 text-sm sm:text-base leading-relaxed bg-black/20 p-4 rounded-xl border border-white/5">
                                    {!! nl2br(e($ticket->description)) !!}
                                </div>

                                {{-- Anexos Originais --}}
                                @if(method_exists($ticket, 'attachments') && $ticket->attachments->count() > 0)
                                    <div class="mt-6">
                                        <h4 class="text-xs font-bold text-slate-500 uppercase mb-3">Arquivos Anexados</h4>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($ticket->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                                   class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-800 border border-white/10 hover:border-indigo-500/50 hover:bg-slate-750 transition group">
                                                    <div class="h-8 w-8 rounded bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:text-white transition">📎</div>
                                                    <div class="text-xs">
                                                        <div class="text-slate-300 font-medium group-hover:text-white truncate max-w-[150px]">{{ $attachment->name }}</div>
                                                        <div class="text-slate-500 text-[10px]">Clique para baixar</div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TIMELINE DO CHAT --}}
                        <div class="space-y-8 relative py-4">
                            <div class="absolute left-8 top-0 bottom-0 w-px bg-white/5 -z-10 hidden md:block"></div>
                            
                            {{-- Divisor --}}
                            <div class="flex items-center justify-center gap-4 opacity-40">
                                <div class="h-px bg-white/20 w-16"></div>
                                <span class="text-[10px] uppercase tracking-widest text-slate-400">Início do Atendimento</span>
                                <div class="h-px bg-white/20 w-16"></div>
                            </div>

                            @foreach($ticket->messages as $message)
                                @php 
                                    $isMe = $message->user_id === auth()->id();
                                    $isAdmin = $message->user->role === 'admin' || $message->user->role === 'master';
                                @endphp

                                <div class="flex gap-4 {{ $isMe ? 'flex-row-reverse' : '' }} group animate-fade-in-up">
                                    
                                    {{-- Avatar --}}
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center text-xs font-bold border shrink-0 shadow-lg
                                        {{ $isAdmin 
                                            ? ($isMe ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-slate-700 border-slate-600 text-indigo-300') 
                                            : 'bg-slate-800 border-slate-700 text-slate-400' }}">
                                        {{ substr($message->user->name, 0, 1) }}
                                    </div>

                                    <div class="flex-1 max-w-2xl">
                                        {{-- Bolha --}}
                                        <div class="rounded-2xl p-5 shadow-sm relative border transition-all duration-200 hover:shadow-md
                                            {{ $isMe 
                                                ? 'bg-indigo-900/30 border-indigo-500/30 rounded-tr-sm' 
                                                : ($isAdmin ? 'bg-slate-800 border-white/10 rounded-tl-sm' : 'bg-slate-900 border-white/5 rounded-tl-sm') 
                                            }}">
                                            
                                            {{-- Header Mensagem --}}
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold {{ $isAdmin ? 'text-indigo-400' : 'text-white' }}">
                                                        {{ $message->user->name }}
                                                    </span>
                                                    @if($isAdmin)
                                                        <span class="px-1.5 py-0.5 rounded bg-indigo-500/20 text-[9px] font-bold text-indigo-300 border border-indigo-500/20 uppercase">
                                                            Suporte
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-slate-500">{{ $message->created_at->format('d/m H:i') }}</span>
                                            </div>

                                            {{-- Texto --}}
                                            <div class="prose prose-invert prose-sm max-w-none text-slate-300 leading-relaxed">
                                                {!! nl2br(e($message->message)) !!}
                                            </div>

                                            {{-- Anexos --}}
                                            @if($message->attachments && $message->attachments->count() > 0)
                                                <div class="mt-4 pt-3 border-t border-white/5 flex flex-wrap gap-2">
                                                    @foreach($message->attachments as $attachment)
                                                        <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                                           class="flex items-center gap-2 px-3 py-1.5 rounded bg-black/20 hover:bg-black/40 border border-white/5 transition text-xs font-medium text-cyan-400 hover:underline">
                                                            <span>📎</span> {{ $attachment->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- ÁREA DE RESPOSTA (Floating Capsule) --}}
                        @if($ticket->status !== \App\Enums\TicketStatus::CLOSED)
                            <div class="sticky bottom-6 z-30">
                                <div class="absolute inset-0 bg-slate-900/50 blur-xl -z-10 rounded-3xl"></div>
                                <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" 
                                      class="rounded-2xl border border-indigo-500/30 bg-slate-900/90 backdrop-blur-xl p-2 shadow-2xl ring-1 ring-indigo-500/20">
                                    @csrf
                                    
                                    <div class="relative">
                                        <textarea name="message" rows="1" 
                                                  class="w-full bg-transparent border-0 text-white placeholder:text-slate-500 focus:ring-0 resize-none py-3 px-4 max-h-40 overflow-y-auto"
                                                  placeholder="Escreva uma resposta técnica..." required
                                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                    </div>

                                    <div class="flex items-center justify-between mt-2 px-2 pb-1 border-t border-white/5 pt-2">
                                        <div class="relative">
                                            <input type="file" name="attachments[]" multiple id="admin-upload" class="hidden" 
                                                   onchange="document.getElementById('admin-file-label').classList.add('text-indigo-400')">
                                            <label for="admin-upload" class="cursor-pointer flex items-center gap-2 p-2 rounded-lg hover:bg-white/5 text-slate-400 transition hover:text-white">
                                                <svg id="admin-file-label" class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span class="text-xs font-medium">Anexar</span>
                                            </label>
                                        </div>
                                        <button type="submit" class="px-6 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-600/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                                            <span>Responder</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/5 text-center text-slate-500 font-medium">
                                🔒 Este chamado está encerrado e não aceita mais respostas.
                            </div>
                        @endif
                    </div>

                    {{-- ⚙️ COLUNA LATERAL: PAINEL DE CONTROLE --}}
                    <div class="space-y-6">
                        
                        {{-- 1. Painel de Status --}}
                        <div class="rounded-2xl border border-white/10 bg-slate-800 p-6 shadow-lg">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                Controle de Status
                            </h3>
                            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="space-y-4">
                                @csrf @method('PATCH')
                                <div class="relative">
                                    <select name="status" class="w-full appearance-none rounded-xl bg-slate-950 border border-white/10 text-slate-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 p-3 pr-10 transition cursor-pointer">
                                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <button type="submit" class="w-full rounded-xl bg-white/5 py-2.5 text-xs font-bold text-white hover:bg-white/10 transition border border-white/5 hover:border-white/20">
                                    Atualizar Estado
                                </button>
                            </form>
                        </div>

                        {{-- 2. Painel de Escalonamento (Zona de Risco) --}}
                        @if(!$ticket->is_escalated)
                            <div class="rounded-2xl border border-red-500/20 bg-slate-800 p-1 overflow-hidden group">
                                <div class="bg-red-500/5 p-5 rounded-xl transition-colors group-hover:bg-red-500/10">
                                    <h3 class="text-xs font-bold text-red-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Zona de Risco
                                    </h3>
                                    <p class="text-[10px] text-slate-400 mb-4 leading-relaxed">
                                        Incidente crítico ou falha de segurança? Notifique o nível Master imediatamente.
                                    </p>
                                    
                                    <form action="{{ route('admin.tickets.escalate', $ticket) }}" method="POST" onsubmit="return confirm('ATENÇÃO: Ação irreversível. O time de Segurança será notificado. Continuar?');">
                                        @csrf
                                        <button type="submit" class="w-full py-2.5 rounded-lg bg-red-500 text-white font-bold text-xs hover:bg-red-600 transition shadow-lg shadow-red-900/20 flex items-center justify-center gap-2">
                                            <span>🚨</span> Escalar para Segurança
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-red-500/50 bg-red-900/20 p-4 text-center animate-pulse">
                                <div class="text-xs font-bold text-red-400 uppercase tracking-widest mb-1 flex justify-center items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Escalonado
                                </div>
                                <p class="text-[10px] text-red-200/70">Em análise pela equipe de Segurança.</p>
                            </div>
                        @endif

                        {{-- 3. Detalhes Técnicos --}}
                        <div class="rounded-2xl border border-white/10 bg-slate-800 p-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Metadados</h3>
                            <div class="space-y-4 text-sm">
                                <div class="flex justify-between items-center py-2 border-b border-white/5">
                                    <span class="text-slate-500">Prioridade</span>
                                    @if($ticket->priority === \App\Enums\TicketPriority::HIGH)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-red-500/10 text-red-400 text-xs font-bold border border-red-500/20">🔥 Alta</span>
                                    @elseif($ticket->priority === \App\Enums\TicketPriority::MEDIUM)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-yellow-500/10 text-yellow-400 text-xs font-bold border border-yellow-500/20">⚠️ Média</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">ℹ️ Baixa</span>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-white/5">
                                    <span class="text-slate-500">Última Atualização</span>
                                    <span class="text-slate-300 font-mono text-xs">{{ $ticket->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-slate-500">IP do Cliente</span>
                                    <span class="text-slate-300 font-mono text-xs">***.***.***</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>