<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Gerenciar Tags
            </h2>
            {{-- Botão Trigger Modal Criar --}}
            <button @click="$dispatch('open-create-modal')" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Tag
            </button>
        </div>
    </x-slot>

    {{-- STATE ALPINE CENTRALIZADO --}}
    <div class="py-8" 
         x-data="{ 
            loaded: false, 
            showCreateModal: false,
            showEditModal: false,
            editForm: { id: null, name: '', color: '#3B82F6', description: '', action: '' }
         }" 
         x-init="setTimeout(() => loaded = true, 400)"
         @open-create-modal.window="showCreateModal = true"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Feedback Messages --}}
            @if(session('success'))
            <div x-show="loaded" x-transition class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <div x-show="!loaded" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-pulse">
                @for($i = 0; $i < 6; $i++)
                <div class="bg-white/5 border border-white/5 rounded-xl p-6 h-48 flex flex-col justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-full"></div>
                        <div class="h-6 w-32 bg-white/10 rounded"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-3 bg-white/5 rounded w-full"></div>
                        <div class="h-3 bg-white/5 rounded w-2/3"></div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <div class="h-8 flex-1 bg-white/10 rounded-lg"></div>
                        <div class="h-8 flex-1 bg-white/10 rounded-lg"></div>
                    </div>
                </div>
                @endfor
            </div>

            <div x-show="loaded" style="display: none;" 
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                @forelse($tags as $tag)
                <div class="group bg-slate-800/50 backdrop-blur-sm border border-white/5 rounded-xl p-6 hover:bg-slate-800 hover:border-indigo-500/30 transition-all duration-300 relative overflow-hidden">
                    {{-- Decorative Glow --}}
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-{{ $tag->color }}-500/10 rounded-full blur-2xl group-hover:bg-{{ $tag->color }}-500/20 transition" style="background-color: {{ $tag->color }}10;"></div>

                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full shadow-[0_0_10px_rgba(0,0,0,0.5)]" style="background-color: {{ $tag->color }}; box-shadow: 0 0 10px {{ $tag->color }}80;"></div>
                            <h3 class="text-lg font-bold text-white tracking-wide">{{ $tag->name }}</h3>
                        </div>
                        <span class="bg-slate-950/50 border border-white/5 text-slate-400 text-[10px] font-mono px-2 py-1 rounded-lg">
                            {{ $tag->tickets_count }} tickets
                        </span>
                    </div>

                    <p class="text-sm text-slate-400 mb-6 min-h-[40px] line-clamp-2">
                        {{ $tag->description ?? 'Sem descrição definida.' }}
                    </p>

                    <div class="flex items-center gap-2 relative z-10">
                        <button @click="
                                    editForm.id = {{ $tag->id }};
                                    editForm.name = '{{ addslashes($tag->name) }}';
                                    editForm.color = '{{ $tag->color }}';
                                    editForm.description = '{{ addslashes($tag->description ?? '') }}';
                                    editForm.action = '{{ route('admin.tags.update', $tag) }}';
                                    showEditModal = true;
                                " 
                                class="flex-1 bg-slate-700/50 hover:bg-indigo-600 border border-white/5 hover:border-indigo-500 text-slate-300 hover:text-white text-xs font-bold uppercase tracking-wider px-3 py-2.5 rounded-lg transition-all duration-200">
                            Editar
                        </button>
                        
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta tag?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-500/5 hover:bg-red-500 border border-red-500/20 hover:border-red-500 text-red-400 hover:text-white text-xs font-bold uppercase tracking-wider px-3 py-2.5 rounded-lg transition-all duration-200">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 rounded-3xl border-2 border-dashed border-white/10 bg-white/5">
                    <div class="h-16 w-16 bg-slate-800 rounded-full flex items-center justify-center mb-4 text-3xl">🏷️</div>
                    <h3 class="text-white font-bold text-lg">Nenhuma tag encontrada</h3>
                    <p class="text-slate-400 text-sm mt-1 mb-6">Crie tags para organizar os chamados por categoria ou prioridade.</p>
                    <button @click="showCreateModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/20 transition">
                        Criar Primeira Tag
                    </button>
                </div>
                @endforelse
            </div>

            @if($tags->hasPages())
            <div class="mt-8 border-t border-white/5 pt-6">
                {{ $tags->links() }}
            </div>
            @endif
        </div>

        {{-- 1. MODAL CRIAR TAG --}}
        <div x-show="showCreateModal" style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition>
            
            <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative" @click.away="showCreateModal = false">
                <div class="h-1 w-full bg-indigo-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white">Nova Tag</h3>
                        <button @click="showCreateModal = false" class="text-slate-400 hover:text-white transition">✕</button>
                    </div>

                    <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nome da Tag</label>
                            <input type="text" name="name" required class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                        </div>

                        <div x-data="{ color: '#3B82F6' }">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Cor de Identificação</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-10 rounded-xl overflow-hidden shadow-inner border border-white/10">
                                    <input type="color" name="color" x-model="color" class="absolute -top-2 -left-2 w-16 h-16 cursor-pointer p-0 border-0">
                                </div>
                                <input type="text" name="color_hex" x-model="color" class="flex-1 bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono text-sm uppercase focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Descrição</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-indigo-500 focus:border-indigo-500 transition outline-none resize-none"></textarea>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="showCreateModal = false" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 py-2.5 rounded-xl font-bold transition">Cancelar</button>
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/20 transition">Criar Tag</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. MODAL EDITAR TAG --}}
        <div x-show="showEditModal" style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition>
            
            <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative" @click.away="showEditModal = false">
                <div class="h-1 w-full bg-indigo-500" :style="'background-color: ' + editForm.color"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white">Editar Tag</h3>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-white transition">✕</button>
                    </div>

                    <form :action="editForm.action" method="POST" class="space-y-5">
                        @csrf @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nome da Tag</label>
                            <input type="text" name="name" x-model="editForm.name" required class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Cor de Identificação</label>
                            <div class="flex gap-3">
                                <div class="relative w-12 h-10 rounded-xl overflow-hidden shadow-inner border border-white/10">
                                    <input type="color" name="color" x-model="editForm.color" class="absolute -top-2 -left-2 w-16 h-16 cursor-pointer p-0 border-0">
                                </div>
                                <input type="text" name="color_hex" x-model="editForm.color" class="flex-1 bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono text-sm uppercase focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Descrição</label>
                            <textarea name="description" x-model="editForm.description" rows="3" class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-indigo-500 focus:border-indigo-500 transition outline-none resize-none"></textarea>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="showEditModal = false" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 py-2.5 rounded-xl font-bold transition">Cancelar</button>
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/20 transition">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>