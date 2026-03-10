<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('client.tickets.show', $ticket) }}" 
               class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                     Pesquisa de Satisfação
                </h2>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">Chamado #{{ $ticket->id }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen flex items-start justify-center">
        <div class="max-w-3xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl p-8 sm:p-12 text-center">
                {{-- Background Decorativo --}}
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-500/20 text-indigo-400 rounded-2xl mb-6 border border-indigo-500/30 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    
                    <h3 class="text-3xl font-black text-white mb-2">Chamado Concluído!</h3>
                    <p class="text-slate-400 text-lg mb-10">
                        Como você avalia o atendimento para: <br>
                        <span class="text-indigo-400 font-bold">"{{ $ticket->subject }}"</span>
                    </p>

                    <form action="{{ route('client.tickets.nps.store', $ticket) }}" method="POST" x-data="{ score: null }">
                        @csrf
                        
                        <div class="mb-12">
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-6">Em uma escala de 0 a 10, o quanto você recomendaria nosso suporte?</p>
                            
                            <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                                <template x-for="n in [0,1,2,3,4,5,6,7,8,9,10]" :key="n">
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="score" :value="n" x-model="score" class="hidden" required>
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-xl border-2 transition-all duration-300 font-black text-lg shadow-lg"
                                             :class="{
                                                'bg-indigo-600 border-indigo-400 text-white scale-110 shadow-indigo-500/40': score == n,
                                                'bg-slate-950/50 border-white/5 text-slate-500 hover:border-indigo-500/50 hover:text-indigo-400': score != n,
                                                'hover:bg-red-500/10': n <= 6 && score != n,
                                                'hover:bg-yellow-500/10': (n == 7 || n == 8) && score != n,
                                                'hover:bg-emerald-500/10': n >= 9 && score != n
                                             }"
                                             x-text="n">
                                        </div>
                                    </label>
                                </template>
                            </div>
                            
                            <div class="flex justify-between mt-6 px-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-600">
                                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500/50"></span> Nada Provável</span>
                                <span class="flex items-center gap-2">Muito Provável <span class="w-2 h-2 rounded-full bg-emerald-500/50"></span></span>
                            </div>
                            <x-input-error for="score" class="mt-2" />
                        </div>

                        <div class="mb-10 text-left">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 block">Conte-nos um pouco mais (opcional)</label>
                            <textarea id="comment" name="comment" rows="4" 
                                      class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-4 px-5 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700"
                                      placeholder="O que podemos fazer para melhorar ainda mais seu atendimento?"></textarea>
                            <x-input-error for="comment" class="mt-2" />
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <a href="{{ route('client.tickets.show', $ticket) }}" class="text-slate-500 hover:text-white font-bold text-sm uppercase tracking-widest transition">Pular agora</a>
                            <button type="submit" 
                                    ::disabled="score === null"
                                    class="w-full sm:w-auto px-12 py-4 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 transition transform hover:-translate-y-1 active:translate-y-0">
                                Enviar Avaliação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
