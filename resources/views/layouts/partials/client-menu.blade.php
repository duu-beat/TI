@php
    $activeClass = 'bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 text-white border border-white/10 shadow-lg shadow-indigo-500/10 font-medium';
    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition-colors border border-transparent';
@endphp

<div class="space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route('client.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('client.dashboard') ? $activeClass : $inactiveClass }}">
       🏠 Início
    </a>

    {{-- Chamados (Ativo em: index, create, show) --}}
    <a href="{{ route('client.tickets.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('client.tickets.*') ? $activeClass : $inactiveClass }}">
        🎫 Meus Chamados
    </a>

    {{-- Perfil --}}
    <a href="{{ route('profile.show') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('profile.show') ? $activeClass : $inactiveClass }}">
        👤 Minha Conta
    </a>
</div>