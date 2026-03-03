<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesquisa de Satisfação') }} - Chamado #{{ $ticket->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Seu chamado foi concluído!</h3>
                    <p class="text-gray-600 mt-2">Como você avalia o atendimento recebido para o chamado: <br><strong>{{ $ticket->subject }}</strong></p>
                </div>

                <form action="{{ route('client.tickets.nps.store', $ticket) }}" method="POST" x-data="{ score: null }">
                    @csrf
                    
                    <div class="mb-10">
                        <p class="text-sm font-medium text-gray-700 mb-4">Em uma escala de 0 a 10, o quanto você recomendaria nosso suporte?</p>
                        
                        <div class="flex flex-wrap justify-center gap-2">
                            <template x-for="n in [0,1,2,3,4,5,6,7,8,9,10]" :key="n">
                                <label class="cursor-pointer">
                                    <input type="radio" name="score" :value="n" x-model="score" class="hidden" required>
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-lg border-2 transition-all duration-200 font-bold text-lg"
                                         :class="{
                                            'bg-indigo-600 border-indigo-600 text-white shadow-lg scale-110': score == n,
                                            'bg-white border-gray-200 text-gray-600 hover:border-indigo-300 hover:bg-indigo-50': score != n,
                                            'border-red-200 text-red-600': n <= 6 && score != n,
                                            'border-yellow-200 text-yellow-600': (n == 7 || n == 8) && score != n,
                                            'border-green-200 text-green-600': n >= 9 && score != n
                                         }"
                                         x-text="n">
                                    </div>
                                </label>
                            </template>
                        </div>
                        
                        <div class="flex justify-between mt-4 px-2 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            <span>Nada Provável</span>
                            <span>Muito Provável</span>
                        </div>
                        <x-input-error for="score" class="mt-2" />
                    </div>

                    <div class="mb-8 text-left">
                        <x-label for="comment" value="Conte-nos um pouco mais (opcional)" />
                        <textarea id="comment" name="comment" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" placeholder="O que podemos fazer para melhorar ainda mais?"></textarea>
                        <x-input-error for="comment" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <a href="{{ route('client.tickets.show', $ticket) }}" class="text-gray-500 hover:text-gray-700 font-medium">Pular agora</a>
                        <x-button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition transform hover:scale-105 active:scale-95" ::disabled="score === null">
                            Enviar Avaliação
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
