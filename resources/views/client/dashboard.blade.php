<x-app-layout>
    <x-slot name="header">
        Visão Geral
    </x-slot>

    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
        
        {{-- Skeleton Loader --}}
        <div x-show="!loaded" class="space-y-6 animate-pulse">
            <div class="h-32 bg-white/5 rounded-2xl w-full"></div>
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="h-24 bg-white/5 rounded-2xl"></div>
                <div class="h-24 bg-white/5 rounded-2xl"></div>
                <div class="h-24 bg-white/5 rounded-2xl"></div>
            </div>
        </div>

        <div x-show="loaded" style="display: none;" class="space-y-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- 🔔 ALERTA DE AÇÃO --}}
            @if(isset($waitingForUser) && $waitingForUser > 0)
                <div class="rounded-2xl bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-yellow-500/20 flex items-center justify-center text-2xl text-yellow-400">👋</div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Aguardando sua resposta</h3>
                            <p class="text-yellow-200/80 text-sm">O suporte respondeu a <strong>{{ $waitingForUser }}</strong> chamado(s). Por favor, verifique.</p>
                        </div>
                    </div>
                    <a href="{{ route('client.tickets.index', ['status' => 'waiting_client']) }}" 
                       class="whitespace-nowrap px-6 py-2.5 rounded-xl bg-yellow-500 text-slate-900 font-bold hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/20">
                        Responder Agora
                    </a>
                </div>
            @else
                {{-- Banner Padrão --}}
                <div class="rounded-2xl bg-white/5 border border-white/10 p-8 relative overflow-hidden">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-500/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold text-white mb-2">Olá, {{ Auth::user()->name }}!</h2>
                        <p class="text-slate-400 mb-6 max-w-lg">Bem-vindo à sua central de suporte. Acompanhe seus chamados ou inicie um novo atendimento.</p>
                        <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-bold hover:brightness-110 transition shadow-lg shadow-indigo-500/25">
                            <span>+</span> Novo Chamado
                        </a>
                    </div>
                </div>
            @endif

            {{-- 📊 STATUS RÁPIDO --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5 hover:border-white/10 transition">
                    <div class="text-xs text-slate-500 uppercase font-bold mb-1">Em Aberto</div>
                    <div class="text-2xl font-bold text-white">{{ $stats['open'] }}</div>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5 hover:border-cyan-500/30 transition">
                    <div class="text-xs text-cyan-500 uppercase font-bold mb-1">Em Atendimento</div>
                    <div class="text-2xl font-bold text-cyan-400">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900 border border-white/5 hover:border-emerald-500/30 transition">
                    <div class="text-xs text-emerald-500 uppercase font-bold mb-1">Finalizados</div>
                    <div class="text-2xl font-bold text-emerald-400">{{ $stats['resolved'] }}</div>
                </div>
                <a href="{{ route('faq') }}" class="p-4 rounded-2xl bg-slate-900 border border-white/5 hover:bg-white/5 transition flex flex-col justify-center group cursor-pointer">
                    <div class="text-xs text-indigo-400 uppercase font-bold mb-1 group-hover:text-white transition">Dúvidas?</div>
                    <div class="text-sm text-slate-300 group-hover:text-white">Acesse a FAQ &rarr;</div>
                </a>
            </div>

            {{-- LISTA DE RECENTES & LINKS --}}
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- Últimos Chamados --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Atividades Recentes</h3>
                        <a href="{{ route('client.tickets.index') }}" class="text-xs text-slate-400 hover:text-white">Ver histórico completo</a>
                    </div>

                    @forelse($recentTickets as $ticket)
                        <a href="{{ route('client.tickets.show', $ticket) }}" class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition group">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-lg bg-slate-800 border border-white/5 group-hover:border-white/20 transition">
                                    {{ $ticket->status === \App\Enums\TicketStatus::RESOLVED ? '✅' : '🎫' }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white group-hover:text-cyan-400 transition">{{ $ticket->subject }}</div>
                                    <div class="text-xs text-slate-500">Atualizado {{ $ticket->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            
                            {{-- ✅ AQUI ESTÁ A MUDANÇA --}}
                            <x-ticket-status :status="$ticket->status" />
                            
                        </a>
                    @empty
                        <div class="text-center py-10 rounded-2xl border border-dashed border-white/10">
                            <p class="text-slate-500 text-sm">Você ainda não tem chamados recentes.</p>
                        </div>
                    @endforelse
                </div>

                {{-- FAQs Rápidas --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-white">Ajuda Rápida</h3>
                    <div class="p-5 rounded-2xl bg-slate-900 border border-white/10">
                        <ul class="space-y-3">
                            @forelse($faqs as $faq)
                                <li>
                                    <a href="{{ route('faq') }}#faq-{{ $faq->id }}" class="flex items-start gap-2 text-sm text-slate-400 hover:text-cyan-400 transition">
                                        <span class="text-cyan-500 mt-0.5">•</span>
                                        {{ $faq->question }}
                                    </a>
                                </li>
                            @empty
                                <li class="text-xs text-slate-500">Nenhuma pergunta frequente cadastrada.</li>
                            @endforelse
                        </ul>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <a href="{{ route('faq') }}" class="block w-full text-center py-2 rounded-xl bg-white/5 text-xs font-bold text-white hover:bg-white/10 transition">
                                Ver Central de Ajuda
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>