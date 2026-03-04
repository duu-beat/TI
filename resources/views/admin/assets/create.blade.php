<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.assets.index') }}" 
               class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    📦 Novo Equipamento
                </h2>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">Adicionar ativo ao inventário de TI</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl">
                {{-- Background Decorativo --}}
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <form action="{{ route('admin.assets.store') }}" method="POST" class="relative z-10 p-8 sm:p-10 space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nome --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Nome do Equipamento</label>
                            <input type="text" name="name" required placeholder="Ex: Notebook Dell Latitude 3420"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        {{-- Patrimônio --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Nº Patrimônio (Tag)</label>
                            <input type="text" name="tag" required placeholder="Ex: TI-001"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                            <x-input-error for="tag" class="mt-2" />
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Tipo de Ativo</label>
                            <select name="type" required 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="Laptop" class="bg-slate-900">Laptop</option>
                                <option value="Desktop" class="bg-slate-900">Desktop</option>
                                <option value="Monitor" class="bg-slate-900">Monitor</option>
                                <option value="Impressora" class="bg-slate-900">Impressora</option>
                                <option value="Celular" class="bg-slate-900">Celular</option>
                                <option value="Outros" class="bg-slate-900">Outros</option>
                            </select>
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Marca</label>
                            <input type="text" name="brand" placeholder="Ex: Dell, HP, Apple"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                        </div>

                        {{-- Modelo --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Modelo</label>
                            <input type="text" name="model" placeholder="Ex: Latitude 3420"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                        </div>

                        {{-- Serial --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Número de Série</label>
                            <input type="text" name="serial_number" placeholder="S/N"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                        </div>

                        {{-- Usuário Responsável --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Usuário Responsável</label>
                            <select name="user_id" 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="" class="bg-slate-900">-- Sem Vínculo (Em Estoque) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" class="bg-slate-900">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Status Inicial</label>
                            <select name="status" required 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="active" class="bg-slate-900">Ativo</option>
                                <option value="maintenance" class="bg-slate-900">Em Manutenção</option>
                                <option value="retired" class="bg-slate-900">Aposentado</option>
                                <option value="lost" class="bg-slate-900">Extraviado</option>
                            </select>
                        </div>

                        {{-- Datas --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Data de Compra</label>
                            <input type="date" name="purchase_date"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Expiração da Garantia</label>
                            <input type="date" name="warranty_expiration"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                        </div>

                        {{-- Notas --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Observações Técnicas</label>
                            <textarea name="notes" rows="4" placeholder="Detalhes técnicos, histórico de manutenção, etc."
                                      class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-white/5">
                        <a href="{{ route('admin.assets.index') }}" 
                           class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-2xl transition text-center">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-10 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 active:translate-y-0">
                            Salvar Equipamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
