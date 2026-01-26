@props([
    'type' => 'text', // text, avatar, card, box
    'lines' => 1,
    'width' => 'w-full',
    'height' => 'h-4',
    'class' => ''
])

<div class="animate-pulse {{ $class }}">
    
    {{-- TIPO: TEXTO (Linhas) --}}
    @if($type === 'text')
        <div class="space-y-2">
            {{-- CORREÇÃO: Usamos foreach(range...) para ter acesso à variável $loop --}}
            @foreach(range(1, $lines) as $line)
                <div class="{{ $height }} {{ $loop->last && $lines > 1 ? 'w-2/3' : $width }} bg-slate-700/50 rounded"></div>
            @endforeach
        </div>

    {{-- TIPO: AVATAR (Círculo) --}}
    @elseif($type === 'avatar')
        <div class="h-10 w-10 bg-slate-700/50 rounded-full"></div>

    {{-- TIPO: CARD COMPLETO (Foto + Texto) --}}
    @elseif($type === 'card')
        <div class="rounded-2xl border border-white/5 p-4 space-y-3 bg-white/5">
            <div class="h-32 bg-slate-700/30 rounded-xl w-full"></div> {{-- Imagem --}}
            <div class="h-4 bg-slate-700/50 rounded w-3/4"></div> {{-- Título --}}
            <div class="h-3 bg-slate-700/50 rounded w-1/2"></div> {{-- Subtítulo --}}
        </div>

    {{-- TIPO: BOX SIMPLES --}}
    @else
        <div class="{{ $width }} {{ $height }} bg-slate-700/50 rounded-xl"></div>
    @endif

</div>