<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            {{ __('Gerenciamento de Usuários') }}
        </h2>
    </x-slot>

    {{-- ✅ MERGE DO X-DATA (showCreateModal + loaded) --}}
    <div class="py-12" x-data="{ showCreateModal: false, loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 💀 SKELETON --}}
            <div x-show="!loaded" class="space-y-6 animate-pulse">
                <div class="flex justify-between gap-4">
                    <div class="h-10 w-full max-w-md bg-slate-800/50 rounded-xl border border-white/5"></div>
                    <div class="h-10 w-40 bg-slate-800/50 rounded-xl border border-white/5"></div>
                </div>
                <div class="bg-slate-800/40 rounded-2xl border border-white/5 overflow-hidden">
                    <div class="h-12 bg-white/5 border-b border-white/5"></div>
                    @foreach(range(1, 10) as $i)
                        <div class="flex items-center gap-4 px-6 py-4 border-b border-white/5">
                            <div class="h-8 w-8 rounded-full bg-white/5"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 w-1/4 bg-white/5 rounded"></div>
                                <div class="h-3 w-1/3 bg-white/5 rounded opacity-50"></div>
                            </div>
                            <div class="h-6 w-20 bg-white/5 rounded"></div>
                            <div class="h-4 w-32 bg-white/5 rounded"></div>
                            <div class="h-4 w-12 bg-white/5 rounded ml-auto"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ✅ CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;" class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- Toolbar: Busca e Novo Usuário --}}
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <form method="GET" class="flex-1 max-w-md relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Buscar por nome ou email..." 
                               class="w-full rounded-xl border border-white/10 bg-slate-800 text-slate-300 placeholder-slate-500 focus:border-red-500 focus:ring-red-500">
                        <button type="submit" class="absolute right-3 top-2.5 text-slate-500 hover:text-white">🔍</button>
                    </form>

                    <button @click="showCreateModal = true" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-red-500/20 transition flex items-center gap-2">
                         Novo Usuário / Admin
                    </button>
                </div>

                {{-- Tabela de Usuários --}}
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
                                                <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white uppercase">
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
                                            @if($user->id !== auth()->id() && !$user->isMaster())
                                                <form action="{{ route('master.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja EXCLUIR este usuário? Essa ação é irreversível.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-400 hover:underline text-xs font-bold">
                                                        Excluir
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-slate-600 text-xs italic">Protegido</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginação --}}
                    <div class="p-4 border-t border-white/10">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

        </div>

        {{-- MODAL: CRIAR USUÁRIO --}}
        <div x-show="showCreateModal" style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition>
            
            <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" 
                 @click.away="showCreateModal = false">
                
                <div class="p-6 border-b border-white/10 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Novo Usuário</h3>
                    <button @click="showCreateModal = false" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <form action="{{ route('master.users.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    
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
                            <option value="admin" class="text-cyan-400 font-bold">Admin (Suporte)</option>
                            <option value="master" class="text-red-400 font-bold">Master (Segurança)</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 transition">
                            Criar Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>