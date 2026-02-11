{{-- Skeleton para lista de tickets --}}
<div class="animate-pulse space-y-4">
    @for($i = 0; $i < 5; $i++)
        <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-5">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-1 space-y-3">
                    {{-- ID e categoria --}}
                    <div class="flex items-center gap-2">
                        <div class="h-5 w-16 bg-slate-700/50 rounded"></div>
                        <div class="h-4 w-24 bg-slate-700/50 rounded"></div>
                    </div>
                    
                    {{-- Título --}}
                    <div class="h-5 bg-slate-700/50 rounded w-3/4"></div>
                    
                    {{-- Descrição --}}
                    <div class="space-y-2">
                        <div class="h-3 bg-slate-700/50 rounded w-full"></div>
                        <div class="h-3 bg-slate-700/50 rounded w-2/3"></div>
                    </div>
                </div>
                
                {{-- Status badge --}}
                <div class="h-6 w-20 bg-slate-700/50 rounded-full"></div>
            </div>
            
            {{-- Footer --}}
            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 bg-slate-700/50 rounded-full"></div>
                    <div class="h-4 w-32 bg-slate-700/50 rounded"></div>
                </div>
                <div class="h-4 w-24 bg-slate-700/50 rounded"></div>
            </div>
        </div>
    @endfor
</div>
