@props(['class' => ''])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-red-400/25 bg-red-500/10 p-4 text-red-100 ' . $class]) }} role="alert" aria-labelledby="validation-errors-title">
        <div class="flex items-start gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-400/15 text-sm font-black text-red-200">!</span>
            <div>
                <h3 id="validation-errors-title" class="text-sm font-bold">Revise os campos destacados</h3>
                <p class="mt-1 text-sm text-red-100/80">Algumas informações precisam ser corrigidas antes de continuar.</p>
                <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-red-100/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
