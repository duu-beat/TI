<x-app-layout>
    <x-slot name="header">
        Novo Chamado
    </x-slot>

    {{-- ✅ WRAPPER ALPINE ADICIONADO --}}
    <div class="py-6" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-3xl font-bold text-white">
                    <span class="text-indigo-400">Abrir</span> Chamado
                </h2>
                <p class="mt-2 text-sm text-slate-400">
                    Descreva seu problema detalhadamente para agilizar o atendimento.
                </p>
            </div>

            {{-- 💀 SKELETON LOADER (Simula o formulário) --}}
            <div x-show="!loaded" class="bg-slate-900/50 rounded-2xl border border-white/5 p-8 animate-pulse">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                        <div class="h-12 w-full bg-slate-700/20 rounded-xl"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                        <div class="h-12 w-full bg-slate-700/20 rounded-xl"></div>
                    </div>
                </div>
                <div class="space-y-2 mb-8">
                    <div class="h-4 w-40 bg-slate-700/50 rounded"></div>
                    <div class="h-12 w-full bg-slate-700/20 rounded-xl"></div>
                </div>
                <div class="space-y-2 mb-8">
                    <div class="h-4 w-40 bg-slate-700/50 rounded"></div>
                    <div class="h-40 w-full bg-slate-700/20 rounded-xl"></div>
                </div>
                <div class="flex justify-end pt-4">
                    <div class="h-12 w-40 bg-slate-700/20 rounded-xl"></div>
                </div>
            </div>

            {{-- ✅ CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;" 
                 class="bg-slate-900/50 backdrop-blur-md overflow-hidden shadow-2xl shadow-black/50 sm:rounded-2xl border border-white/10"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="p-8 sm:p-10">
                    
                    <form action="{{ route('client.tickets.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          x-data="ticketForm()">
                        @csrf

                        <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                            
                            <div class="col-span-1">
                                <label for="category" class="block font-medium text-sm text-slate-300">Categoria do Problema</label>
                                <div class="mt-2 relative">
                                    <select id="category" name="category" 
                                        class="block w-full rounded-xl border-white/10 bg-slate-950/50 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-lg transition-all text-sm py-3 px-4" required>
                                        <option value="" disabled selected class="bg-slate-900">Selecione uma opção...</option>
                                        <option value="hardware" class="bg-slate-900">🖥️ Hardware / Equipamento</option>
                                        <option value="software" class="bg-slate-900">💾 Software / Programas</option>
                                        <option value="network" class="bg-slate-900">🌐 Internet / Rede</option>
                                        <option value="access" class="bg-slate-900">🔒 Acesso / Senhas</option>
                                        <option value="printer" class="bg-slate-900">🖨️ Impressoras</option>
                                        <option value="other" class="bg-slate-900">Outros</option>
                                    </select>
                                </div>
                                <x-input-error for="category" class="mt-2" />
                            </div>

                            <div class="col-span-1">
                                <label class="block font-medium text-sm text-slate-300">Qual a Urgência?</label>
                                <div class="mt-2 flex space-x-3">
                                    <label class="cursor-pointer w-full group">
                                        <input type="radio" name="priority" value="low" class="peer sr-only">
                                        <div class="h-11 flex items-center justify-center rounded-xl border border-white/10 bg-slate-950/50 text-slate-400 
                                                    group-hover:border-emerald-500/50 group-hover:text-emerald-400
                                                    peer-checked:bg-emerald-500/10 peer-checked:text-emerald-400 peer-checked:border-emerald-500 transition-all font-medium text-sm shadow-md">
                                            🟢 Baixa
                                        </div>
                                    </label>
                                    <label class="cursor-pointer w-full group">
                                        <input type="radio" name="priority" value="medium" class="peer sr-only" checked>
                                        <div class="h-11 flex items-center justify-center rounded-xl border border-white/10 bg-slate-950/50 text-slate-400 
                                                    group-hover:border-yellow-500/50 group-hover:text-yellow-400
                                                    peer-checked:bg-yellow-500/10 peer-checked:text-yellow-400 peer-checked:border-yellow-500 transition-all font-medium text-sm shadow-md">
                                            🟡 Média
                                        </div>
                                    </label>
                                    <label class="cursor-pointer w-full group">
                                        <input type="radio" name="priority" value="high" class="peer sr-only">
                                        <div class="h-11 flex items-center justify-center rounded-xl border border-white/10 bg-slate-950/50 text-slate-400 
                                                    group-hover:border-red-500/50 group-hover:text-red-400
                                                    peer-checked:bg-red-500/10 peer-checked:text-red-400 peer-checked:border-red-500 transition-all font-medium text-sm shadow-md">
                                            🔴 Alta
                                        </div>
                                    </label>
                                </div>
                                <x-input-error for="priority" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <label for="subject" class="block font-medium text-sm text-slate-300">Assunto Resumido</label>
                                <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required 
                                       class="block mt-2 w-full py-3 px-4 rounded-xl border-white/10 bg-slate-950/50 text-slate-200 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500 shadow-lg transition-all" 
                                       placeholder="Ex: Monitor não liga, Erro ao acessar VPN..." />
                                <x-input-error for="subject" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block font-medium text-sm text-slate-300">Descrição Detalhada</label>
                                <div class="mt-2 relative">
                                    <textarea id="description" name="description" rows="6" 
                                        class="block w-full rounded-xl border-white/10 bg-slate-950/50 text-slate-200 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500 shadow-lg transition-all py-3 px-4 resize-none" 
                                        placeholder="Descreva o que aconteceu, mensagens de erro e passos para reproduzir..." required>{{ old('description') }}</textarea>
                                </div>
                                <p class="text-xs text-slate-500 mt-2 text-right">Seja o mais específico possível.</p>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <label class="block font-medium text-sm text-slate-300 mb-2">Anexos (Prints, Logs, PDFs)</label>
                                
                                <div 
                                    class="border-2 border-dashed border-white/10 rounded-xl p-8 text-center bg-slate-950/30 hover:bg-slate-900/50 hover:border-indigo-500/50 transition-all cursor-pointer relative group"
                                    @dragover.prevent="dragover = true"
                                    @dragleave.prevent="dragover = false"
                                    @drop.prevent="handleDrop($event)"
                                    @click="$refs.fileInput.click()"
                                    :class="{ 'bg-indigo-500/10 border-indigo-500': dragover }"
                                >
                                    <input type="file" multiple name="attachments[]" class="hidden" x-ref="fileInput" @change="handleFiles($event.target.files)">
                                    
                                    <div class="space-y-2 pointer-events-none">
                                        <svg class="mx-auto h-10 w-10 text-slate-500 group-hover:text-indigo-400 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-slate-400">
                                            <span class="font-medium text-indigo-400 group-hover:text-indigo-300">Clique para enviar</span> ou arraste arquivos aqui
                                        </div>
                                        <p class="text-xs text-slate-600">PNG, JPG, PDF até 5MB (Max 5 arquivos)</p>
                                    </div>
                                </div>

                                <div class="mt-4 space-y-2" x-show="files.length > 0" style="display: none;">
                                    <template x-for="(file, index) in files" :key="index">
                                        <div class="flex items-center justify-between p-3 bg-slate-800/50 border border-white/5 rounded-xl shadow-sm">
                                            <div class="flex items-center space-x-3 truncate">
                                                <div class="flex-shrink-0 h-8 w-8 rounded bg-indigo-500/20 text-indigo-400 flex items-center justify-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <span class="text-sm font-medium text-slate-200 truncate" x-text="file.name"></span>
                                                <span class="text-xs text-slate-500" x-text="(file.size / 1024).toFixed(0) + ' KB'"></span>
                                            </div>
                                            <button type="button" @click="removeFile(index)" class="text-slate-500 hover:text-red-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <x-input-error for="attachments" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-10 pt-6 border-t border-white/10">
                            <a href="{{ route('client.tickets.index') }}" class="text-sm text-slate-400 hover:text-white transition-colors mr-6">
                                Cancelar
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-base font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Abrir Chamado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ticketForm() {
            return {
                files: [],
                dragover: false,
                handleDrop(e) {
                    this.dragover = false;
                    const droppedFiles = e.dataTransfer.files;
                    this.handleFiles(droppedFiles);
                },
                handleFiles(fileList) {
                    for (let i = 0; i < fileList.length; i++) {
                        if (this.files.length >= 5) break;
                        this.files.push(fileList[i]);
                    }
                    const dataTransfer = new DataTransfer();
                    this.files.forEach(file => dataTransfer.items.add(file));
                    this.$refs.fileInput.files = dataTransfer.files;
                },
                removeFile(index) {
                    this.files.splice(index, 1);
                    const dataTransfer = new DataTransfer();
                    this.files.forEach(file => dataTransfer.items.add(file));
                    this.$refs.fileInput.files = dataTransfer.files;
                }
            }
        }
    </script>
</x-app-layout>