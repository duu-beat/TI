@props(['id', 'maxWidth', 'show' => false])

@php
$id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth ?? '2xl'];
@endphp

<div
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="relative z-[9999]"
    style="display: none;"
>
    {{-- Overlay (Fundo Preto com Blur) --}}
    <div x-show="show" 
         class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    {{-- Container do Modal --}}
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div x-show="show" 
                 class="relative transform overflow-hidden rounded-2xl bg-slate-900 border border-white/10 text-left shadow-[0_0_50px_-12px_rgba(0,0,0,0.7)] transition-all sm:my-8 sm:w-full {{ $maxWidth }}"
                 x-trap.inert.noscroll="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                {{-- Conteúdo Injetado --}}
                {{ $slot }}

            </div>
        </div>
    </div>
</div>