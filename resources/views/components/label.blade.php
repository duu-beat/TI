@props(['value' => null])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-slate-300']) }}>
    {{ $value ?? $slot }}
</label>
