@php
    // Estilo ligeiramente diferente para Admin (Vermelho/Laranja se quiseres, ou manter o padrão)
    $activeClass = 'bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 text-white border border-white/10 shadow-lg shadow-indigo-500/10 font-medium';
    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition-colors border border-transparent';
@endphp

<div class="space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
       📊 Dashboard
    </a>

    {{-- Chamados --}}
    <a href="{{ route('admin.tickets.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.tickets.*') ? $activeClass : $inactiveClass }}">
        🎫 Gerenciar Chamados
    </a>

    
    {{-- Perfil --}}
    <a href="{{ route('admin.profile') }}"
        class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.profile') ? $activeClass : $inactiveClass }}">
        👤 Meu Perfil
    </a>
</div>