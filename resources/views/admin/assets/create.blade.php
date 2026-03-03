<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.assets.index') }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl transition border border-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-white leading-tight">
                📦 Novo Equipamento
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                <form action="{{ route('admin.assets.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nome --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Nome do Equipamento</label>
                            <input type="text" name="name" required placeholder="Ex: Notebook Dell Latitude 3420"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Patrimônio --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Nº Patrimônio (Tag)</label>
                            <input type="text" name="tag" required placeholder="Ex: TI-001"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Tipo</label>
                            <select name="type" required class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Laptop">Laptop</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Impressora">Impressora</option>
                                <option value="Celular">Celular</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Marca</label>
                            <input type="text" name="brand" placeholder="Ex: Dell, HP, Apple"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Modelo --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Modelo</label>
                            <input type="text" name="model" placeholder="Ex: Latitude 3420"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Serial --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Número de Série</label>
                            <input type="text" name="serial_number" placeholder="S/N"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Usuário Responsável --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Usuário Responsável</label>
                            <select name="user_id" class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Sem Vínculo --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Status</label>
                            <select name="status" required class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="active">Ativo</option>
                                <option value="maintenance">Em Manutenção</option>
                                <option value="retired">Aposentado</option>
                                <option value="lost">Extraviado</option>
                            </select>
                        </div>

                        {{-- Datas --}}
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Data de Compra</label>
                            <input type="date" name="purchase_date"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Expiração da Garantia</label>
                            <input type="date" name="warranty_expiration"
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Notas --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Observações</label>
                            <textarea name="notes" rows="3" placeholder="Detalhes técnicos, histórico, etc."
                                      class="w-full bg-slate-950 border-white/10 rounded-xl text-slate-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t border-white/5">
                        <a href="{{ route('admin.assets.index') }}" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-xl transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-indigo-500/20">
                            Salvar Equipamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
