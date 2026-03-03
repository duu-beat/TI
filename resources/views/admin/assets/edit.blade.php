<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Equipamento') }}: {{ $asset->name }}
            </h2>
            <a href="{{ route('admin.assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <x-label for="name" value="Nome do Equipamento" />
                            <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $asset->name) }}" required />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Patrimônio / Tag -->
                        <div>
                            <x-label for="tag" value="Nº de Patrimônio (Tag)" />
                            <x-input id="tag" name="tag" type="text" class="mt-1 block w-full" value="{{ old('tag', $asset->tag) }}" required />
                            <x-input-error for="tag" class="mt-2" />
                        </div>

                        <!-- Tipo -->
                        <div>
                            <x-label for="type" value="Tipo de Equipamento" />
                            <select id="type" name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="notebook" {{ old('type', $asset->type) == 'notebook' ? 'selected' : '' }}>Notebook</option>
                                <option value="desktop" {{ old('type', $asset->type) == 'desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="monitor" {{ old('type', $asset->type) == 'monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="printer" {{ old('type', $asset->type) == 'printer' ? 'selected' : '' }}>Impressora</option>
                                <option value="smartphone" {{ old('type', $asset->type) == 'smartphone' ? 'selected' : '' }}>Smartphone</option>
                                <option value="other" {{ old('type', $asset->type) == 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                            <x-input-error for="type" class="mt-2" />
                        </div>

                        <!-- Usuário Responsável -->
                        <div>
                            <x-label for="user_id" value="Usuário Responsável" />
                            <select id="user_id" name="user_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="">Sem vínculo (Estoque)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $asset->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="user_id" class="mt-2" />
                        </div>

                        <!-- Marca -->
                        <div>
                            <x-label for="brand" value="Marca" />
                            <x-input id="brand" name="brand" type="text" class="mt-1 block w-full" value="{{ old('brand', $asset->brand) }}" />
                            <x-input-error for="brand" class="mt-2" />
                        </div>

                        <!-- Modelo -->
                        <div>
                            <x-label for="model" value="Modelo" />
                            <x-input id="model" name="model" type="text" class="mt-1 block w-full" value="{{ old('model', $asset->model) }}" />
                            <x-input-error for="model" class="mt-2" />
                        </div>

                        <!-- Número de Série -->
                        <div>
                            <x-label for="serial_number" value="Número de Série" />
                            <x-input id="serial_number" name="serial_number" type="text" class="mt-1 block w-full" value="{{ old('serial_number', $asset->serial_number) }}" />
                            <x-input-error for="serial_number" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-label for="status" value="Status" />
                            <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="active" {{ old('status', $asset->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Em Manutenção</option>
                                <option value="retired" {{ old('status', $asset->status) == 'retired' ? 'selected' : '' }}>Aposentado</option>
                                <option value="lost" {{ old('status', $asset->status) == 'lost' ? 'selected' : '' }}>Extraviado</option>
                            </select>
                            <x-input-error for="status" class="mt-2" />
                        </div>

                        <!-- Data de Compra -->
                        <div>
                            <x-label for="purchase_date" value="Data de Compra" />
                            <x-input id="purchase_date" name="purchase_date" type="date" class="mt-1 block w-full" value="{{ old('purchase_date', $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}" />
                            <x-input-error for="purchase_date" class="mt-2" />
                        </div>

                        <!-- Expiração da Garantia -->
                        <div>
                            <x-label for="warranty_expiration" value="Expiração da Garantia" />
                            <x-input id="warranty_expiration" name="warranty_expiration" type="date" class="mt-1 block w-full" value="{{ old('warranty_expiration', $asset->warranty_expiration ? $asset->warranty_expiration->format('Y-m-d') : '') }}" />
                            <x-input-error for="warranty_expiration" class="mt-2" />
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="mt-6">
                        <x-label for="notes" value="Observações" />
                        <textarea id="notes" name="notes" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">{{ old('notes', $asset->notes) }}</textarea>
                        <x-input-error for="notes" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-4">
                            {{ __('Atualizar Equipamento') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
