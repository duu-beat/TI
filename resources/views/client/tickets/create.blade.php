<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-3">
                <a href="{{ route('client.tickets.index') }}"
                   class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-slate-900/80 text-slate-400 transition hover:border-indigo-400/40 hover:bg-indigo-500/10 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   aria-label="Voltar para meus chamados">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-300">Central de suporte</p>
                    <h2 class="truncate text-lg font-bold text-white sm:text-xl">Novo chamado</h2>
                </div>
            </div>
            <a href="{{ route('client.tickets.index') }}" class="hidden rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white sm:inline-flex">Meus chamados</a>
        </div>
    </x-slot>

    <main class="min-h-screen py-6 sm:py-8" aria-labelledby="ticket-create-title">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-7 overflow-hidden rounded-3xl border border-white/10 bg-slate-900/65 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
                <div class="relative px-6 py-7 sm:px-8 sm:py-8">
                    <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-indigo-500/15 blur-3xl"></div>
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-indigo-300">Suporte organizado</p>
                            <h1 id="ticket-create-title" class="text-2xl font-black tracking-tight text-white sm:text-3xl">Conte o que aconteceu.</h1>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Com algumas informações objetivas, a equipe consegue direcionar seu atendimento mais rápido.</p>
                        </div>
                        <div class="inline-flex w-fit items-center gap-2 rounded-xl border border-emerald-400/15 bg-emerald-400/5 px-3 py-2 text-xs font-semibold text-emerald-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" /></svg>
                            Leva menos de 2 minutos
                        </div>
                    </div>
                </div>
                <ol class="grid border-t border-white/5 bg-slate-950/30 sm:grid-cols-3" aria-label="Etapas da abertura de chamado">
                    <li class="flex items-center gap-3 border-b border-white/5 px-5 py-4 sm:border-b-0 sm:border-r sm:px-6">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-500 text-xs font-black text-white">1</span>
                        <div><p class="text-xs font-bold text-white">Contexto</p><p class="text-[11px] text-slate-500">Categoria e urgência</p></div>
                    </li>
                    <li class="flex items-center gap-3 border-b border-white/5 px-5 py-4 sm:border-b-0 sm:border-r sm:px-6">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full border border-white/10 bg-white/5 text-xs font-black text-slate-300">2</span>
                        <div><p class="text-xs font-bold text-slate-300">Detalhes</p><p class="text-[11px] text-slate-500">Explique o problema</p></div>
                    </li>
                    <li class="flex items-center gap-3 px-5 py-4 sm:px-6">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full border border-white/10 bg-white/5 text-xs font-black text-slate-300">3</span>
                        <div><p class="text-xs font-bold text-slate-300">Evidências</p><p class="text-[11px] text-slate-500">Anexe se precisar</p></div>
                    </li>
                </ol>
            </div>

            <form action="{{ route('client.tickets.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="Object.assign(attachmentUploader(), {
                      articleSuggestions: [],
                      suggestionsLoading: false,
                      suggestionsError: '',
                      suggestionsTimer: null,
                      knowledgeSuggestionsUrl: @js(route('client.knowledge.suggestions')),
                      queueKnowledgeSuggestions() {
                          window.clearTimeout(this.suggestionsTimer);
                          const subject = this.$el.querySelector('#subject')?.value || '';
                          const description = this.$el.querySelector('#description')?.value || '';
                          const category = this.$el.querySelector('#category')?.value || '';
                          const categoryTerms = { hardware: 'hardware equipamento', software: 'software programa', network: 'rede internet', access: 'acesso senha', printer: 'impressora' };
                          const query = [subject, description, categoryTerms[category] || category].join(' ').trim();

                          if (query.length < 3) {
                              this.articleSuggestions = [];
                              this.suggestionsError = '';
                              return;
                          }

                          this.suggestionsTimer = window.setTimeout(() => this.fetchKnowledgeSuggestions(query), 350);
                      },
                      async fetchKnowledgeSuggestions(query) {
                          this.suggestionsLoading = true;
                          this.suggestionsError = '';
                          try {
                              const response = await fetch(this.knowledgeSuggestionsUrl + '?' + new URLSearchParams({ q: query }), { headers: { Accept: 'application/json' } });
                              if (!response.ok) throw new Error('Falha ao buscar sugestões.');
                              this.articleSuggestions = await response.json();
                          } catch (error) {
                              this.articleSuggestions = [];
                              this.suggestionsError = 'Não foi possível buscar artigos agora.';
                          } finally {
                              this.suggestionsLoading = false;
                          }
                      }
                  })"
                  class="grid gap-6 lg:grid-cols-12">
                @csrf

                <section class="space-y-6 lg:col-span-8" aria-label="Informações do chamado">
                    <x-validation-errors />

                    <article class="rounded-3xl border border-white/10 bg-slate-900/70 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-7">
                        <div class="mb-6 flex items-start gap-3 border-b border-white/5 pb-5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-300 ring-1 ring-inset ring-indigo-400/20">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">O que você precisa?</h3>
                                <p class="mt-1 text-xs leading-5 text-slate-500">Escolha o tipo de solicitação e indique a urgência percebida.</p>
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="category" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">Categoria</label>
                                <div class="relative">
                                    <select id="category" name="category" required @change="queueKnowledgeSuggestions()" class="block w-full appearance-none rounded-xl border border-white/10 bg-slate-950/70 px-4 py-3 pr-10 text-sm text-slate-200 shadow-inner shadow-black/10 transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                        <option value="" disabled {{ old('category') ? '' : 'selected' }} class="bg-slate-900">Selecione uma categoria</option>
                                        <option value="hardware" {{ old('category') === 'hardware' ? 'selected' : '' }} class="bg-slate-900">Hardware / Equipamento</option>
                                        <option value="software" {{ old('category') === 'software' ? 'selected' : '' }} class="bg-slate-900">Software / Programas</option>
                                        <option value="network" {{ old('category') === 'network' ? 'selected' : '' }} class="bg-slate-900">Internet / Rede</option>
                                        <option value="access" {{ old('category') === 'access' ? 'selected' : '' }} class="bg-slate-900">Acesso / Senhas</option>
                                        <option value="printer" {{ old('category') === 'printer' ? 'selected' : '' }} class="bg-slate-900">Impressoras</option>
                                        <option value="other" {{ old('category') === 'other' ? 'selected' : '' }} class="bg-slate-900">Outro assunto</option>
                                    </select>
                                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 9 6 6 6-6" /></svg>
                                </div>
                                <x-input-error for="category" class="mt-2" />
                            </div>

                            <fieldset>
                                <legend class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">Urgência</legend>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="group cursor-pointer">
                                        <input type="radio" name="priority" value="low" class="peer sr-only" {{ old('priority', 'medium') === 'low' ? 'checked' : '' }}>
                                        <span class="flex min-h-12 items-center justify-center rounded-xl border border-white/10 bg-slate-950/50 px-2 text-center text-xs font-semibold text-slate-400 transition group-hover:border-emerald-400/40 group-hover:text-emerald-300 peer-checked:border-emerald-400/50 peer-checked:bg-emerald-400/10 peer-checked:text-emerald-300">Baixa</span>
                                    </label>
                                    <label class="group cursor-pointer">
                                        <input type="radio" name="priority" value="medium" class="peer sr-only" {{ old('priority', 'medium') === 'medium' ? 'checked' : '' }}>
                                        <span class="flex min-h-12 items-center justify-center rounded-xl border border-white/10 bg-slate-950/50 px-2 text-center text-xs font-semibold text-slate-400 transition group-hover:border-amber-400/40 group-hover:text-amber-300 peer-checked:border-amber-400/50 peer-checked:bg-amber-400/10 peer-checked:text-amber-300">Média</span>
                                    </label>
                                    <label class="group cursor-pointer">
                                        <input type="radio" name="priority" value="high" class="peer sr-only" {{ old('priority', 'medium') === 'high' ? 'checked' : '' }}>
                                        <span class="flex min-h-12 items-center justify-center rounded-xl border border-white/10 bg-slate-950/50 px-2 text-center text-xs font-semibold text-slate-400 transition group-hover:border-rose-400/40 group-hover:text-rose-300 peer-checked:border-rose-400/50 peer-checked:bg-rose-400/10 peer-checked:text-rose-300">Alta</span>
                                    </label>
                                </div>
                                <x-input-error for="priority" class="mt-2" />
                            </fieldset>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-white/10 bg-slate-900/70 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-7">
                        <div class="mb-6 flex items-start gap-3 border-b border-white/5 pb-5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-300 ring-1 ring-inset ring-cyan-400/20">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-4l-3 4-3-4Z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Descreva a situação</h3>
                                <p class="mt-1 text-xs leading-5 text-slate-500">Inclua mensagens de erro, quando o problema começou e o que já foi tentado.</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="subject" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">Assunto</label>
                                <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required maxlength="255" @input="queueKnowledgeSuggestions()" class="block w-full rounded-xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white placeholder:text-slate-600 shadow-inner shadow-black/10 transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20" placeholder="Ex.: Não consigo acessar a VPN" />
                                <x-input-error for="subject" class="mt-2" />
                            </div>
                            <div>
                                <div class="mb-2 flex items-end justify-between gap-4">
                                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Detalhes do problema</label>
                                    <span class="text-[11px] text-slate-600">Seja o mais específico possível</span>
                                </div>
                                <textarea id="description" name="description" rows="8" required @input="queueKnowledgeSuggestions()" class="block w-full resize-y rounded-xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm leading-6 text-white placeholder:text-slate-600 shadow-inner shadow-black/10 transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20" placeholder="Descreva o problema e informe o que aparece na tela ou o que você já tentou fazer.">{{ old('description') }}</textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>
                        </div>
                    </article>

                    <section x-show="suggestionsLoading || articleSuggestions.length || suggestionsError" x-cloak class="overflow-hidden rounded-3xl border border-cyan-400/20 bg-cyan-500/[0.05] shadow-xl shadow-cyan-950/10" aria-live="polite" aria-label="Sugestões da Base de Conhecimento">
                        <div class="flex items-start gap-3 border-b border-cyan-400/10 px-5 py-4 sm:px-6"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.25C7.7 4.15 4.55 5.18 3 6.16v11.16c1.55-.98 4.7-2.01 9 0 4.3-2.01 7.45-.98 9 0V6.16c-1.55-.98-4.7-2.01-9 0Zm0 0v11.07" /></svg></span><div><h2 class="text-sm font-bold text-cyan-100">Talvez este procedimento ajude</h2><p class="mt-1 text-xs leading-5 text-slate-400">Antes de enviar, veja se algum artigo resolve o problema.</p></div></div>
                        <div class="p-4 sm:p-5"><div x-show="suggestionsLoading" class="flex items-center gap-2 text-xs text-cyan-200"><span class="h-3 w-3 animate-spin rounded-full border-2 border-cyan-300/30 border-t-cyan-300"></span>Buscando artigos relevantes...</div><p x-show="suggestionsError" x-text="suggestionsError" class="text-xs text-amber-200"></p><div x-show="!suggestionsLoading && articleSuggestions.length" class="grid gap-3"><template x-for="article in articleSuggestions" :key="article.id"><a :href="article.url" class="group rounded-2xl border border-white/10 bg-slate-950/45 p-4 transition hover:border-cyan-400/35 hover:bg-slate-950/70"><div class="flex items-center justify-between gap-3"><span class="rounded-md border border-cyan-400/15 bg-cyan-500/[0.08] px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-cyan-300" x-text="article.category || 'Geral'"></span><span class="text-[10px] font-bold text-cyan-300 transition group-hover:text-cyan-100">Abrir artigo →</span></div><h3 class="mt-2 text-sm font-bold text-slate-100" x-text="article.title"></h3><p class="mt-1 text-xs leading-5 text-slate-400" x-text="article.excerpt"></p></a></template></div></div>
                    </section>

                    <article class="rounded-3xl border border-white/10 bg-slate-900/70 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-7">
                        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-500/10 text-violet-300 ring-1 ring-inset ring-violet-400/20">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13.5 10.5-3 3m0 0-3-3m3 3v-9m5.5 5.5v6A2.5 2.5 0 0 1 13.5 19h-7A2.5 2.5 0 0 1 4 16.5v-6" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">Anexos de apoio</h3>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">Adicione fotos, PDFs ou documentos que ajudem a entender a situação.</p>
                                </div>
                            </div>
                            <span class="w-fit rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-[11px] font-medium text-slate-400">Até 5 arquivos · 10 MB cada</span>
                        </div>

                        <div class="rounded-2xl border border-dashed border-white/15 bg-slate-950/35 p-4 transition hover:border-violet-400/45 hover:bg-violet-500/[0.04]"
                             @dragover.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             @drop.prevent="handleDrop($event)"
                             :class="dragover ? 'border-violet-400 bg-violet-500/10' : ''">
                            <input id="ticket-attachments" type="file" multiple name="attachments[]" class="sr-only" x-ref="attachmentsInput" accept=".jpg,.jpeg,.png,.webp,.pdf,.txt,.doc,.docx,.xls,.xlsx,.zip" @change="handleFiles($event.target.files)">
                            <label for="ticket-attachments" class="flex cursor-pointer flex-col items-center justify-center rounded-xl px-4 py-7 text-center focus-within:outline-none">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-500/15 text-violet-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L8 8m4-4 4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
                                </span>
                                <span class="mt-3 text-sm font-semibold text-slate-200">Selecionar arquivos <span class="font-normal text-slate-500">ou arrastar para esta área</span></span>
                                <span class="mt-1 text-xs text-slate-600">JPG, PNG, WEBP, PDF, TXT, Word, Excel ou ZIP</span>
                            </label>
                        </div>

                        <div x-show="errors.length" x-cloak class="mt-3 rounded-xl border border-rose-500/20 bg-rose-500/10 p-3 text-sm text-rose-200" role="alert">
                            <template x-for="error in errors" :key="error"><p x-text="error"></p></template>
                        </div>

                        <div x-show="files.length" x-cloak class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                            <template x-for="(item, index) in files" :key="item.file.name + item.file.size">
                                <article class="overflow-hidden rounded-2xl border border-white/10 bg-slate-950/60">
                                    <template x-if="item.isImage"><img :src="item.preview" :alt="item.file.name" class="h-28 w-full object-cover" /></template>
                                    <template x-if="item.isPdf"><iframe :src="item.preview" :title="'Pré-visualização de ' + item.file.name" class="h-28 w-full bg-white" loading="lazy"></iframe></template>
                                    <template x-if="!item.isImage && !item.isPdf"><div class="flex h-28 items-center justify-center bg-white/[0.03] text-3xl">📎</div></template>
                                    <div class="flex items-start gap-2 p-3">
                                        <div class="min-w-0 flex-1"><p class="truncate text-xs font-semibold text-slate-200" x-text="item.file.name"></p><p class="mt-1 text-[11px] text-slate-500"><span x-text="fileKind(item)"></span> · <span x-text="formatBytes(item.file.size)"></span></p></div>
                                        <button type="button" @click="removeFile(index)" class="rounded-lg p-1 text-slate-500 transition hover:bg-rose-500/10 hover:text-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-500/40" :aria-label="'Remover ' + item.file.name">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18" /></svg>
                                        </button>
                                    </div>
                                </article>
                            </template>
                        </div>
                        <x-input-error for="attachments" class="mt-2" />
                    </article>
                </section>

                <aside class="space-y-5 lg:col-span-4" aria-label="Orientações de atendimento">
                    <section class="rounded-3xl border border-indigo-400/15 bg-gradient-to-br from-indigo-500/10 to-slate-900/80 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-400/15 text-indigo-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></div>
                        <h2 class="mt-4 text-base font-bold text-white">Como agilizar seu atendimento</h2>
                        <ul class="mt-4 space-y-3 text-sm leading-5 text-slate-400">
                            <li class="flex gap-2"><span class="mt-0.5 text-indigo-300">•</span><span>Informe a mensagem de erro exatamente como aparece.</span></li>
                            <li class="flex gap-2"><span class="mt-0.5 text-indigo-300">•</span><span>Diga se o problema afeta só você ou mais pessoas.</span></li>
                            <li class="flex gap-2"><span class="mt-0.5 text-indigo-300">•</span><span>Use anexos quando uma captura de tela puder ajudar.</span></li>
                        </ul>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6">
                        <h2 class="text-sm font-bold text-white">O que acontece depois?</h2>
                        <ol class="mt-5 space-y-4">
                            <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-white/10 bg-white/5 text-[11px] font-bold text-slate-300">1</span><p class="pt-0.5 text-xs leading-5 text-slate-400"><strong class="font-semibold text-slate-200">Recebimento:</strong> seu chamado entra na fila de atendimento.</p></li>
                            <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-white/10 bg-white/5 text-[11px] font-bold text-slate-300">2</span><p class="pt-0.5 text-xs leading-5 text-slate-400"><strong class="font-semibold text-slate-200">Análise:</strong> um técnico revisa as informações e define o próximo passo.</p></li>
                            <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-white/10 bg-white/5 text-[11px] font-bold text-slate-300">3</span><p class="pt-0.5 text-xs leading-5 text-slate-400"><strong class="font-semibold text-slate-200">Acompanhamento:</strong> você recebe as atualizações diretamente pelo chamado.</p></li>
                        </ol>
                    </section>
                </aside>

                <div class="lg:col-span-12">
                    <div class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-slate-900/80 p-4 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <svg class="h-4 w-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Zm10-10V7a4 4 0 0 0-8 0v4" /></svg>
                            Seus dados e anexos são enviados com segurança.
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('client.tickets.index') }}" class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">Cancelar</a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-900 active:scale-[0.98]">
                                Abrir chamado
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 14-7-4 14-3-5-7-2Z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
