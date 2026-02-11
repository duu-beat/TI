<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Perguntas Frequentes (FAQ)</span>
        </div>
    </x-slot>

    <div class="space-y-8" x-data="{ openItem: null, loaded: false }" x-init="setTimeout(() => loaded = true, 400)">
        
        {{-- Busca e Filtros --}}
        <div class="bg-slate-900/50 border border-white/10 rounded-2xl p-6">
            <form method="GET" action="{{ route('client.faq') }}" class="space-y-4">
                {{-- Campo de Busca --}}
                <div class="relative">
                    <label for="search" class="sr-only">Buscar no FAQ</label>
                    <input type="text" 
                           id="search"
                           name="search" 
                           value="{{ $search ?? '' }}"
                           placeholder="🔍 Buscar por palavra-chave..."
                           class="w-full px-5 py-3 pl-12 bg-slate-800 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                           aria-label="Campo de busca no FAQ">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                {{-- Filtro por Categoria --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('client.faq') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !$category ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white' }}"
                       aria-label="Todas as categorias">
                        Todas
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('client.faq', ['category' => $cat['slug']]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $category === $cat['slug'] ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white' }}"
                           aria-label="Filtrar por {{ $cat['name'] }}">
                            {{ $cat['icon'] }} {{ $cat['name'] }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Skeleton Loader --}}
        <div x-show="!loaded" class="space-y-6 animate-pulse">
            @for($i = 0; $i < 3; $i++)
                <div class="space-y-4">
                    {{-- Header da categoria --}}
                    <div class="flex items-center gap-3 px-2">
                        <div class="h-8 w-8 bg-slate-700/50 rounded"></div>
                        <div class="h-7 w-48 bg-slate-700/50 rounded"></div>
                        <div class="h-6 w-20 bg-slate-700/50 rounded-full"></div>
                    </div>
                    
                    {{-- Perguntas --}}
                    <div class="space-y-3">
                        @for($j = 0; $j < 4; $j++)
                            <div class="bg-slate-900/50 border border-white/5 rounded-xl p-5">
                                <div class="h-5 bg-slate-700/50 rounded w-3/4 mb-3"></div>
                                <div class="space-y-2">
                                    <div class="h-3 bg-slate-700/50 rounded w-full"></div>
                                    <div class="h-3 bg-slate-700/50 rounded w-5/6"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endfor
        </div>

        {{-- Resultados --}}
        <div x-show="loaded" style="display: none;">
        @if(count($faqs) > 0)
            @foreach($faqs as $categoryData)
                <div class="space-y-4">
                    {{-- Cabeçalho da Categoria --}}
                    <div class="flex items-center gap-3 px-2">
                        <span class="text-3xl" aria-hidden="true">{{ $categoryData['icon'] }}</span>
                        <h2 class="text-2xl font-bold text-white">{{ $categoryData['name'] }}</h2>
                        <span class="px-3 py-1 rounded-full bg-slate-800 text-slate-400 text-sm font-medium">
                            {{ count($categoryData['items']) }} {{ count($categoryData['items']) === 1 ? 'pergunta' : 'perguntas' }}
                        </span>
                    </div>

                    {{-- Lista de Perguntas --}}
                    <div class="space-y-3">
                        @foreach($categoryData['items'] as $index => $item)
                            @php
                                $itemId = $categoryData['slug'] . '-' . $index;
                            @endphp
                            <div class="bg-slate-900/50 border border-white/10 rounded-xl overflow-hidden hover:border-white/20 transition">
                                <button @click="openItem === '{{ $itemId }}' ? openItem = null : openItem = '{{ $itemId }}'"
                                        class="w-full px-6 py-4 flex items-center justify-between text-left group"
                                        :aria-expanded="openItem === '{{ $itemId }}'"
                                        aria-controls="answer-{{ $itemId }}">
                                    <span class="font-semibold text-white group-hover:text-indigo-400 transition pr-4">
                                        {{ $item['question'] }}
                                    </span>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform flex-shrink-0"
                                         :class="{ 'rotate-180': openItem === '{{ $itemId }}' }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="openItem === '{{ $itemId }}'"
                                     x-collapse
                                     id="answer-{{ $itemId }}"
                                     class="px-6 pb-4 text-slate-300 leading-relaxed border-t border-white/5">
                                    <div class="pt-4">
                                        {{ $item['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            {{-- Nenhum Resultado --}}
            <div class="text-center py-16">
                <svg class="w-20 h-20 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-white mb-2">Nenhum resultado encontrado</h3>
                <p class="text-slate-400 mb-6">Tente buscar com outras palavras-chave ou remova os filtros.</p>
                <a href="{{ route('client.faq') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Ver Todas as Perguntas
                </a>
             </div>
        @endif
        </div>
        {{-- CTA: Não encontrou? --}}
        <div class="bg-gradient-to-r from-indigo-600/20 to-purple-600/20 border border-indigo-500/30 rounded-2xl p-8 text-center">
            <h3 class="text-2xl font-bold text-white mb-3">Não encontrou o que procurava?</h3>
            <p class="text-slate-300 mb-6 max-w-2xl mx-auto">
                Nossa equipe de suporte está pronta para te ajudar! Abra um chamado e responderemos o mais rápido possível.
            </p>
            <a href="{{ route('client.tickets.create') }}" 
               class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Abrir Novo Chamado
            </a>
        </div>

    </div>
</x-app-layout>
