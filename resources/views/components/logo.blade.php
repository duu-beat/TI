@props([
    'size' => 'md', // sm | md | lg
    'withText' => true
])

@php
    $sizes = [
        'sm' => 'h-8',
        'md' => 'h-10',
        'lg' => 'h-14',
    ];
@endphp

<div class="flex items-center gap-3">
    <img
        src="{{ asset('images/logosuporteTI.png') }}"
        alt="Suporte TI"
        class="{{ $sizes[$size] ?? 'h-10' }} w-auto object-contain"
    >

    @if($withText)
        <div class="leading-tight">
            <div class="font-bold tracking-tight text-white leading-none">
                Suporte TI
            </div>
            <div class="text-xs text-slate-300 leading-snug">
                Soluções rápidas e seguras
            </div>
        </div>
    @endif
</div>
