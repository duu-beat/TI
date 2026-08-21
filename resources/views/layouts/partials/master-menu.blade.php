@php
    $activeClass = 'bg-red-500/10 text-red-300 border border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.12)] font-bold';
    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition-colors border border-transparent';
@endphp

<div class="space-y-1">
    <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-red-400/70">
        Supervisão e risco
    </div>

    <a href="{{ route('master.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('master.dashboard') ? $activeClass : $inactiveClass }}">
       Controle central
    </a>

    <a href="{{ route('master.health') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('master.health') ? $activeClass : $inactiveClass }}">
       Saúde do sistema
    </a>

    <a href="{{ route('master.system-logs') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('master.system-logs') ? $activeClass : $inactiveClass }}">
       Eventos e erros
    </a>

    <div class="my-4 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

    <div class="px-4 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-600">
        Governança
    </div>

    <a href="{{ route('master.audit') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('master.audit') ? $activeClass : $inactiveClass }}">
       Auditoria de ações
    </a>

    <a href="{{ route('master.users.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('master.users.*') ? $activeClass : $inactiveClass }}">
       Identidades e acessos
    </a>

    <a href="{{ route('master.settings') }}"
       class="block rounded-xl px-4 py-2.5 text-sm {{ request()->routeIs('master.settings') ? $activeClass : $inactiveClass }}">
       Controles globais
    </a>
</div>
