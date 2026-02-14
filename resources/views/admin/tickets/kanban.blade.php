<x-app-layout>
    {{-- Carrega SortableJS para o Drag & Drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
                <div class="p-2 bg-indigo-600/20 rounded-lg border border-indigo-500/30">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                </div>
                Quadro de Gestão
            </h2>
            
            {{-- Controles --}}
            <div class="flex items-center gap-3" x-data>
                <div class="h-6 w-px bg-white/10 mx-1"></div>

                <a href="{{ route('admin.tickets.index') }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-lg transition border border-white/5" title="Ver Lista">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </a>
                <button disabled class="p-2 bg-indigo-600 text-white rounded-lg shadow-lg shadow-indigo-500/20 cursor-default" title="Kanban Ativo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 h-[calc(100vh-140px)] overflow-hidden">
        <div class="h-full px-4 sm:px-6 lg:px-8 overflow-x-auto custom-scrollbar">
            
            {{-- Container das Colunas --}}
            <div class="inline-flex h-full gap-6 pb-4 min-w-full" id="kanban-board">
                
                @foreach($statuses as $status)
                    @php
                        $colors = match($status) {
                            \App\Enums\TicketStatus::NEW => ['bg' => 'bg-blue-500/5', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400', 'dot' => 'bg-blue-500'],
                            \App\Enums\TicketStatus::IN_PROGRESS => ['bg' => 'bg-amber-500/5', 'border' => 'border-amber-500/20', 'text' => 'text-amber-400', 'dot' => 'bg-amber-500'],
                            \App\Enums\TicketStatus::WAITING_CLIENT => ['bg' => 'bg-purple-500/5', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400', 'dot' => 'bg-purple-500'],
                            \App\Enums\TicketStatus::RESOLVED => ['bg' => 'bg-emerald-500/5', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-400', 'dot' => 'bg-emerald-500'],
                            \App\Enums\TicketStatus::CLOSED => ['bg' => 'bg-slate-500/5', 'border' => 'border-slate-500/20', 'text' => 'text-slate-400', 'dot' => 'bg-slate-500'],
                        };
                        $tickets = $groupedTickets[$status->value] ?? collect([]);
                    @endphp

                    {{-- Coluna --}}
                    <div class="w-80 flex flex-col shrink-0 rounded-2xl border {{ $colors['border'] }} {{ $colors['bg'] }} backdrop-blur-md transition-colors duration-300">
                        
                        {{-- Cabeçalho --}}
                        <div class="p-4 border-b border-white/5 flex items-center justify-between sticky top-0 z-10">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $colors['dot'] }} shadow-[0_0_10px_rgba(0,0,0,0.5)] shadow-{{ explode('-', $colors['text'])[1] }}-500/50"></div>
                                <span class="font-bold text-sm text-white tracking-wide">{{ $status->label() }}</span>
                            </div>
                            <span class="bg-slate-900/60 text-slate-400 text-[10px] px-2.5 py-1 rounded-lg border border-white/5 font-mono">
                                {{ $tickets->count() }}
                            </span>
                        </div>

                        {{-- Área de Cards (Sortable) --}}
                        <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar kanban-column" 
                             data-status="{{ $status->value }}">
                            
                            @forelse($tickets as $ticket)
                                @php
                                    $priorityColor = match($ticket->priority) {
                                        'high' => 'border-l-red-500',
                                        'medium' => 'border-l-yellow-500',
                                        'low' => 'border-l-blue-500',
                                        default => 'border-l-slate-600'
                                    };
                                @endphp

                                {{-- CARD KANBAN COM AÇÕES --}}
                                <div class="kanban-card group relative bg-slate-800 hover:bg-slate-750 p-4 rounded-xl border border-white/5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-200 cursor-grab active:cursor-grabbing border-l-4 {{ $priorityColor }}"
                                     data-id="{{ $ticket->id }}">
                                    
                                    {{-- 🚀 OVERLAY DE AÇÕES RÁPIDAS (Só aparece no Hover) --}}
                                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2 rounded-xl z-20"
                                         onclick="if(event.target === this) window.location='{{ route('admin.tickets.show', $ticket) }}'">
                                         
                                         {{-- Botão Ver --}}
                                         <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                            class="p-2 bg-white/10 hover:bg-white/20 text-white rounded-lg backdrop-blur-md transition transform hover:scale-110 border border-white/10" 
                                            title="Abrir Chamado">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                         </a>

                                         {{-- Botão "Pegar para mim" (Se não tiver dono) --}}
                                         @if(!$ticket->assigned_to)
                                             <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="inline no-drag">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                                                <button type="submit" 
                                                        class="p-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg shadow-lg shadow-indigo-500/30 transition transform hover:scale-110 border border-white/10"
                                                        title="Atribuir para mim">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                                </button>
                                             </form>
                                         @endif
                                    </div>

                                    {{-- CONTEÚDO NORMAL DO CARD --}}
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[10px] font-mono text-slate-500 group-hover:text-indigo-400 transition">#{{ $ticket->id }}</span>
                                        @if($ticket->tags->isNotEmpty())
                                            <div class="flex gap-1">
                                                @foreach($ticket->tags->take(2) as $tag)
                                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $tag->color }}" title="{{ $tag->name }}"></span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <h4 class="text-sm font-semibold text-slate-200 mb-3 line-clamp-2 leading-snug group-hover:text-white">
                                        {{ $ticket->subject }}
                                    </h4>

                                    <div class="flex items-center justify-between pt-3 border-t border-white/5">
                                        <div class="flex items-center gap-2">
                                            <div class="relative">
                                                <div class="w-6 h-6 rounded-lg bg-slate-700 flex items-center justify-center text-[10px] text-white font-bold border border-white/10">
                                                    {{ substr($ticket->user->name, 0, 1) }}
                                                </div>
                                                @if($ticket->messages->first()?->attachments->isNotEmpty())
                                                    <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-indigo-500 rounded-full border border-slate-800"></div>
                                                @endif
                                            </div>
                                            
                                            @if($ticket->assignee)
                                                <div class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center text-[10px] text-white font-bold border-2 border-slate-800 -ml-3 shadow-sm" title="Resp: {{ $ticket->assignee->name }}">
                                                    {{ substr($ticket->assignee->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-[10px] text-slate-500 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $ticket->created_at->format('d/m') }}
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <div class="empty-placeholder text-center py-10 opacity-40 border-2 border-dashed border-white/5 rounded-xl">
                                    <p class="text-xs text-slate-400">Sem chamados</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SCRIPTS PARA DRAG & DROP --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inicializa SortableJS em todas as colunas
            const columns = document.querySelectorAll('.kanban-column');
            
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'shared', // Permite mover entre colunas
                    animation: 150,
                    ghostClass: 'bg-indigo-500/10', // Classe do "fantasma" ao arrastar
                    delay: 100, // Previne cliques acidentais
                    delayOnTouchOnly: true,
                    
                    onEnd: function (evt) {
                        const itemEl = evt.item;
                        const newStatus = evt.to.getAttribute('data-status');
                        const ticketId = itemEl.getAttribute('data-id');
                        const oldStatus = evt.from.getAttribute('data-status');

                        // Se mudou de coluna
                        if (newStatus !== oldStatus) {
                            updateTicketStatus(ticketId, newStatus);
                        }
                    },
                });
            });

            // 2. Função para salvar no banco via AJAX
            function updateTicketStatus(ticketId, status) {
                // Notificação visual simples (opcional)
                const originalColor = document.querySelector(`[data-id="${ticketId}"]`).style.borderColor;
                
                fetch(`/admin/chamados/${ticketId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao atualizar');
                    // Sucesso: Toca um som ou mostra toast (opcional)
                    console.log(`Ticket #${ticketId} movido para ${status}`);
                })
                .catch(error => {
                    alert('Erro ao mover chamado. Recarregue a página.');
                    location.reload(); // Reverte visualmente em caso de erro
                });
            }
        });

        // 3. Função de Filtro Rápido (Front-end puro)
        function filterCards(text) {
            text = text.toLowerCase();
            const cards = document.querySelectorAll('.kanban-card');
            
            cards.forEach(card => {
                const content = card.innerText.toLowerCase();
                if (content.includes(text)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    <style>
        /* Estilos extras para scrollbar e drag */
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
        .sortable-drag { opacity: 0; }
        .kanban-card { touch-action: manipulation; }
    </style>
</x-app-layout>