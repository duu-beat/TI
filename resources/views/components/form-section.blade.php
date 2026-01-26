@props(['submit'])

<div {{ $attributes->merge(['class' => 'h-full']) }}>
    <form wire:submit="{{ $submit }}" class="h-full flex flex-col bg-slate-900/50 backdrop-blur-xl border border-white/10 rounded-[2rem] shadow-xl relative group">
        
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-white/5 bg-white/5 rounded-t-[2rem] flex flex-col justify-center min-h-[6rem]">
            <h3 class="text-xl font-bold text-white group-hover:text-cyan-400 transition">{{ $title }}</h3>
            <p class="mt-1 text-sm text-slate-400 line-clamp-2">
                {{ $description }}
            </p>
        </div>

        {{-- Body --}}
        <div class="flex-1 px-6 py-6 bg-slate-950/20">
            <div class="grid grid-cols-6 gap-6">
                {{ $form }}
            </div>
        </div>

        {{-- Footer --}}
        @if (isset($actions))
            <div class="px-6 py-4 bg-slate-950/50 border-t border-white/5 flex items-center justify-end rounded-b-[2rem]">
                {{ $actions }}
            </div>
        @endif
    </form>
</div>