@php
    $user = auth()->user();
    
    // Verifica os níveis
    $isMaster = $user?->isMaster() ?? false;
    $isAdmin = $user?->isAdmin() ?? false;
    
    // Define Badge, Cor e Rota de Perfil
    if ($isMaster) {
        $badge = 'Segurança';
        $badgeClass = 'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.2)] font-bold';
        $profileRoute = route('master.profile'); // Rota dedicada
    } elseif ($isAdmin) {
        $badge = 'Admin';
        $badgeClass = 'bg-cyan-500/10 text-cyan-200 border-cyan-500/20';
        $profileRoute = route('admin.profile');
    } else {
        $badge = 'Cliente';
        $badgeClass = 'bg-slate-700/50 text-slate-400 border-white/5';
        $profileRoute = route('client.profile');
    }
@endphp

<aside class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950/80 backdrop-blur-xl border-r border-white/10 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    {{-- Logo --}}
    <div class="p-6 border-b border-white/10 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-4 group">
            <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI" 
                 class="h-12 w-12 rounded-xl object-contain shrink-0 group-hover:scale-105 transition" />
            <div class="flex-1">
                <div class="font-bold text-white leading-tight tracking-tight">Suporte TI</div>
                <div class="mt-1 inline-flex items-center">
                    <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded border {{ $badgeClass }}">
                        {{ $badge }}
                    </span>
                </div>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Menu Dinâmico --}}
    <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">
        @if($isMaster)
            @include('layouts.partials.master-menu')
        @elseif($isAdmin)
            @include('layouts.partials.admin-menu')
        @else
            @include('layouts.partials.client-menu')
        @endif
    </nav>

    {{-- Footer Sidebar --}}
    <div class="p-4 border-t border-white/10 bg-white/5">
        <div class="flex items-center justify-between gap-3">
            
            {{-- ✅ CORREÇÃO: Link para o perfil correto --}}
            <a href="{{ $profileRoute }}" class="overflow-hidden group cursor-pointer block flex-1">
                <div class="text-sm text-slate-200 font-semibold truncate group-hover:text-white transition">{{ $user->name }}</div>
                <div class="text-xs text-slate-500 truncate group-hover:text-slate-400 transition">{{ $user->email }}</div>
            </a>
            
            <button type="button" 
                    @click="logoutModalOpen = true"
                    class="rounded-xl bg-white/10 p-2 text-slate-200 hover:bg-white/20 hover:text-red-400 transition" 
                    title="Sair">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </div>
    </div>
</aside>