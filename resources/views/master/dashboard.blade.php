<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            {{ __('Painel de Segurança & Sistema') }}
        </h2>
    </x-slot>

    {{-- Inicia o Alpine.js para controlar o Modal --}}
    <div class="py-12" x-data="{ showResolveModal: false, selectedUrl: '', ticketSubject: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Feedback --}}
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- SEÇÃO 1: CHAMADOS ESCALONADOS --}}
            <div class="bg-slate-900 overflow-hidden shadow-2xl sm:rounded-2xl border border-red-500/30 relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-red-600/20 blur-3xl rounded-full pointer-events-none"></div>

                <div class="p-6 border-b border-red-500/20 flex justify-between items-center bg-red-500/5">
                    <div>
                        <h3 class="text-lg font-bold text-red-100 flex items-center gap-2">
                            🚨 Casos Repassados (Escalonados)
                        </h3>
                        <p class="text-xs text-red-300/60 mt-1">Chamados que o suporte nível 1 não conseguiu resolver.</p>
                    </div>
                    <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg shadow-red-500/20">
                        {{ $escalatedTickets->count() }} Pendentes
                    </span>
                </div>
                
                <div class="p-6">
                    @if($escalatedTickets->isEmpty())
                        <div class="text-slate-500 text-center py-8 border-2 border-dashed border-white/5 rounded-xl">
                            <div class="text-2xl mb-2">✅</div>
                            Nenhum caso crítico pendente no momento.
                        </div>
                    @else
                        <div class="grid gap-4">
                            @foreach($escalatedTickets as $ticket)
                                <div class="flex items-center justify-between p-4 bg-slate-950/50 rounded-xl border border-red-500/10 hover:border-red-500/30 transition group">
                                    <div class="flex items-start gap-4">
                                        <div class="h-10 w-10 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400 font-bold text-xs border border-red-500/20">
                                            #{{ $ticket->id }}
                                        </div>
                                        <div>
                                            <div class="text-white font-bold group-hover:text-red-400 transition">{{ $ticket->subject }}</div>
                                            <div class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                                                <span>👤 {{ $ticket->user->name }}</span>
                                                <span class="text-slate-600">•</span>
                                                <span>📅 {{ $ticket->created_at->format('d/m H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        {{-- Botão Ver (Vai pro Admin se precisar ler o histórico) --}}
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-xs text-slate-500 hover:text-white underline">
                                            Ver Histórico
                                        </a>

                                        {{-- ✅ BOTÃO RESOLVER (Abre Modal) --}}
                                        <button 
                                            @click="showResolveModal = true; selectedUrl = '{{ route('master.tickets.resolve', $ticket->id) }}'; ticketSubject = '{{ $ticket->subject }}'"
                                            class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-red-600/20 flex items-center gap-2 cursor-pointer">
                                            ⚡ Resolver Agora
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- SEÇÃO 2: GERENCIAR ADMINS --}}
            <div class="bg-slate-800 overflow-hidden shadow-xl sm:rounded-2xl border border-white/10">
                <div class="p-6 border-b border-white/10 bg-white/5">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        👮 Equipe de Administração
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Gerencie quem tem acesso ao painel de atendimento.</p>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="bg-black/20 text-slate-200 uppercase font-bold text-xs">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">Nome</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3 text-right rounded-r-lg">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($admins as $admin)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-4 py-3 font-medium text-white flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-cyan-900/50 flex items-center justify-center text-xs text-cyan-400 border border-cyan-500/20">
                                                {{ substr($admin->name, 0, 1) }}
                                            </div>
                                            {{ $admin->name }}
                                            @if($admin->id === auth()->id())
                                                <span class="text-[10px] bg-red-500/20 text-red-400 px-1.5 py-0.5 rounded border border-red-500/20">VOCÊ</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $admin->email }}</td>
                                        <td class="px-4 py-3 text-right">
                                            @if($admin->id !== auth()->id())
                                                <form action="{{ route('master.users.toggle-admin', $admin) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-400 hover:text-red-300 hover:underline text-xs font-medium transition" onclick="return confirm('Tem certeza que deseja remover o acesso Admin deste usuário?')">
                                                        Remover Acesso
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-slate-600 text-xs italic">Imutável</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- 🛑 MODAL DE RESOLUÇÃO (MASTER) --}}
        <div x-show="showResolveModal" style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-slate-900 border border-red-500/30 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative" 
                 @click.away="showResolveModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                
                {{-- Topo Vermelho --}}
                <div class="h-2 w-full bg-gradient-to-r from-red-600 to-orange-600"></div>

                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Resolver Definitivamente
                    </h3>
                    <p class="text-slate-400 text-sm mb-6">
                        Você está prestes a encerrar o chamado <strong class="text-white" x-text="ticketSubject"></strong> com autoridade Master.
                    </p>

                    <form :action="selectedUrl" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Solução Técnica (Opcional)</label>
                            <textarea name="solution" rows="3" class="w-full bg-slate-950 border border-white/10 rounded-xl text-slate-300 focus:border-red-500 focus:ring-red-500 text-sm p-3" placeholder="Descreva brevemente o que foi feito (ex: Bloqueio de IP, Reset de senha)..."></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showResolveModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold">
                                Cancelar
                            </button>
                            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 transition text-sm flex items-center gap-2">
                                <span>Confirmar Resolução</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>