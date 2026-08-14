@php
    $flashMessages = collect([
        ['key' => 'success', 'title' => 'Operação concluída', 'icon' => '✓', 'classes' => 'border-emerald-400/25 bg-emerald-500/10 text-emerald-100', 'iconClasses' => 'bg-emerald-400/15 text-emerald-300'],
        ['key' => 'error', 'title' => 'Não foi possível concluir', 'icon' => '!', 'classes' => 'border-red-400/25 bg-red-500/10 text-red-100', 'iconClasses' => 'bg-red-400/15 text-red-200'],
        ['key' => 'warning', 'title' => 'Atenção necessária', 'icon' => '!', 'classes' => 'border-amber-400/25 bg-amber-500/10 text-amber-100', 'iconClasses' => 'bg-amber-400/15 text-amber-200'],
        ['key' => 'info', 'title' => 'Informação', 'icon' => 'i', 'classes' => 'border-cyan-400/25 bg-cyan-500/10 text-cyan-100', 'iconClasses' => 'bg-cyan-400/15 text-cyan-200'],
        ['key' => 'status', 'title' => 'Atualização', 'icon' => 'i', 'classes' => 'border-indigo-400/25 bg-indigo-500/10 text-indigo-100', 'iconClasses' => 'bg-indigo-400/15 text-indigo-200'],
    ])->filter(fn (array $message) => session()->has($message['key']))->values();
@endphp

@if ($flashMessages->isNotEmpty())
    <div class="fixed bottom-5 right-5 z-[100] w-[calc(100%-2.5rem)] max-w-md space-y-3" aria-live="polite" aria-atomic="true">
        @foreach ($flashMessages as $message)
            <div x-data="{ open: true }" x-init="setTimeout(() => open = false, 5500)" x-show="open" x-transition role="status" class="flex items-start gap-3 rounded-2xl border p-4 shadow-2xl backdrop-blur-xl {{ $message['classes'] }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-black {{ $message['iconClasses'] }}">{{ $message['icon'] }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold">{{ $message['title'] }}</p>
                    <p class="mt-0.5 text-sm opacity-85">{{ session($message['key']) }}</p>
                </div>
                <button type="button" @click="open = false" class="rounded-lg p-1 text-current opacity-60 hover:bg-white/10 hover:opacity-100" aria-label="Fechar aviso">×</button>
            </div>
        @endforeach
    </div>
@endif
