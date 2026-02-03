<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.tickets.index') }}" 
                   class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20">
                            {{ $ticket->category ?? 'Suporte' }}
                        </span>
                        <span class="text-slate-500 text-xs flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $ticket->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-2 mt-1">
                        Chamado #{{ $ticket->id }}
                    </h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if($ticket->is_escalated)
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold animate-pulse">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        ESCALONADO
                    </div>
                @endif

                <div class="scale-105">
                    <x-ticket-status :status="$ticket->status"/>
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
            loaded: false, 
            replyMode: 'public', // 'public' or 'internal'
            toggleMode() { this.replyMode = this.replyMode === 'public' ? 'internal' : 'public' }
         }" 
         x-init="setTimeout(() => loaded = true, 300)" 
         class="py-8 pb-24 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- SKELETON LOADER --}}
            <div x-show="!loaded" class="grid lg:grid-cols-3 gap-8 animate-pulse">
                <div class="lg:col-span-2 space-y-6">
                    <div class="h-40 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                    <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                </div>
                <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;" 
                 class="grid lg:grid-cols-12 gap-6 lg:gap-8"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- 👈 COLUNA ESQUERDA (Chat e Detalhes) --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- CARD DO USUÁRIO & PROBLEMA --}}
                    <div class="relative overflow-hidden rounded-2xl bg-slate-900 border border-white/10 shadow-2xl">
                        {{-- Background Decorativo --}}
                        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="p-6 border-b border-white/5 bg-white/5 backdrop-blur-sm flex items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <img class="h-14 w-14 rounded-xl object-cover border-2 border-slate-700 shadow-lg" 
                                         src="{{ $ticket->user->profile_photo_url }}" 
                                         alt="{{ $ticket->user->name }}">
                                    <div class="absolute -bottom-1 -right-1 bg-slate-800 text-[10px] text-slate-300 px-1.5 py-0.5 rounded border border-slate-600 font-bold">
                                        CLIENTE
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $ticket->user->name }}</h3>
                                    <div class="text-sm text-slate-400 flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                                        <span class="flex items-center gap-1 hover:text-indigo-400 transition cursor-pointer" title="Copiar Email">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $ticket->user->email }}
                                        </span>
                                        <span class="hidden sm:block text-slate-700">•</span>
                                        <span class="flex items-center gap-1" title="Tickets Totais">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            {{ $clientHistory['total_tickets'] ?? 0 }} Chamados
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8">
                            <h1 class="text-xl sm:text-2xl font-bold text-white mb-4 leading-snug">{{ $ticket->subject }}</h1>
                            <div class="prose prose-invert max-w-none text-slate-300 bg-slate-950/50 p-5 rounded-xl border border-white/5">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>

                            @if(count($ticket->messages) > 0 && $ticket->messages->first()->attachments->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        Anexos Iniciais
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($ticket->messages->first()->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                               class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-800 border border-white/10 hover:border-indigo-500/50 hover:bg-slate-750 transition group">
                                                <span class="text-xs text-indigo-400 font-bold group-hover:text-white transition">BAIXAR</span>
                                                <span class="w-px h-4 bg-white/10"></span>
                                                <span class="text-xs text-slate-300 truncate max-w-[150px]">{{ $attachment->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- TIMELINE --}}
                    <div class="relative space-y-8 py-6">
                        <div class="absolute left-8 top-0 bottom-0 w-px bg-slate-800 -z-10 hidden md:block"></div>

                        @foreach($ticket->messages->skip(1) as $message)
                            @php 
                                $isMe = $message->user_id === auth()->id();
                                $isAdmin = $message->user->role === 'admin' || $message->user->role === 'master';
                                $isInternal = $message->is_internal;
                            @endphp

                            <div class="flex gap-4 {{ $isMe ? 'flex-row-reverse' : '' }} group animate-fade-in-up">
                                
                                {{-- Avatar --}}
                                <div class="h-10 w-10 rounded-xl flex items-center justify-center text-xs font-bold border shrink-0 shadow-lg relative z-10
                                    {{ $isInternal 
                                        ? 'bg-amber-500/10 border-amber-500/50 text-amber-500 ring-4 ring-slate-900' 
                                        : ($isAdmin 
                                            ? ($isMe ? 'bg-indigo-600 border-indigo-500 text-white ring-4 ring-slate-900' : 'bg-slate-700 border-slate-600 text-indigo-300 ring-4 ring-slate-900') 
                                            : 'bg-slate-800 border-slate-700 text-slate-400 ring-4 ring-slate-900') 
                                    }}">
                                    {{ $isInternal ? '🔒' : substr($message->user->name, 0, 1) }}
                                </div>

                                <div class="flex-1 max-w-3xl">
                                    {{-- Bolha --}}
                                    <div class="rounded-2xl p-5 shadow-sm relative border transition-all duration-200 hover:shadow-md
                                        {{ $isInternal
                                            ? 'bg-amber-950/30 border-amber-500/20 rounded-tl-sm'
                                            : ($isMe 
                                                ? 'bg-indigo-500/10 border-indigo-500/20 rounded-tr-sm' 
                                                : ($isAdmin ? 'bg-slate-800 border-white/10 rounded-tl-sm' : 'bg-slate-900 border-white/5 rounded-tl-sm') 
                                            )
                                        }}">
                                        
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold {{ $isInternal ? 'text-amber-500' : ($isAdmin ? 'text-indigo-400' : 'text-white') }}">
                                                    {{ $message->user->name }}
                                                </span>
                                                @if($isInternal)
                                                    <span class="px-1.5 py-0.5 rounded bg-amber-500/10 text-[9px] font-bold text-amber-500 border border-amber-500/20 uppercase">Nota Interna</span>
                                                @elseif($isAdmin && !$isMe)
                                                    <span class="px-1.5 py-0.5 rounded bg-indigo-500/10 text-[9px] font-bold text-indigo-400 border border-indigo-500/20 uppercase">Staff</span>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-slate-500">{{ $message->created_at->format('d/m H:i') }}</span>
                                        </div>

                                        <div class="prose prose-invert prose-sm max-w-none text-slate-300 leading-relaxed">
                                            {!! nl2br(e($message->message)) !!}
                                        </div>

                                        @if($isInternal && $message->time_spent > 0)
                                            <div class="mt-3 pt-2 border-t border-amber-500/10 flex items-center gap-2 text-xs text-amber-500/60 font-mono">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message->time_spent }} min
                                            </div>
                                        @endif

                                        @if($message->attachments->count() > 0)
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

                    {{-- Feedback do Cliente (Se existir) --}}
                    @if($ticket->rating)
                        <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 border border-emerald-500/20 shadow-lg relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl"></div>
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="h-12 w-12 rounded-full bg-emerald-500/20 flex items-center justify-center text-2xl shadow-inner border border-emerald-500/30">
                                    ★
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Avaliação do Cliente</div>
                                    <div class="flex text-yellow-400 text-lg mt-1">
                                        @foreach(range(1, 5) as $i)
                                            <span class="{{ $i <= $ticket->rating ? '' : 'text-slate-700' }}">★</span>
                                        @endforeach
                                    </div>
                                    @if($ticket->rating_comment)
                                        <p class="text-slate-400 text-sm mt-2 italic">"{{ $ticket->rating_comment }}"</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- 👉 COLUNA DIREITA (Sidebar de Controle) --}}
                <div class="lg:col-span-4 space-y-6">
                    
                    {{-- 1. Painel de Status --}}
                    <div class="bg-slate-800/80 backdrop-blur border border-white/10 rounded-2xl p-5 shadow-xl sticky top-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Controle do Chamado</h3>
                        
                        <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="space-y-4">
                            @csrf @method('PATCH')
                            
                            {{-- Status --}}
                            <div>
                                <label class="text-[10px] text-slate-500 uppercase font-bold mb-1 block">Status Atual</label>
                                <div class="relative">
                                    <select name="status" class="w-full appearance-none rounded-xl bg-slate-950 border border-white/10 text-slate-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 p-2.5 pr-8 cursor-pointer hover:bg-slate-900 transition">
                                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2 transition shadow-lg shadow-indigo-500/20">
                                Salvar Alteração
                            </button>
                        </form>

                        {{-- Atribuição --}}
                        <div class="mt-6 pt-6 border-t border-white/5">
                            <h4 class="text-[10px] text-slate-500 uppercase font-bold mb-2">Responsável</h4>
                            <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="flex gap-2">
                                @csrf @method('PATCH')
                                <select name="assigned_to" class="w-full text-xs rounded-lg bg-slate-950 border border-white/10 text-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Não Atribuído --</option>
                                    @if(isset($admins))
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <button type="submit" class="px-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        </div>

                        {{-- Merge & Escalar --}}
                        <div class="mt-6 pt-6 border-t border-white/5 grid grid-cols-2 gap-3">
                             <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="w-full py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-xs text-slate-200 font-bold border border-white/5 transition">
                                    Mesclar
                                </button>
                                
                                {{-- Dropdown Merge --}}
                                <div x-show="open" @click.away="open = false" class="absolute left-0 top-full mt-2 w-48 bg-slate-800 border border-white/10 rounded-xl shadow-xl p-3 z-50">
                                    <p class="text-[10px] text-slate-500 mb-2">ID do Ticket Destino:</p>
                                    <form action="{{ route('admin.tickets.merge', $ticket) }}" method="POST">
                                        @csrf
                                        <input type="number" name="target_ticket_id" class="w-full text-xs bg-slate-950 border-white/10 rounded mb-2 text-white" placeholder="ID #">
                                        <button type="submit" class="w-full bg-indigo-600 text-white text-xs py-1 rounded">Confirmar</button>
                                    </form>
                                </div>
                             </div>

                             @if(!$ticket->is_escalated)
                                <form action="{{ route('admin.tickets.escalate', $ticket) }}" method="POST" onsubmit="return confirm('Escalar para Segurança?');">
                                    @csrf
                                    <button class="w-full py-2 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-xs text-red-400 hover:text-red-300 font-bold border border-red-500/20 transition">
                                        Escalar 🚨
                                    </button>
                                </form>
                             @else
                                <div class="w-full py-2 rounded-lg bg-red-500 text-white text-xs font-bold text-center opacity-80 cursor-not-allowed">
                                    Escalonado
                                </div>
                             @endif
                        </div>
                    </div>

                    {{-- 2. Histórico Rápido --}}
                    <div class="bg-slate-900/50 border border-white/5 rounded-2xl p-5">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Contexto</h3>
                        <div class="space-y-2 text-xs text-slate-400">
                            <div class="flex justify-between">
                                <span>Criado:</span>
                                <span class="text-slate-300">{{ $ticket->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Última msg:</span>
                                <span class="text-slate-300">{{ $ticket->updated_at->diffForHumans() }}</span>
                            </div>
                            @if(isset($clientHistory['last_ticket']))
                                <div class="pt-2 mt-2 border-t border-white/5">
                                    <span class="block mb-1">Último chamado:</span>
                                    <a href="{{ route('admin.tickets.show', $clientHistory['last_ticket']) }}" class="flex items-center gap-2 text-indigo-400 hover:underline bg-indigo-500/5 p-2 rounded">
                                        <span class="font-mono">#{{ $clientHistory['last_ticket']->id }}</span>
                                        <span class="truncate">{{ Str::limit($clientHistory['last_ticket']->subject, 15) }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- 3. ÁREA DE RESPOSTA (Fixed Bottom) --}}
            @if($ticket->status !== \App\Enums\TicketStatus::CLOSED)
                <div class="fixed bottom-0 left-0 right-0 z-40 pb-6 px-4 pointer-events-none">
                    <div class="max-w-4xl mx-auto pointer-events-auto">
                        
                        {{-- Tabs Visual --}}
                        <div class="flex items-end ml-4 mb-[-1px] relative z-10 gap-1">
                            <button @click="replyMode = 'public'" 
                                    class="px-4 py-1.5 rounded-t-lg text-xs font-bold transition-all border-t border-x"
                                    :class="replyMode === 'public' 
                                        ? 'bg-slate-900 text-white border-white/10 border-b-slate-900' 
                                        : 'bg-slate-950/50 text-slate-500 border-transparent hover:text-slate-300'">
                                Resposta Pública
                            </button>
                            <button @click="replyMode = 'internal'" 
                                    class="px-4 py-1.5 rounded-t-lg text-xs font-bold transition-all border-t border-x flex items-center gap-2"
                                    :class="replyMode === 'internal' 
                                        ? 'bg-amber-950/90 text-amber-500 border-amber-500/30' 
                                        : 'bg-slate-950/50 text-slate-500 border-transparent hover:text-amber-500/70'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Nota Interna
                            </button>
                        </div>

                        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data" 
                              class="relative rounded-2xl border shadow-2xl backdrop-blur-xl transition-all duration-300 overflow-hidden"
                              :class="replyMode === 'internal' 
                                ? 'bg-amber-950/90 border-amber-500/30 shadow-[0_0_50px_rgba(245,158,11,0.15)]' 
                                : 'bg-slate-900/95 border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.5)]'">
                            @csrf
                            
                            {{-- Hidden Inputs --}}
                            <input type="hidden" name="is_internal" :value="replyMode === 'internal' ? 1 : 0">

                            {{-- Toolbar --}}
                            <div class="flex items-center gap-3 p-2 border-b border-white/5 bg-black/10">
                                {{-- Respostas Prontas --}}
                                <select id="cannedSelect" onchange="document.querySelector('textarea[name=message]').value += this.value + '\n'; this.value='';" 
                                        class="bg-transparent text-xs border-none focus:ring-0 text-slate-400 hover:text-white cursor-pointer w-40">
                                    <option value="">⚡ Respostas Prontas...</option>
                                    @if(isset($cannedResponses))
                                        @foreach($cannedResponses as $canned)
                                            <option value="{{ $canned->content }}">{{ $canned->title }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="h-4 w-px bg-white/10"></div>

                                {{-- Time Tracking (Visível apenas se interno, ou sempre opcional) --}}
                                <div class="flex items-center gap-2" x-show="replyMode === 'internal'">
                                    <span class="text-[10px] text-slate-400">Tempo (min):</span>
                                    <input type="number" name="time_spent" class="w-12 h-6 text-xs bg-black/20 border-white/10 rounded px-1 text-white focus:ring-0">
                                </div>
                            </div>

                            <div class="flex items-end gap-2 p-3">
                                <div class="relative shrink-0">
                                    <input type="file" name="attachments[]" multiple id="admin-file-upload" class="hidden" 
                                           onchange="document.getElementById('admin-upload-icon').classList.add('text-indigo-400');">
                                    <label for="admin-file-upload" class="flex h-10 w-10 items-center justify-center rounded-xl hover:bg-white/10 text-slate-400 cursor-pointer transition active:scale-95">
                                        <svg id="admin-upload-icon" class="w-6 h-6 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </label>
                                </div>

                                <textarea name="message" rows="1" 
                                          class="w-full bg-transparent border-0 text-white placeholder:text-slate-500 focus:ring-0 resize-none py-3 max-h-40 overflow-y-auto custom-scrollbar"
                                          :placeholder="replyMode === 'internal' ? 'Escreva uma nota visível apenas para a equipe...' : 'Escreva uma resposta pública para o cliente...'" 
                                          required
                                          oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 160) + 'px'"></textarea>

                                <button type="submit" 
                                        class="shrink-0 h-10 px-6 flex items-center justify-center gap-2 rounded-xl text-white font-bold shadow-lg transition-all hover:scale-105 active:scale-95"
                                        :class="replyMode === 'internal' 
                                            ? 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/30' 
                                            : 'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-600/30'">
                                    <span class="hidden sm:block text-sm" x-text="replyMode === 'internal' ? 'Salvar Nota' : 'Enviar'"></span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                {{-- Espaçador --}}
                <div class="h-24"></div>
            @endif

        </div>
    </div>
</x-app-layout>