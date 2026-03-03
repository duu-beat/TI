<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-white leading-tight">
                📦 Inventário de TI
            </h2>
            <a href="{{ route('admin.assets.create') }}" 
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-indigo-500/20">
                + Novo Equipamento
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Filtros --}}
            <div class="bg-slate-900/50 border border-white/10 rounded-2xl p-6 mb-8 backdrop-blur-sm">
                <form action="{{ route('admin.assets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-[10px] text-slate-500 uppercase font-bold mb-1 block">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nome, Patrimônio ou Serial..."
                               class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-500 uppercase font-bold mb-1 block">Tipo</label>
                        <select name="type" class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos</option>
                            <option value="Laptop" {{ request('type') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="Desktop" {{ request('type') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                            <option value="Monitor" {{ request('type') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                            <option value="Impressora" {{ request('type') == 'Impressora' ? 'selected' : '' }}>Impressora</option>
                            <option value="Celular" {{ request('type') == 'Celular' ? 'selected' : '' }}>Celular</option>
                            <option value="Outros" {{ request('type') == 'Outros' ? 'selected' : '' }}>Outros</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white py-2 rounded-xl border border-white/10 transition">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabela --}}
            <div class="bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Patrimônio</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Equipamento</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-indigo-400 font-bold">#{{ $asset->tag }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-white">{{ $asset->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $asset->type }} • {{ $asset->brand }} {{ $asset->model }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($asset->user)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $asset->user->profile_photo_url }}" class="w-6 h-6 rounded-full border border-white/10">
                                            <span class="text-sm text-slate-300">{{ $asset->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-600 italic">Disponível</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-{{ $asset->getStatusColor() }}-500/10 text-{{ $asset->getStatusColor() }}-400 border border-{{ $asset->getStatusColor() }}-500/20">
                                        {{ $asset->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.assets.edit', $asset) }}" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white rounded-lg transition border border-white/5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Remover este equipamento?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-slate-800 hover:bg-red-600 text-slate-400 hover:text-white rounded-lg transition border border-white/5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                                    Nenhum equipamento encontrado no inventário.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
