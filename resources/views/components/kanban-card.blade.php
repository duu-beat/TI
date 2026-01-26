@props(['ticket', 'color' => 'border-white/10'])

<a href="{{ route('admin.tickets.show', $ticket) }}" 
   class="block bg-white/5 hover:bg-white/10 border-l-4 {{ $color }} rounded-r-xl p-4 transition group shadow-lg">
    
    <div class="flex justify-between items-start mb-2">
        <span class="text-xs font-mono text-slate-500">#{{ $ticket->id }}</span>
        <span class="text-[10px] text-slate-400">{{ $ticket->created_at->format('d/m H:i') }}</span>
    </div>

    <h4 class="font-semibold text-slate-200 text-sm mb-1 group-hover:text-white line-clamp-2">
        {{ $ticket->subject }}
    </h4>

    <div class="flex items-center gap-2 mt-3">
        <div class="h-5 w-5 rounded-full bg-slate-700 flex items-center justify-center text-[10px] text-white font-bold">
            {{ substr($ticket->user->name, 0, 1) }}
        </div>
        <span class="text-xs text-slate-400 truncate max-w-[100px]">{{ $ticket->user->name }}</span>
        
        @if($ticket->status === \App\Enums\TicketStatus::WAITING_CLIENT)
            <span class="ml-auto text-[10px] bg-yellow-500/20 text-yellow-300 px-1.5 py-0.5 rounded">Aguardando</span>
        @endif
    </div>
</a>