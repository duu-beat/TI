@props(['status'])

@php
    $styles = match($status->value) {
        'new' => [
            'class' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            'icon' => '🆕'
        ],
        'in_progress' => [
            'class' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
            'icon' => '⚡'
        ],
        'waiting_client' => [
            'class' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            'icon' => '⏳'
        ],
        'resolved' => [
            'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'icon' => '✅'
        ],
        'closed' => [
            'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
            'icon' => '🔒'
        ],
        default => [
            'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
            'icon' => '❓'
        ],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold border {{ $styles['class'] }}">
    <span class="text-[10px]">{{ $styles['icon'] }}</span>
    {{ $status->label() }}
</span>