@props(['attachment', 'compact' => false])

@php
    $url = $attachment->url;
    $name = $attachment->name;
@endphp

<article class="overflow-hidden rounded-xl border border-white/10 bg-slate-950/45 {{ $compact ? 'w-40' : 'w-full sm:w-56' }}">
    @if ($attachment->isImage())
        <a href="{{ $url }}" target="_blank" rel="noopener" class="block bg-slate-900" title="Abrir {{ $name }} em tamanho maior">
            <img src="{{ $url }}" alt="Prévia de {{ $name }}" class="h-28 w-full object-cover transition duration-200 hover:scale-[1.03] {{ $compact ? 'h-24' : '' }}">
        </a>
    @elseif ($attachment->isPdf())
        <iframe src="{{ $url }}#toolbar=0&navpanes=0" title="Prévia do PDF {{ $name }}" class="h-28 w-full bg-white {{ $compact ? 'h-24' : '' }}" loading="lazy"></iframe>
    @else
        <div class="flex h-28 items-center justify-center bg-slate-900/80 text-4xl {{ $compact ? 'h-24' : '' }}">{{ $attachment->icon }}</div>
    @endif

    <div class="flex items-start gap-2 p-3">
        <div class="min-w-0 flex-1">
            <p class="truncate text-xs font-semibold text-slate-200" title="{{ $name }}">{{ $name }}</p>
            <p class="mt-1 text-[10px] uppercase tracking-wide text-slate-500">
                {{ $attachment->isImage() ? 'Imagem' : ($attachment->isPdf() ? 'PDF' : 'Arquivo') }} · {{ $attachment->formatted_size }}
            </p>
        </div>
        <a href="{{ $url }}" target="_blank" rel="noopener" class="rounded-lg p-1.5 text-cyan-300 hover:bg-cyan-400/10 hover:text-cyan-200 transition" title="Abrir anexo">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" /></svg>
            <span class="sr-only">Abrir {{ $name }}</span>
        </a>
    </div>
</article>
