{{-- Skeleton para visualização de ticket --}}
<div class="animate-pulse">
    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Coluna principal --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Header do ticket --}}
            <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-20 bg-slate-700/50 rounded"></div>
                            <div class="h-5 w-32 bg-slate-700/50 rounded"></div>
                        </div>
                        <div class="h-7 bg-slate-700/50 rounded w-3/4"></div>
                    </div>
                    <div class="h-7 w-24 bg-slate-700/50 rounded-full"></div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/5">
                    <div class="space-y-2">
                        <div class="h-3 w-20 bg-slate-700/50 rounded"></div>
                        <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-3 w-20 bg-slate-700/50 rounded"></div>
                        <div class="h-4 w-28 bg-slate-700/50 rounded"></div>
                    </div>
                </div>
            </div>
            
            {{-- Mensagens --}}
            <div class="space-y-4">
                @for($i = 0; $i < 3; $i++)
                    <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-5">
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 bg-slate-700/50 rounded-full shrink-0"></div>
                            <div class="flex-1 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                                    <div class="h-3 w-24 bg-slate-700/50 rounded"></div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-3 bg-slate-700/50 rounded w-full"></div>
                                    <div class="h-3 bg-slate-700/50 rounded w-5/6"></div>
                                    <div class="h-3 bg-slate-700/50 rounded w-4/6"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        
        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Card de informações --}}
            <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-5">
                <div class="h-5 w-32 bg-slate-700/50 rounded mb-4"></div>
                <div class="space-y-4">
                    @for($i = 0; $i < 4; $i++)
                        <div class="space-y-2">
                            <div class="h-3 w-24 bg-slate-700/50 rounded"></div>
                            <div class="h-8 bg-slate-700/50 rounded"></div>
                        </div>
                    @endfor
                </div>
            </div>
            
            {{-- Outro card --}}
            <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-5">
                <div class="h-5 w-28 bg-slate-700/50 rounded mb-4"></div>
                <div class="space-y-3">
                    @for($i = 0; $i < 3; $i++)
                        <div class="h-16 bg-slate-700/50 rounded-xl"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
