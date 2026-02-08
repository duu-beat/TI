<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Gerenciar Tags
            </h2>
            <button onclick="document.getElementById('createTagModal').classList.remove('hidden')" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Tag
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Grid de Tags -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($tags as $tag)
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 hover:bg-white/10 transition">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $tag->color }}"></div>
                            <h3 class="text-lg font-semibold text-white">{{ $tag->name }}</h3>
                        </div>
                        <span class="bg-slate-700/50 text-slate-300 text-xs px-2 py-1 rounded-full">
                            {{ $tag->tickets_count }} tickets
                        </span>
                    </div>

                    @if($tag->description)
                    <p class="text-sm text-slate-400 mb-4">{{ $tag->description }}</p>
                    @endif

                    <div class="flex items-center gap-2">
                        <button onclick="editTag({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}', '{{ $tag->description }}')" 
                                class="flex-1 bg-slate-700 hover:bg-slate-600 text-white text-sm px-3 py-2 rounded-lg transition">
                            Editar
                        </button>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta tag?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600/20 hover:bg-red-600/30 text-red-400 text-sm px-3 py-2 rounded-lg transition">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <p class="text-slate-400 text-lg mb-4">Nenhuma tag cadastrada</p>
                    <button onclick="document.getElementById('createTagModal').classList.remove('hidden')" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
                        Criar Primeira Tag
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Paginação -->
            @if($tags->hasPages())
            <div class="mt-6">
                {{ $tags->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Criar Tag -->
    <div id="createTagModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-slate-800 rounded-2xl border border-white/10 max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Nova Tag</h3>
                <button onclick="document.getElementById('createTagModal').classList.add('hidden')" 
                        class="text-slate-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nome da Tag</label>
                    <input type="text" name="name" required 
                           class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Cor</label>
                    <div class="flex gap-2">
                        <input type="color" name="color" value="#3B82F6" required 
                               class="w-16 h-10 bg-slate-900/50 border border-white/10 rounded-lg cursor-pointer">
                        <input type="text" name="color_hex" value="#3B82F6" 
                               class="flex-1 bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono text-sm"
                               pattern="^#[0-9A-Fa-f]{6}$"
                               onchange="this.previousElementSibling.value = this.value">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Descrição (opcional)</label>
                    <textarea name="description" rows="3" 
                              class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('createTagModal').classList.add('hidden')" 
                            class="flex-1 bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                        Criar Tag
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Tag -->
    <div id="editTagModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-slate-800 rounded-2xl border border-white/10 max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Editar Tag</h3>
                <button onclick="document.getElementById('editTagModal').classList.add('hidden')" 
                        class="text-slate-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editTagForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nome da Tag</label>
                    <input type="text" name="name" id="edit_name" required 
                           class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Cor</label>
                    <input type="color" name="color" id="edit_color" required 
                           class="w-full h-10 bg-slate-900/50 border border-white/10 rounded-lg cursor-pointer">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Descrição (opcional)</label>
                    <textarea name="description" id="edit_description" rows="3" 
                              class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('editTagModal').classList.add('hidden')" 
                            class="flex-1 bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editTag(id, name, color, description) {
            document.getElementById('editTagForm').action = `/admin/tags/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_color').value = color;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('editTagModal').classList.remove('hidden');
        }

        // Sincronizar color picker com input hex
        document.addEventListener('DOMContentLoaded', function() {
            const colorInput = document.querySelector('input[type="color"]');
            const hexInput = document.querySelector('input[name="color_hex"]');
            
            if (colorInput && hexInput) {
                colorInput.addEventListener('input', function() {
                    hexInput.value = this.value.toUpperCase();
                });
                
                hexInput.addEventListener('input', function() {
                    if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                        colorInput.value = this.value;
                    }
                });
            }
        });
    </script>
</x-app-layout>
