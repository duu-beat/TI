<div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div>
        {{ $logo }}
    </div>

    {{-- Cartão com efeito Glassmorphism --}}
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-slate-900/60 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-3xl">
        {{ $slot }}
    </div>
</div>