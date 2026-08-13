<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Saúde e Diagnóstico da Infraestrutura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Status dos Componentes do Sistema</h3>
                    <p class="text-sm text-gray-500">Monitoramento em tempo real dos serviços críticos da aplicação.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($health as $key => $item)
                        @if(is_array($item))
                            <div class="p-4 rounded-lg border {{ $item['status'] === 'ok' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold uppercase text-xs tracking-wider text-gray-600">{{ $key }}</span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $item['status'] === 'ok' ? 'bg-emerald-200 text-emerald-800' : 'bg-red-200 text-red-800' }}">
                                        {{ strtoupper($item['status']) }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-700">{{ $item['message'] }}</p>
                            </div>
                        @else
                            <div class="p-4 rounded-lg border bg-gray-50 border-gray-200">
                                <span class="font-bold uppercase text-xs tracking-wider text-gray-600">{{ $key }}</span>
                                <p class="mt-2 text-sm text-gray-700 font-mono">{{ $item }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('master.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Voltar ao Painel Master
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
