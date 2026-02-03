<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            {{ __('Gerenciamento de Usuários') }}
        </h2>
    </x-slot>

    {{-- ✅ STATE ALPINE ATUALIZADO (Inclui Edição e Exclusão) --}}
    <div class="py-12" x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            showDeleteModal: false,
            loaded: false,
            editForm: { name: '', email: '', role: '', action: '' },
            deleteForm: { action: '', name: '' }
        }" 
        x-init="setTimeout(() => loaded = true, 300)">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SKELETON --}}
            <div x-show="!loaded" class="space-y-6">
                <div class="flex justify-between gap-4">
                    <x-skeleton type="box" height="h-10" width="w-full max-w-md" class="rounded-xl" />
                    <x-skeleton type="box" height="h-10" width="w-40" class="rounded-xl" />
                </div>
                <x-skeleton type="box" height="h-96" class="rounded-2xl" />
            </div>

            <div x-show="loaded" style="display: none;" class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                {{-- Feedbacks --}}
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3">
                        ⚠️ {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Toolbar --}}
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <form method="GET" class="flex-1 max-w-md relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Buscar por nome ou email..." 
                               class="w-full rounded-xl border border-white/10 bg-slate-800 text-slate-300 placeholder-slate-500 focus:border-red-500 focus:ring-red-500">
                        <button type="submit" class="absolute right-3 top-2.5 text-slate-500 hover:text-white">🔍</button>
                    </form>

                    <button @click="showCreateModal = true" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-red-500/20 transition flex items-center gap-2">
                         Novo Usuário
                    </button>
                </div>

                {{-- Tabela --}}
                <div class="bg-slate-800 rounded-2xl border border-white/10 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="bg-black/20 text-slate-200 uppercase font-bold text-xs">
                                <tr>
                                    <th class="px-6 py-4">Usuário</th>
                                    <th class="px-6 py-4">Papel (Role)</th>
                                    <th class="px-6 py-4">Cadastro</th>
                                    <th class="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($users as $user)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white uppercase shrink-0">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-white">{{ $user->name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($user->isMaster())
                                                <span class="px-2 py-1 rounded bg-red-500/10 text-red-400 border border-red-500/20 text-xs font-bold uppercase">Master</span>
                                            @elseif($user->isAdmin())
                                                <span class="px-2 py-1 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold uppercase">Admin</span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-slate-700 text-slate-300 border border-white/5 text-xs font-bold uppercase">Cliente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end items-center gap-3">
                                                
                                                {{-- BOTÃO EDITAR --}}
                                                @if($user->id !== auth()->id() || $user->isMaster()) 
                                                    <button 
                                                        @click="
                                                            editForm.name = '{{ addslashes($user->name) }}';
                                                            editForm.email = '{{ $user->email }}';
                                                            editForm.role = '{{ $user->role }}';
                                                            editForm.action = '{{ route('master.users.update', $user) }}';
                                                            showEditModal = true;
                                                        "
                                                        class="text-blue-500 hover:text-blue-400 hover:underline text-xs font-bold">
                                                        Editar
                                                    </button>
                                                @endif

                                                {{-- ✅ BOTÃO EXCLUIR (ABRE MODAL) --}}
                                                @if($user->id !== auth()->id() && !$user->isMaster())
                                                    <button 
                                                        @click="
                                                            deleteForm.action = '{{ route('master.users.destroy', $user) }}';
                                                            deleteForm.name = '{{ addslashes($user->name) }}';
                                                            showDeleteModal = true;
                                                        "
                                                        class="text-red-500 hover:text-red-400 hover:underline text-xs font-bold">
                                                        Excluir
                                                    </button>
                                                @else
                                                    @if($user->id === auth()->id())
                                                        <span class="text-slate-600 text-xs italic">Você</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t border-white/10">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

            {{-- 1. MODAL: CRIAR USUÁRIO --}}
            <div x-show="showCreateModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                 x-transition>
                
                <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" 
                     @click.away="showCreateModal = false">
                    
                    <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                        <h3 class="text-lg font-bold text-white">Novo Usuário</h3>
                        <button @click="showCreateModal = false" class="text-slate-400 hover:text-white">✕</button>
                    </div>

                    <form action="{{ route('master.users.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        {{-- Campos de Criação --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nome Completo</label>
                            <input type="text" name="name" required class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">E-mail</label>
                            <input type="email" name="email" required class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Senha Inicial</label>
                            <input type="text" name="password" required minlength="8" class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-red-500 focus:ring-red-500" placeholder="Mínimo 8 caracteres">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nível de Acesso</label>
                            <select name="role" class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-red-500 focus:ring-red-500">
                                <option value="client">Cliente (Padrão)</option>
                                <option value="admin">Admin (Suporte)</option>
                                <option value="master">Master (Segurança)</option>
                            </select>
                        </div>
                        <div class="pt-4 flex justify-end gap-3 border-t border-white/10 mt-4">
                            <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold">Cancelar</button>
                            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 transition text-sm">Criar Usuário</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. MODAL: EDITAR USUÁRIO --}}
            <div x-show="showEditModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                 x-transition>
                
                <div class="bg-slate-900 border border-blue-500/30 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative" 
                     @click.away="showEditModal = false">
                     
                    <div class="h-1 w-full bg-blue-500"></div> 

                    <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                        <h3 class="text-lg font-bold text-white">Editar Usuário</h3>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-white">✕</button>
                    </div>

                    <form :action="editForm.action" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT') 
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nome Completo</label>
                            <input type="text" name="name" x-model="editForm.name" required class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">E-mail</label>
                            <input type="email" name="email" x-model="editForm.email" required class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nova Senha (Opcional)</label>
                            <input type="text" name="password" minlength="8" class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-blue-500 focus:ring-blue-500" placeholder="Deixe em branco para manter">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nível de Acesso</label>
                            <select name="role" x-model="editForm.role" class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="client">Cliente (Padrão)</option>
                                <option value="admin">Admin (Suporte)</option>
                                <option value="master">Master (Segurança)</option>
                            </select>
                        </div>

                        <div class="pt-4 flex justify-end gap-3 border-t border-white/10 mt-4">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold">Cancelar</button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition text-sm">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 3. ✅ NOVO MODAL: CONFIRMAR EXCLUSÃO (PERSONALIZADO) --}}
            <div x-show="showDeleteModal" style="display: none;" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
                 x-transition>
                
                <div class="bg-slate-900 border border-red-500/30 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden relative transform transition-all scale-100" 
                     @click.away="showDeleteModal = false">
                    
                    {{-- Faixa de Alerta --}}
                    <div class="h-2 w-full bg-gradient-to-r from-red-600 to-orange-600"></div>

                    <div class="p-6 text-center">
                        {{-- Ícone Animado --}}
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/10 mb-4 border border-red-500/20">
                            <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">Tem certeza?</h3>
                        <p class="text-slate-400 text-sm mb-6">
                            Você está prestes a excluir o usuário <br>
                            <strong class="text-white text-base" x-text="deleteForm.name"></strong>.
                            <br><br>
                            <span class="text-red-400 font-bold uppercase text-xs">Esta ação é irreversível.</span>
                        </p>

                        <form :action="deleteForm.action" method="POST">
                            @csrf
                            @method('DELETE')
                            
                            <div class="flex justify-center gap-3">
                                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition text-sm font-bold border border-transparent hover:border-white/10">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 transition text-sm flex items-center gap-2">
                                    Sim, Excluir
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>