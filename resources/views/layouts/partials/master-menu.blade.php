@php
    $activeClass = 'bg-red-500/10 text-red-400 border border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.15)] font-bold';
    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white transition-colors border border-transparent';
@endphp

<div class="space-y-1">
    
    <div class="px-4 py-2 text-[10px] font-bold text-red-500/70 uppercase tracking-widest">
        Nível de Segurança
    </div>

    {{-- Dashboard --}}
    <a href="{{ route('master.dashboard') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.dashboard') ? $activeClass : $inactiveClass }}">
       🛡️ Controle Central
    </a>


    {{-- NOVO: Logs de Auditoria --}}
    <a href="{{ route('master.audit') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.audit') ? $activeClass : $inactiveClass }}">
       👁️ Logs de Auditoria
    </a>

    {{-- NOVO: Configurações do Sistema --}}
    <a href="{{ route('master.settings') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.settings') ? $activeClass : $inactiveClass }}">
       ⚙️ Core do Sistema
    </a>

    {{-- ✅ NOVO: Logs do Sistema (Erros de Código) --}}
    <a href="{{ route('master.system-logs') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.system-logs') ? $activeClass : $inactiveClass }}">
       ⚠️ Erros do Sistema
    </a>

    {{-- ✅ NOVO: Usuários --}}
    <a href="{{ route('master.users.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.users.*') ? $activeClass : $inactiveClass }}">
       👥 Gerenciar Usuários
    </a>

    {{-- Tags --}}
    <a href="{{ route('admin.tags.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('admin.tags.*') ? $activeClass : $inactiveClass }}">
       🏷️ Tags
    </a>

    {{-- Relatórios --}}
    <a href="{{ route('admin.reports.index') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('admin.reports.*') ? $activeClass : $inactiveClass }}">
       📊 Relatórios
    </a>

    {{-- Perfil --}}
    <a href="{{ route('master.profile') }}"
       class="block rounded-xl px-4 py-2.5 text-sm mb-1 {{ request()->routeIs('master.profile') ? $activeClass : $inactiveClass }}">
       👤 Identidade
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