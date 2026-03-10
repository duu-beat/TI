<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('client.tickets.index') }}" 
                   class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800/80 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
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

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8 pb-24 min-h-screen">

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

                    {{-- 🟢 FEEDBACKS E NOTIFICAÇÕES --}}
                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-medium">{{ session('info') }}</span>
                        </div>
                    @endif

                    {{-- 📧 BANNER DE PESQUISA DE SATISFAÇÃO (NPS) --}}
                    @if(in_array($ticket->status, [\App\Enums\TicketStatus::CLOSED, \App\Enums\TicketStatus::RESOLVED]) && !$ticket->npsSurvey()->exists())
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 shadow-2xl p-6">
                            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="flex flex-col md:flex-row items-center gap-6 relative z-10 text-center md:text-left">
                                <div class="h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center text-3xl shadow-inner border border-white/30">⭐</div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white">Sua opinião é muito importante!</h3>
                                    <p class="text-indigo-100 text-sm mt-1">Este chamado foi concluído. Poderia dedicar 30 segundos para nos contar como foi sua experiência?</p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('client.tickets.nps.show', $ticket) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-xl font-bold shadow-lg hover:bg-indigo-50 transition transform hover:scale-105 active:scale-95">
                                        Avaliar Atendimento
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- VISITA TÉCNICA AGENDADA --}}
                    @php $activeVisit = $ticket->technicalVisits()->whereIn('status', ['scheduled', 'in_transit', 'in_service'])->first(); @endphp
                    @if($activeVisit)
                        <div class="relative overflow-hidden rounded-2xl bg-cyan-600/10 border border-cyan-500/30 shadow-xl p-6 animate-pulse-slow">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 rounded-xl bg-cyan-500/20 flex items-center justify-center text-2xl">📅</div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-bold text-cyan-400">Visita Técnica Agendada</h3>
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                            {{ $activeVisit->getStatusLabel() }}
                                        </span>
                                    </div>
                                    <p class="text-slate-300 text-sm mt-1">
                                        Um técnico está programado para comparecer em: <br>
                                        <strong class="text-white">{{ $activeVisit->scheduled_at->format('d/m/Y \à\s H:i') }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- CARD DO DETALHE PRINCIPAL (PROBLEMA) --}}
                    <div class="relative overflow-hidden rounded-2xl bg-slate-900/60 backdrop-blur-xl border border-white/10 shadow-2xl">
                        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="p-6 sm:p-8 relative z-10">
                            <h1 class="text-xl sm:text-2xl font-bold text-white mb-4 leading-snug">{{ $ticket->subject }}</h1>
                            <div class="prose prose-invert max-w-none text-slate-300 bg-slate-950/50 p-5 rounded-xl border border-white/5 font-mono text-sm shadow-inner">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>

                            @if(count($ticket->messages) > 0 && $ticket->messages->first()->attachments->count() > 0)
                                <div class="mt-6 pt-6 border-t border-white/5">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        Anexos Iniciais
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($ticket->messages->first()->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                               class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-800/80 border border-white/10 hover:border-indigo-500/50 hover:bg-slate-700 transition group shadow-md">
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

                    {{-- TIMELINE (Mensagens de resposta) --}}
                    <div class="relative space-y-6 py-6">
                        <div class="absolute left-8 top-0 bottom-0 w-px bg-white/10 -z-10 hidden md:block"></div>

                        @foreach($ticket->messages->skip(1) as $message)
                            @php 
                                $isMe = $message->user_id === auth()->id();
                                $isAdmin = $message->user->role === 'admin' || $message->user->role === 'master';
                                // O Cliente não deve ver mensagens internas nunca, mas caso chegue algo, a gente filtra na view por segurança
                                if($message->is_internal) continue; 
                            @endphp

                            <div class="flex gap-4 {{ $isMe ? 'flex-row-reverse' : '' }} group">
                                {{-- Avatar --}}
                                <div class="h-10 w-10 rounded-xl flex items-center justify-center text-xs font-bold border shrink-0 shadow-lg relative z-10 
                                    {{ $isMe ? 'bg-indigo-600 border-indigo-500 text-white ring-4 ring-slate-900' : 'bg-slate-800 border-white/10 text-indigo-300 ring-4 ring-slate-900' }}">
                                    {{ substr($message->user->name, 0, 1) }}
                                </div>

                                <div class="flex-1 max-w-3xl">
                                    {{-- Bolha --}}
                                    <div class="rounded-2xl p-5 shadow-xl relative border transition-all duration-200 
                                        {{ $isMe ? 'bg-indigo-500/10 border-indigo-500/20 rounded-tr-sm backdrop-blur-sm' : 'bg-slate-800/80 border-white/10 rounded-tl-sm backdrop-blur-sm' }}">
                                        
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold {{ $isAdmin ? 'text-indigo-400' : 'text-white' }}">{{ $message->user->name }}</span>
                                                @if($isAdmin && !$isMe)
                                                    <span class="px-1.5 py-0.5 rounded bg-indigo-500/10 text-[9px] font-bold text-indigo-400 border border-indigo-500/20 uppercase">Suporte Técnico</span>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-slate-500">{{ $message->created_at->format('d/m H:i') }}</span>
                                        </div>

                                        <div class="prose prose-invert prose-sm max-w-none text-slate-300 leading-relaxed font-mono">
                                            {!! nl2br(e($message->message)) !!}
                                        </div>

                                        @if($message->attachments->count() > 0)
                                            <div class="mt-4 pt-3 border-t border-white/5 flex flex-wrap gap-2">
                                                @foreach($message->attachments as $attachment)
                                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-black/30 hover:bg-black/50 border border-white/10 transition text-xs font-medium text-cyan-400 hover:text-cyan-300">
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

                    {{-- Feedback do Cliente (Se já foi avaliado) --}}
                    @if($ticket->rating)
                        <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-900/40 to-slate-900 border border-emerald-500/20 shadow-lg relative overflow-hidden backdrop-blur-sm">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl"></div>
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="h-12 w-12 rounded-full bg-emerald-500/20 flex items-center justify-center text-2xl shadow-inner border border-emerald-500/30 text-emerald-400">
                                    ★
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Sua Avaliação</div>
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

                {{-- 👉 COLUNA DIREITA (Sidebar de Informações) --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-2xl sticky top-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-white/5 pb-2">Detalhes</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] text-slate-500 uppercase font-bold mb-2 block">Status Atual</label>
                                <x-ticket-status :status="$ticket->status"/>
                            </div>

                            <div class="pt-4 border-t border-white/5">
                                <label class="text-[10px] text-slate-500 uppercase font-bold mb-2 block">Prioridade</label>
                                <div class="text-sm text-slate-300">
                                    @if($ticket->priority === \App\Enums\TicketPriority::HIGH)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-red-500/10 text-red-400 border border-red-500/20 font-bold text-xs">🔥 Alta</span>
                                    @elseif($ticket->priority === \App\Enums\TicketPriority::MEDIUM)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 font-bold text-xs">🟡 Média</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold text-xs">🟢 Baixa</span>
                                    @endif
                                </div>
                            </div>

                            @if($ticket->assignedTo)
                                <div class="pt-4 border-t border-white/5">
                                    <label class="text-[10px] text-slate-500 uppercase font-bold mb-2 block">Técnico Responsável</label>
                                    <div class="flex items-center gap-2 text-sm text-slate-300">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold border border-indigo-500/30">
                                            {{ substr($ticket->assignedTo->name, 0, 1) }}
                                        </div>
                                        <span>{{ $ticket->assignedTo->name }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="pt-4 border-t border-white/5 text-xs text-slate-400 space-y-2">
                                <div class="flex justify-between"><span>Aberto em:</span> <span class="text-slate-300">{{ $ticket->created_at->format('d/m/Y H:i') }}</span></div>
                                <div class="flex justify-between"><span>Última Interação:</span> <span class="text-slate-300">{{ $ticket->updated_at->diffForHumans() }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. ÁREA DE RESPOSTA FIXA DO CLIENTE --}}
            @if(!in_array($ticket->status, [\App\Enums\TicketStatus::CLOSED, \App\Enums\TicketStatus::RESOLVED]))
                <div class="fixed bottom-0 left-0 right-0 z-40 pb-6 px-4 pointer-events-none">
                    <div class="max-w-4xl mx-auto pointer-events-auto">
                        
                        <div class="flex items-end ml-4 mb-[-1px] relative z-10">
                            <div class="px-4 py-1.5 rounded-t-lg text-xs font-bold bg-indigo-600 text-white shadow-lg border border-indigo-500">
                                Nova Resposta
                            </div>
                        </div>

                        <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" enctype="multipart/form-data" 
                              class="relative rounded-2xl border shadow-2xl backdrop-blur-2xl transition-all duration-300 overflow-hidden bg-slate-900/90 border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.6)]">
                            @csrf

                            <div class="flex items-end gap-2 p-4">
                                <div class="relative shrink-0">
                                    <input type="file" name="attachments[]" multiple id="client-file-upload" class="hidden" 
                                           onchange="document.getElementById('client-upload-icon').classList.add('text-indigo-400');">
                                    <label for="client-file-upload" class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-slate-400 cursor-pointer transition active:scale-95 shadow-inner">
                                        <svg id="client-upload-icon" class="w-6 h-6 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </label>
                                </div>

                                <textarea name="message" rows="1" 
                                          class="w-full bg-slate-950/50 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 resize-none px-4 py-3 max-h-40 overflow-y-auto custom-scrollbar transition shadow-inner"
                                          placeholder="Digite sua resposta aqui..." 
                                          required
                                          oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 160) + 'px'"></textarea>

                                <button type="submit" class="shrink-0 h-12 px-6 flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold shadow-lg shadow-indigo-600/30 transition-all hover:scale-105 active:scale-95">
                                    <span class="hidden sm:block text-sm">Enviar</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="h-24"></div>
            @endif

        </div>
    </div>
</x-app-layout>