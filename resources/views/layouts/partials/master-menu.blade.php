@php
    $activeClass = 'bg-red-500/10 text-red-400 border border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.15)] font-bold';
    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition-colors border border-transparent';
@endphp

<div class="space-y-1">
    <div class="px-4 py-2 text-[10px] font-bold text-red-500/70 uppercase tracking-widest">
        Nível de Segurança
    </div>

    <a href="{{ route('master.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.dashboard') ? $activeClass : $inactiveClass }}">
       🛡️ Controle Central
    </a>

    <a href="{{ route('master.audit') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.audit') ? $activeClass : $inactiveClass }}">
       👁️ Logs de Auditoria
    </a>

    <a href="{{ route('master.system-logs') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.system-logs') ? $activeClass : $inactiveClass }}">
       ⚠️ Erros do Sistema
    </a>

    <a href="{{ route('master.health') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.health') ? $activeClass : $inactiveClass }}">
       🩺 Saúde do Sistema
    </a>

    <a href="{{ route('master.settings') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.settings') ? $activeClass : $inactiveClass }}">
       ⚙️ Core do Sistema
    </a>

    <a href="{{ route('master.users.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.users.*') ? $activeClass : $inactiveClass }}">
       👥 Gerenciar Usuários
    </a>

    <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent my-4"></div>

    <div class="px-4 pb-2 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
        Acesso Subordinado
    </div>

    <a href="{{ route('admin.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm text-slate-500 hover:text-cyan-400 hover:bg-white/5 transition flex items-center gap-2 group">
        <span class="group-hover:translate-x-1 transition-transform">↳</span>
        Painel Admin
    </a>
</div>
