<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.checklists.index') }}" 
               class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    📋 Editar: {{ $checklist->title }}
                </h2>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">Atualizar modelo de checklist para {{ $checklist->category }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl">
                {{-- Background Decorativo --}}
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <form action="{{ route('admin.checklists.update', $checklist) }}" method="POST" 
                      x-data="{ items: {{ json_encode($checklist->items->pluck('content')) }} }" 
                      class="relative z-10 p-8 sm:p-10 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Título --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Título do Checklist</label>
                            <input type="text" name="title" value="{{ old('title', $checklist->title) }}" required 
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                            <x-input-error for="title" class="mt-2" />
                        </div>

                        {{-- Categoria --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Categoria do Chamado</label>
                            <select name="category" required 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="Hardware" class="bg-slate-900" {{ old('category', $checklist->category) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" class="bg-slate-900" {{ old('category', $checklist->category) == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Rede" class="bg-slate-900" {{ old('category', $checklist->category) == 'Rede' ? 'selected' : '' }}>Rede</option>
                                <option value="Acessos" class="bg-slate-900" {{ old('category', $checklist->category) == 'Acessos' ? 'selected' : '' }}>Acessos</option>
                                <option value="Outros" class="bg-slate-900" {{ old('category', $checklist->category) == 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                            <x-input-error for="category" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $checklist->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-medium text-slate-300 group-hover:text-white transition">Modelo Ativo</span>
                            </label>
                        </div>
                    </div>

                    {{-- Itens do Checklist --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-white uppercase tracking-widest">Passos Técnicos</h3>
                            <span class="text-[10px] text-slate-500 font-bold uppercase" x-text="items.length + ' itens'"></span>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-3 group animate-fade-in-up">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-800 border border-white/5 flex items-center justify-center text-xs font-bold text-slate-500" x-text="index + 1"></div>
                                    <input type="text" name="items[]" required x-model="items[index]"
                                           class="flex-1 bg-slate-950/50 border-white/5 rounded-xl py-2.5 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/10 outline-none transition text-sm">
                                    <button type="button" @click="items.splice(index, 1)" 
                                            class="p-2.5 rounded-xl bg-slate-800 hover:bg-red-600/20 text-slate-500 hover:text-red-400 border border-white/5 transition-all"
                                            x-show="items.length > 1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="items.push('')" 
                                class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-slate-500 hover:text-indigo-400 hover:border-indigo-500/30 hover:bg-indigo-500/5 transition-all font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Adicionar Novo Passo
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-white/5">
                        <a href="{{ route('admin.checklists.index') }}" 
                           class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-2xl transition text-center">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-10 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 active:translate-y-0">
                            Atualizar Modelo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
