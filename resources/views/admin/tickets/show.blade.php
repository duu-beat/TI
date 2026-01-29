<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span>Gerenciar Chamado</span>
            <a href="{{ route('admin.tickets.index') }}" class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
                Voltar
            </a>
        </div>
    </x-slot>

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

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- COLUNA ESQUERDA: Detalhes e Chat --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- INFO DO TICKET --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <h2 class="text-xl font-bold text-white mb-2">{{ $ticket->subject }}</h2>
                    <p class="text-slate-300 whitespace-pre-line">{{ $ticket->description }}</p>

                    @if($ticket->attachment)
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="text-cyan-400 hover:underline text-sm flex items-center gap-2">
                                📎 Ver anexo original
                            </a>
                        </div>
                    @endif
                </div>

                {{-- CHAT --}}
                <div class="space-y-4">
                    @foreach($ticket->messages as $message)
                        <div class="flex gap-4 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            {{-- Avatar --}}
                            <div class="shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-xs font-bold border border-white/10
                                {{ $message->is_internal ? 'bg-yellow-500/20 text-yellow-500 border-yellow-500/30' : ($message->user_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-300') }}">
                                {{ substr($message->user->name, 0, 2) }}
                            </div>

                            {{-- Balão --}}
                            <div class="max-w-[80%] rounded-2xl p-4 border 
                                {{ $message->is_internal 
                                    ? 'bg-yellow-500/5 border-yellow-500/20 text-yellow-100' 
                                    : ($message->user_id === auth()->id() ? 'bg-indigo-500/20 border-indigo-500/30 text-indigo-100' : 'bg-slate-800 border-white/5 text-slate-300') }}">
                                
                                <div class="flex items-center gap-2 mb-1 text-xs opacity-70">
                                    <span class="font-bold">{{ $message->user->name }}</span>
                                    <span>•</span>
                                    <span>{{ $message->created_at->format('d/m H:i') }}</span>
                                    @if($message->is_internal)
                                        <span class="ml-2 px-1.5 py-0.5 rounded bg-yellow-500/20 text-yellow-400 font-bold uppercase text-[10px]">Interno</span>
                                    @endif
                                </div>

                                <p class="whitespace-pre-line">{{ $message->message }}</p>

                                @if($message->attachments->count() > 0)
                                    <div class="mt-3 space-y-1">
                                        @foreach($message->attachments as $attach)
                                            <a href="{{ $attach->url }}" target="_blank" class="block text-xs text-cyan-400 hover:underline">
                                                📎 {{ $attach->file_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- FORMULÁRIO DE RESPOSTA --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 sticky bottom-6 backdrop-blur-md">
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Checkbox Nota Interna --}}
                        <div class="mb-4">
                            <label class="inline-flex items-center gap-2 p-2 pr-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 cursor-pointer hover:bg-yellow-500/20 transition select-none">
                                <input type="checkbox" name="is_internal" value="1" class="rounded border-yellow-500/50 bg-slate-900 text-yellow-500 focus:ring-yellow-500 focus:ring-offset-0">
                                <span class="text-xs font-bold text-yellow-500 uppercase tracking-wide">🔒 Nota Interna (Cliente não vê)</span>
                            </label>
                        </div>

                        <textarea name="message" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-indigo-500/20 transition-all" placeholder="Escreva a resposta..." required></textarea>

                        <div class="mt-4 flex justify-between items-center">
                            <input type="file" name="attachments[]" multiple class="text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 transition cursor-pointer">
                            
                            <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2 font-bold text-white hover:bg-indigo-500 transition shadow-lg">
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- COLUNA DIREITA: Ações e Info --}}
            <div class="space-y-6">
                {{-- Card Cliente --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Cliente</h3>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold">
                            {{ substr($ticket->user->name, 0, 2) }}
                        </div>
                        <div>
                            <div class="text-white font-semibold">{{ $ticket->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $ticket->user->email }}</div>
                        </div>
                    </div>
                </div>

                {{-- Card Status --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Gerenciar Status</h3>
                    
                    <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="text-xs text-slate-500 mb-1 block">Status Atual</label>
                            <select name="status" class="w-full rounded-xl bg-slate-950 border border-white/10 text-slate-200 text-sm focus:border-indigo-500">
                                @foreach(\App\Enums\TicketStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-white/10 py-2 text-sm font-semibold text-white hover:bg-white/20 transition">
                            Atualizar Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>