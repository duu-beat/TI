@php
    // Estilo ligeiramente diferente para Admin
    $activeClass = 'bg-gradient-to-r from-indigo-500/10 to-cyan-500/10 text-white border border-white/10 shadow-lg shadow-indigo-500/10 font-medium';
    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition-colors border border-transparent';
@endphp

<div class="space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
       📊 Dashboard
    </a>

    {{-- Chamados (Lista) --}}
    {{-- Ajustei o routeIs para não marcar como ativo quando estiver no Kanban --}}
    <a href="{{ route('admin.tickets.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.tickets.index', 'admin.tickets.show') ? $activeClass : $inactiveClass }}">
        🎫 Gerenciar Chamados
    </a>

    {{-- Kanban (Novo) --}}
    <a href="{{ route('admin.tickets.kanban') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.tickets.kanban') ? $activeClass : $inactiveClass }}">
        📋 Quadro Kanban
    </a>

    {{-- Respostas Prontas --}}
    <a href="{{ route('admin.respostas-prontas.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.respostas-prontas.*') ? $activeClass : $inactiveClass }}">
        ⚡ Respostas Prontas
    </a>

    {{-- Tags --}}
    <a href="{{ route('admin.tags.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.tags.*') ? $activeClass : $inactiveClass }}">
        🏷️ Tags
    </a>

    {{-- Relatórios --}}
    <a href="{{ route('admin.reports.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.reports.*') ? $activeClass : $inactiveClass }}">
        📊 Relatórios
    </a>
    
    {{-- Perfil --}}
    <a href="{{ route('admin.profile') }}"
        class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('admin.profile') ? $activeClass : $inactiveClass }}">
        👤 Meu Perfil
    </a>
</div>