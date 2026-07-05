<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Editar Artigo: {{ $article->title }}
            </h2>
            <a href="{{ route('admin.wiki.index') }}" class="text-sm text-slate-400 hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar para a Base
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900/50 border border-white/10 rounded-3xl overflow-hidden backdrop-blur-sm">
                <form action="{{ route('admin.wiki.update', $article) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Título --}}
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Título do Artigo</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required
                                   class="w-full bg-slate-950 border-white/10 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                            <x-input-error for="title" class="mt-2" />
                        </div>

                        {{-- Categoria --}}
                        <div>
                            <label for="category" class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Categoria</label>
                            <select name="category" id="category" required
                                    class="w-full bg-slate-950 border-white/10 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                                <option value="Redes" {{ $article->category == 'Redes' ? 'selected' : '' }}>Redes</option>
                                <option value="Hardware" {{ $article->category == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ $article->category == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Segurança" {{ $article->category == 'Segurança' ? 'selected' : '' }}>Segurança</option>
                                <option value="Acessos" {{ $article->category == 'Acessos' ? 'selected' : '' }}>Acessos</option>
                                <option value="Outros" {{ $article->category == 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                            <x-input-error for="category" class="mt-2" />
                        </div>

                        {{-- Status de Publicação --}}
                        <div class="flex items-center pt-8">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $article->is_published ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-medium text-slate-300">Artigo Publicado</span>
                            </label>
                        </div>
                    </div>

                    {{-- Conteúdo --}}
                    <div>
                        <label for="content" class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Conteúdo (Markdown suportado)</label>
                        <textarea name="content" id="content" rows="15" required
                                  class="w-full bg-slate-950 border-white/10 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition font-mono text-sm">{{ old('content', $article->content) }}</textarea>
                        <x-input-error for="content" class="mt-2" />
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <button type="button" 
                                onclick="if(confirm('Tem certeza que deseja excluir este artigo?')) document.getElementById('delete-form').submit();"
                                class="px-6 py-3 rounded-xl text-red-500 hover:bg-red-500/10 transition font-bold">
                            Excluir Artigo
                        </button>
                        
                        <div class="flex gap-4">
                            <a href="{{ route('admin.wiki.show', $article) }}" class="px-6 py-3 rounded-xl text-slate-400 hover:text-white transition font-bold">
                                Cancelar
                            </a>
                            <button type="submit" class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition shadow-lg shadow-indigo-500/20">
                                Atualizar Artigo
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('admin.wiki.destroy', $article) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
