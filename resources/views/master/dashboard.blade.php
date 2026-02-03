<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            {{ __('Painel de Segurança & Sistema') }}
        </h2>
    </x-slot>

    {{-- ✅ ATUALIZADO: Adicionado showRemoveAdminModal, removeAdminUrl e removeAdminName no x-data --}}
    <div class="py-12" x-data="{ 
            showResolveModal: false, 
            showCreateAdminModal: false, 
            showRemoveAdminModal: false, 
            selectedUrl: '', 
            ticketSubject: '', 
            removeAdminUrl: '', 
            removeAdminName: '', 
            loaded: false 
        }" 
        x-init="setTimeout(() => loaded = true, 300)">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SKELETON LOADING --}}
            <div x-show="!loaded" class="space-y-8 animate-pulse">
                <div class="h-64 bg-slate-900 rounded-2xl border border-white/5 p-6">
                    <div class="h-8 bg-white/5 rounded w-1/3 mb-4"></div>
                    <div class="space-y-3">
                        <div class="h-16 bg-white/5 rounded-xl w-full"></div>
                        <div class="h-16 bg-white/5 rounded-xl w-full"></div>
                    </div>
                </div>
                <div class="h-96 bg-slate-800 rounded-2xl border border-white/5 p-6">
                    <div class="flex justify-between mb-6">
                        <div class="h-8 bg-white/5 rounded w-1/4"></div>
                        <div class="h-8 bg-white/5 rounded w-24"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-12 bg-white/5 rounded-lg w-full"></div>
                        <div class="h-12 bg-white/5 rounded-lg w-full"></div>
                        <div class="h-12 bg-white/5 rounded-lg w-full"></div>
                    </div>
                </div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;" class="space-y-8"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

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
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                               class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-slate-700/20 flex items-center gap-2">
                                                 Ver Histórico
                                            </a>

                                            <button 
                                                @click="showResolveModal = true; selectedUrl = '{{ route('master.tickets.resolve', $ticket->id) }}'; ticketSubject = '{{ $ticket->subject }}'"
                                                class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-red-600/20 flex items-center gap-2 cursor-pointer">
                                                 Resolver Agora
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
                    <div class="p-6 border-b border-white/10 bg-white/5 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                👮 Equipe de Administração
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">Gerencie quem tem acesso ao painel de atendimento.</p>
                        </div>
                        <button @click="showCreateAdminModal = true" class="px-3 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-cyan-600/20 flex items-center gap-2">
                             Novo Admin
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-400">
                                <thead class="bg-black/20 text-slate-200 uppercase font-bold text-xs">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-lg">Nome</th>
                                        <th class="px-4 py-3">Contato</th>
                                        <th class="px-4 py-3 text-right rounded-r-lg">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($admins as $admin)
                                        <tr class="hover:bg-white/5 transition group">
                                            <td class="px-4 py-3 font-medium text-white flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-cyan-900/50 flex items-center justify-center text-xs text-cyan-400 border border-cyan-500/20">
                                                    {{ substr($admin->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div>{{ $admin->name }}</div>
                                                    @if($admin->id === auth()->id())
                                                        <span class="text-[10px] text-cyan-400 font-bold">VOCÊ</span>
                                                    @else
                                                        <span class="text-[10px] text-slate-500">ID: {{ $admin->id }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span>{{ $admin->email }}</span>
                                                    <a href="mailto:{{ $admin->email }}" class="text-[10px] text-cyan-500 hover:underline">Enviar Email</a>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    <a href="{{ route('master.audit', ['user_id' => $admin->id]) }}" 
                                                       class="px-2 py-1 bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white rounded text-xs transition"
                                                       title="Ver histórico de ações deste admin">
                                                        📜 Logs
                                                    </a>

                                                    @if($admin->id !== auth()->id())
                                                        {{-- ✅ BOTÃO DE REMOVER: Agora abre o modal --}}
                                                        <button 
                                                            @click="
                                                                showRemoveAdminModal = true; 
                                                                removeAdminUrl = '{{ route('master.users.toggle-admin', $admin) }}'; 
                                                                removeAdminName = '{{ addslashes($admin->name) }}'
                                                            "
                                                            class="px-2 py-1 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 hover:border-red-500 rounded text-xs transition"
                                                            title="Rebaixar para Cliente">
                                                            ⬇️ Remover
                                                        </button>
                                                    @else
                                                        <span class="text-slate-600 text-xs italic px-2">Imutável</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 1. MODAL DE RESOLUÇÃO (MASTER) --}}
            <div x-show="showResolveModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                 x-transition>
                 
                 <div class="bg-slate-900 border border-red-500/30 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative" 
                      @click.away="showResolveModal = false">
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 to-orange-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-2">Resolver Definitivamente</h3>
                        <p class="text-slate-400 text-sm mb-6">Você está prestes a encerrar o chamado <strong class="text-white" x-text="ticketSubject"></strong>.</p>
                        <form :action="selectedUrl" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Solução Técnica (Opcional)</label>
                                <textarea name="solution" rows="3" class="w-full bg-slate-950 border border-white/10 rounded-xl text-slate-300 p-3"></textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="showResolveModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white">Cancelar</button>
                                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 2. MODAL: CRIAR ADMIN RÁPIDO --}}
            <div x-show="showCreateAdminModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                 x-transition>
                
                <div class="bg-slate-900 border border-cyan-500/30 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative" 
                     @click.away="showCreateAdminModal = false">
                    
                    <div class="h-2 w-full bg-gradient-to-r from-cyan-600 to-blue-600"></div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-2xl">👮</span> Adicionar Administrador
                        </h3>
                        <p class="text-slate-400 text-sm mb-6">
                            Crie um novo usuário com acesso imediato ao painel de suporte (Admin).
                        </p>

                        <form action="{{ route('master.users.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="role" value="admin">

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nome</label>
                                <input type="text" name="name" required class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-500 focus:ring-cyan-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email</label>
                                <input type="email" name="email" required class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-500 focus:ring-cyan-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Senha Provisória</label>
                                <input type="text" name="password" required minlength="8" class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-cyan-500 focus:ring-cyan-500" placeholder="Mínimo 8 caracteres">
                            </div>

                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" @click="showCreateAdminModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl font-bold shadow-lg shadow-cyan-500/20 transition text-sm flex items-center gap-2">
                                    <span>Criar Admin</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 3. ✅ NOVO MODAL: REMOVER ADMIN --}}
            <div x-show="showRemoveAdminModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
                 x-transition>
                
                <div class="bg-slate-900 border border-red-500/30 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden relative transform transition-all scale-100" 
                     @click.away="showRemoveAdminModal = false">
                    
                    {{-- Faixa de Alerta --}}
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 to-orange-600"></div>

                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/10 mb-4 border border-red-500/20">
                            <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">Remover Admin?</h3>
                        <p class="text-slate-400 text-sm mb-6">
                            Você está prestes a remover o acesso administrativo de <strong class="text-white text-base" x-text="removeAdminName"></strong>.
                            <br><br>
                            Ele voltará a ser um <strong>Cliente</strong> comum.
                        </p>

                        <form :action="removeAdminUrl" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="flex justify-center gap-3">
                                <button type="button" @click="showRemoveAdminModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold border border-transparent hover:border-white/10">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 transition text-sm flex items-center gap-2">
                                    Sim, Remover
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>