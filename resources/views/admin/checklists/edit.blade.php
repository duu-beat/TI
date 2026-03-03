<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Modelo de Checklist') }}: {{ $checklist->title }}
            </h2>
            <a href="{{ route('admin.checklists.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.checklists.update', $checklist) }}" method="POST" x-data="{ items: {{ json_encode($checklist->items->pluck('content')) }} }">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Título -->
                        <div>
                            <x-label for="title" value="Título do Checklist" />
                            <x-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $checklist->title) }}" required />
                            <x-input-error for="title" class="mt-2" />
                        </div>

                        <!-- Categoria -->
                        <div>
                            <x-label for="category" value="Categoria do Chamado" />
                            <select id="category" name="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="Hardware" {{ old('category', $checklist->category) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ old('category', $checklist->category) == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Rede" {{ old('category', $checklist->category) == 'Rede' ? 'selected' : '' }}>Rede</option>
                                <option value="Acessos" {{ old('category', $checklist->category) == 'Acessos' ? 'selected' : '' }}>Acessos</option>
                                <option value="Outros" {{ old('category', $checklist->category) == 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                            <x-input-error for="category" class="mt-2" />
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $checklist->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Ativo (será aplicado a novos chamados desta categoria)</span>
                        </label>
                    </div>

                    <!-- Itens do Checklist -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Itens do Checklist</h3>
                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400 font-bold" x-text="index + 1 + '.'"></span>
                                    <x-input name="items[]" type="text" class="block w-full" x-model="items[index]" required />
                                    <button type="button" @click="items.splice(index, 1)" class="text-red-500 hover:text-red-700 p-2" x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="items.push('')" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            + Adicionar Item
                        </button>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-4">
                            {{ __('Atualizar Modelo') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
