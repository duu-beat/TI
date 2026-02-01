<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-200 leading-tight">
                {{ __('Gerenciar Chamado') }} <span class="text-slate-500">#{{ $ticket->id }}</span>
            </h2>
            <a href="{{ route('admin.tickets.index') }}" class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition ml-auto">
                Voltar
            </a>
        </div>
    </x-slot>

    {{-- ⚡ ALPINE.JS CONTROLLER --}}
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 💀 SKELETON LOADING (Layout 2 Colunas) --}}
            <div x-show="!loaded" class="grid lg:grid-cols-3 gap-8 animate-pulse">
                {{-- Coluna Esquerda (Mensagens) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="h-32 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                    <div class="space-y-4">
                        <div class="h-24 bg-white/5 rounded-2xl w-3/4 mr-auto"></div>
                        <div class="h-24 bg-white/5 rounded-2xl w-3/4 ml-auto"></div>
                        <div class="h-24 bg-white/5 rounded-2xl w-3/4 mr-auto"></div>
                    </div>
                </div>

                {{-- Coluna Direita (Sidebar) --}}
                <div class="space-y-6">
                    <div class="h-64 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                    <div class="h-40 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                </div>
            </div>

            {{-- ✅ CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;" class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- ⭐ AVALIAÇÃO DO CLIENTE (Se houver) --}}
                @if($ticket->rating)
                    <div class="rounded-2xl bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 p-6 flex flex-col md:flex-row items-center gap-6 animate-pulse-slow">
                        <div class="text-center md:text-left">
                            <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1">Avaliação Recebida</div>
                            <div class="text-4xl text-yellow-400 tracking-widest">
                                {{ str_repeat('★', $ticket->rating) }}<span class="text-slate-600 opacity-30">{{ str_repeat('★', 5 - $ticket->rating) }}</span>
                            </div>
                        </div>
                        @if($ticket->rating_comment)
                            <div class="flex-1 border-l border-emerald-500/20 pl-6">
                                <p class="text-slate-300 italic text-lg">"{{ $ticket->rating_comment }}"</p>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="grid lg:grid-cols-3 gap-8">
                    
                    {{-- 🗨️ COLUNA ESQUERDA: Conversa --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Card da Descrição Original --}}
                        <div class="rounded-2xl bg-slate-800 border border-white/10 overflow-hidden">
                            <div class="bg-slate-900/50 p-4 border-b border-white/5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">{{ $ticket->user->name }}</div>
                                        <div class="text-xs text-slate-500">Cliente</div>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="p-6">
                                <h1 class="text-xl font-bold text-white mb-4">{{ $ticket->subject }}</h1>
                                <div class="prose prose-invert max-w-none text-slate-300">
                                    {!! nl2br(e($ticket->description)) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Loop de Mensagens --}}
                        <div class="space-y-6 relative">
                            {{-- Linha do tempo (Opcional, decorativa) --}}
                            <div class="absolute left-8 top-0 bottom-0 w-px bg-white/5 -z-10 hidden md:block"></div>

                            @foreach($ticket->messages as $message)
                                <div class="flex gap-4 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                    
                                    {{-- Avatar --}}
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-xs font-bold border shrink-0 
                                        {{ $message->user_id === auth()->id() 
                                            ? 'bg-blue-600 border-blue-500 text-white' 
                                            : ($message->user->is_admin ? 'bg-purple-600 border-purple-500 text-white' : 'bg-slate-700 border-slate-600 text-slate-300') 
                                        }}">
                                        {{ substr($message->user->name, 0, 1) }}
                                    </div>

                                    {{-- Balão --}}
                                    <div class="flex-1 max-w-2xl">
                                        <div class="rounded-2xl p-5 shadow-sm relative
                                            {{ $message->user_id === auth()->id() 
                                                ? 'bg-blue-600/10 border border-blue-500/20 rounded-tr-none' 
                                                : 'bg-slate-800 border border-white/5 rounded-tl-none' 
                                            }}">
                                            
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-bold {{ $message->user_id === auth()->id() ? 'text-blue-400' : 'text-slate-400' }}">
                                                    {{ $message->user->name }}
                                                </span>
                                                <span class="text-[10px] text-slate-500">{{ $message->created_at->format('d/m H:i') }}</span>
                                            </div>

                                            <div class="prose prose-invert prose-sm max-w-none text-slate-300">
                                                {!! nl2br(e($message->message)) !!}
                                            </div>

                                            {{-- Anexos na mensagem --}}
                                            @if($message->attachments && $message->attachments->count() > 0)
                                                <div class="mt-4 pt-4 border-t border-white/5 space-y-2">
                                                    @foreach($message->attachments as $attachment)
                                                        <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-lg bg-black/20 hover:bg-black/40 transition group">
                                                            <div class="h-8 w-8 rounded bg-white/10 flex items-center justify-center text-lg">📎</div>
                                                            <div class="text-xs text-slate-300 group-hover:text-white truncate">{{ $attachment->name }}</div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Área de Resposta --}}
                        @if($ticket->status !== \App\Enums\TicketStatus::CLOSED)
                            <div class="rounded-2xl bg-slate-800 border border-white/10 p-6 mt-8">
                                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                    Responder Chamado
                                </h3>
                                
                                <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <textarea name="message" rows="4" 
                                        class="w-full rounded-xl bg-slate-950 border border-white/10 text-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition p-4 resize-none"
                                        placeholder="Escreva sua resposta técnica aqui..."></textarea>
                                    
                                    {{-- Upload --}}
                                    <div class="mt-4 flex items-center gap-4">
                                        <label class="cursor-pointer flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 border border-white/10 hover:bg-slate-950 transition text-xs text-slate-400">
                                            <span>📎 Anexar Arquivos</span>
                                            <input type="file" name="attachments[]" multiple class="hidden">
                                        </label>
                                        <div class="text-[10px] text-slate-600">Max: 5MB (JPG, PNG, PDF)</div>
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold text-sm hover:brightness-110 transition shadow-lg shadow-blue-500/20">
                                            Enviar Resposta
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-slate-800/50 border border-white/5 text-center text-slate-500">
                                Este chamado foi encerrado e não aceita novas respostas.
                            </div>
                        @endif
                    </div>

                    {{-- ⚙️ COLUNA DIREITA: Sidebar --}}
                    <div class="space-y-6">
                        
                        {{-- Card de Status --}}
                        <div class="rounded-2xl border border-white/10 bg-slate-800 p-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Gerenciar Status</h3>
                            
                            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <select name="status" class="w-full rounded-xl bg-slate-950 border border-white/10 text-slate-200 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 p-2.5">
                                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full rounded-xl bg-white/5 py-2.5 text-xs font-bold text-white hover:bg-white/10 transition border border-white/5">
                                    Atualizar Status
                                </button>
                            </form>
                        </div>

                        {{-- Card de Informações --}}
                        <div class="rounded-2xl border border-white/10 bg-slate-800 p-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Detalhes Técnicos</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="text-[10px] text-slate-500 uppercase">Cliente</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="h-6 w-6 rounded-full bg-slate-700 text-[10px] flex items-center justify-center text-white">
                                            {{ substr($ticket->user->name, 0, 1) }}
                                        </div>
                                        <div class="text-sm text-slate-200">{{ $ticket->user->name }}</div>
                                    </div>
                                    <div class="text-xs text-slate-500 ml-8">{{ $ticket->user->email }}</div>
                                </div>

                                <div>
                                    <div class="text-[10px] text-slate-500 uppercase">Prioridade</div>
                                    <div class="mt-1">
                                        @if($ticket->priority === \App\Enums\TicketPriority::HIGH)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-500/10 border border-red-500/20 text-xs font-bold text-red-400">
                                                🚨 Alta
                                            </span>
                                        @elseif($ticket->priority === \App\Enums\TicketPriority::MEDIUM)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-xs font-bold text-yellow-400">
                                                ⚠️ Média
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-xs font-bold text-blue-400">
                                                ℹ️ Baixa
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-white/5">
                                    <div class="text-[10px] text-slate-500 uppercase">Anexos do Chamado</div>
                                    @if(method_exists($ticket, 'attachments') && $ticket->attachments->count() > 0)
                                        <div class="mt-2 space-y-2">
                                            @foreach($ticket->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="block text-xs text-cyan-400 hover:underline truncate">
                                                    📎 {{ $attachment->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="mt-1 text-xs text-slate-600 italic">Sem anexos iniciais.</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>