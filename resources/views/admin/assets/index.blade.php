<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    {{ __('Gestão de Inventário') }}
                </h2>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.assets.export') }}" 
                   class="group flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-white/10 text-slate-300 hover:text-white rounded-xl font-bold text-sm transition shadow-lg">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-cyan-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Exportar CSV</span>
                </a>
                <a href="{{ route('admin.assets.create') }}" 
                   class="group flex items-center gap-2 px-4 py-2.5 bg-cyan-600 hover:bg-cyan-500 border border-cyan-400/30 rounded-xl text-sm font-bold text-white transition hover:shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Novo Equipamento</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON LOADER --}}
            <div x-show="!loaded" class="space-y-4 animate-pulse">
                <div class="h-16 bg-white/5 rounded-2xl w-full border border-white/5"></div>
                <div class="h-96 bg-white/5 rounded-2xl w-full border border-white/5"></div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 class="space-y-6"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- FILTROS --}}
                <div class="sticky top-0 z-20 rounded-2xl bg-slate-900/90 backdrop-blur-xl border border-white/10 shadow-2xl p-2">
                    <form method="GET" action="{{ route('admin.assets.index') }}" class="flex flex-col md:flex-row gap-2">
                        <div class="relative flex-1 group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 group-focus-within:text-cyan-400 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Buscar por Nome, Patrimônio ou Serial..." 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-slate-200 focus:border-cyan-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-cyan-500/20 outline-none transition placeholder-slate-600 text-sm">
                        </div>
                        
                        <div class="relative w-full md:w-48 group">
                            <select name="type" onchange="this.form.submit()" 
                                    class="w-full px-4 py-2.5 rounded-xl bg-slate-950/50 border border-white/5 text-slate-200 focus:border-cyan-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-cyan-500/20 outline-none transition cursor-pointer appearance-none text-sm">
                                <option value="" class="bg-slate-900">Todos os Tipos</option>
                                <option value="Laptop" class="bg-slate-900" {{ request('type') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Desktop" class="bg-slate-900" {{ request('type') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="Monitor" class="bg-slate-900" {{ request('type') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="Impressora" class="bg-slate-900" {{ request('type') == 'Impressora' ? 'selected' : '' }}>Impressora</option>
                                <option value="Celular" class="bg-slate-900" {{ request('type') == 'Celular' ? 'selected' : '' }}>Celular</option>
                            </select>
                        </div>

                        @if(request()->hasAny(['search', 'type']))
                            <a href="{{ route('admin.assets.index') }}" class="px-4 py-2.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 hover:text-red-300 transition flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- TABELA --}}
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 overflow-hidden shadow-xl backdrop-blur-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5 text-xs uppercase tracking-wider text-slate-400 font-bold">
                                    <th class="px-6 py-4">Equipamento</th>
                                    <th class="px-6 py-4">Identificação</th>
                                    <th class="px-6 py-4">Responsável</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @forelse($assets as $asset)
                                    <tr class="group hover:bg-white/[0.02] transition duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-xl bg-slate-800 flex items-center justify-center text-xl border border-white/5 group-hover:border-cyan-500/30 transition">
                                                    @switch($asset->type)
                                                        @case('Laptop') 💻 @break
                                                        @case('Monitor') 🖥️ @break
                                                        @case('Celular') 📱 @break
                                                        @case('Impressora') 🖨️ @break
                                                        @default 📦
                                                    @endswitch
                                                </div>
                                                <div>
                                                    <div class="font-bold text-white group-hover:text-cyan-400 transition">{{ $asset->name }}</div>
                                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest">{{ $asset->brand }} {{ $asset->model }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-slate-300 font-mono text-xs">Pat: <span class="text-indigo-400 font-bold">#{{ $asset->tag }}</span></div>
                                            <div class="text-slate-500 text-[10px] mt-0.5">S/N: {{ $asset->serial_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($asset->user)
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $asset->user->profile_photo_url }}" class="h-6 w-6 rounded-full border border-white/10">
                                                    <span class="text-slate-300 font-medium">{{ $asset->user->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-600 italic text-xs">Disponível</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-{{ $asset->getStatusColor() }}-500/10 text-{{ $asset->getStatusColor() }}-400 border border-{{ $asset->getStatusColor() }}-500/20">
                                                {{ $asset->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.assets.edit', $asset) }}" 
                                                   class="p-2 rounded-lg bg-slate-800 hover:bg-cyan-600 text-slate-400 hover:text-white border border-white/5 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Excluir este equipamento?')"
                                                            class="p-2 rounded-lg bg-slate-800 hover:bg-red-600 text-slate-400 hover:text-white border border-white/5 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="h-16 w-16 bg-slate-800/50 rounded-full flex items-center justify-center mb-4 border border-white/5">
                                                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                </div>
                                                <h3 class="text-white font-medium mb-1">Nenhum equipamento encontrado</h3>
                                                <p class="text-slate-500 text-sm">Comece cadastrando os ativos de TI da empresa.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($assets->hasPages())
                        <div class="px-6 py-4 border-t border-white/5 bg-slate-900/50">
                            {{ $assets->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
