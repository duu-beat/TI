@props(['ticket', 'color' => 'border-white/20', 'glow' => ''])

<a href="{{ route('admin.tickets.show', $ticket) }}" 
   class="group block bg-slate-800/60 backdrop-blur-sm border-l-[3px] {{ $color }} rounded-r-xl p-4 
          transition-all duration-300 
          hover:-translate-y-1 hover:bg-slate-800/90 hover:shadow-lg {{ $glow }}
          border-y border-r border-white/5">
    
    <div class="flex justify-between items-start mb-2">
        <span class="text-[10px] font-mono font-bold text-slate-500 bg-slate-900/50 px-1.5 py-0.5 rounded border border-white/5">
            #{{ $ticket->id }}
        </span>
        <span class="text-[10px] text-slate-400 flex items-center gap-1">
            {{ $ticket->created_at->format('d/m H:i') }}
        </span>
    </div>

    <h4 class="font-semibold text-slate-200 text-sm mb-2 group-hover:text-white line-clamp-2 leading-relaxed">
        {{ $ticket->subject }}
    </h4>

    <div class="flex items-center gap-2 mt-auto pt-2 border-t border-white/5">
        {{-- Avatar Mini --}}
        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center text-[9px] text-white font-bold shadow-sm ring-1 ring-white/10">
            {{ substr($ticket->user->name, 0, 1) }}
        </div>
        
        <span class="text-xs text-slate-400 truncate max-w-[80px] group-hover:text-slate-300 transition">
            {{ strtok($ticket->user->name, ' ') }}
        </span>
        
        @if($ticket->status === \App\Enums\TicketStatus::WAITING_CLIENT)
            <span class="ml-auto text-[9px] font-bold uppercase tracking-wider bg-yellow-500/10 text-yellow-300 px-1.5 py-0.5 rounded border border-yellow-500/20 shadow-[0_0_8px_rgba(253,224,71,0.15)]">
                Aguardando
            </span>
        @endif
    </div>
</a>