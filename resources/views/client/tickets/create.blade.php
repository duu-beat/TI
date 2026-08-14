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
                          x-data="attachmentUploader()">
                        @csrf

                        <x-validation-errors class="mb-6" />

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
                                <div class="mb-3 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-200">Anexos de apoio</label>
                                        <p class="mt-1 text-xs text-slate-500">Veja fotos, PDFs e documentos antes de enviar o chamado.</p>
                                    </div>
                                    <span class="text-xs font-medium text-slate-500">Até 5 arquivos · 10 MB cada</span>
                                </div>

                                <div class="relative rounded-2xl border-2 border-dashed border-white/10 bg-slate-950/30 p-7 text-center transition cursor-pointer hover:border-indigo-400/50 hover:bg-indigo-500/[0.04]"
                                     @dragover.prevent="dragover = true"
                                     @dragleave.prevent="dragover = false"
                                     @drop.prevent="handleDrop($event)"
                                     @click="$refs.attachmentsInput.click()"
                                     :class="dragover ? 'border-indigo-400 bg-indigo-500/10' : ''">
                                    <input type="file" multiple name="attachments[]" class="hidden" x-ref="attachmentsInput" accept=".jpg,.jpeg,.png,.webp,.pdf,.txt,.doc,.docx,.xls,.xlsx,.zip" @change="handleFiles($event.target.files)">
                                    <div class="pointer-events-none">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/15 text-indigo-300">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0L8 8m4-4 4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" /></svg>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold text-slate-200"><span class="text-indigo-300">Selecionar arquivos</span> ou arrastar para esta área</p>
                                        <p class="mt-1 text-xs text-slate-500">Imagens, PDF, TXT, Word, Excel e ZIP.</p>
                                    </div>
                                </div>

                                <div x-show="errors.length" x-cloak class="mt-3 rounded-xl border border-red-500/20 bg-red-500/10 p-3 text-sm text-red-200" role="alert">
                                    <template x-for="error in errors" :key="error"><p x-text="error"></p></template>
                                </div>

                                <div x-show="files.length" x-cloak class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    <template x-for="(item, index) in files" :key="item.file.name + item.file.size">
                                        <article class="group overflow-hidden rounded-2xl border border-white/10 bg-slate-950/50">
                                            <template x-if="item.isImage">
                                                <img :src="item.preview" :alt="item.file.name" class="h-32 w-full object-cover bg-slate-900">
                                            </template>
                                            <template x-if="item.isPdf">
                                                <iframe :src="item.preview" :title="'Pré-visualização de ' + item.file.name" class="h-32 w-full bg-white" loading="lazy"></iframe>
                                            </template>
                                            <template x-if="!item.isImage && !item.isPdf">
                                                <div class="flex h-32 items-center justify-center bg-slate-900/80 text-4xl">📎</div>
                                            </template>
                                            <div class="p-3">
                                                <div class="flex items-start gap-2">
                                                    <div class="min-w-0 flex-1">
                                                        <p class="truncate text-sm font-semibold text-slate-200" x-text="item.file.name"></p>
                                                        <p class="mt-1 text-xs text-slate-500"><span x-text="fileKind(item)"></span> · <span x-text="formatBytes(item.file.size)"></span></p>
                                                    </div>
                                                    <button type="button" @click.stop="removeFile(index)" class="rounded-lg p-1.5 text-slate-500 hover:bg-red-500/10 hover:text-red-300 transition" :aria-label="'Remover ' + item.file.name">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </article>
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

</x-app-layout>