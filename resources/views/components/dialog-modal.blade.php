@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4 bg-slate-900">
        {{-- MUDANÇA: Título Branco --}}
        <div class="text-lg font-bold text-white">
            {{ $title }}
        </div>

        {{-- MUDANÇA: Conteúdo Cinza Claro --}}
        <div class="mt-4 text-sm text-slate-400">
            {{ $content }}
        </div>
    </div>

    {{-- MUDANÇA: Rodapé Escuro --}}
    <div class="flex flex-row justify-end px-6 py-4 bg-slate-950/30 border-t border-white/5 text-end">
        {{ $footer }}
    </div>
</x-modal>