<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            {{ __('Core do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('master.settings.update') }}" method="POST">
                @csrf
                <div class="grid md:grid-cols-2 gap-8">
                    
                    {{-- Modo Manutenção --}}
                    <div class="bg-slate-800 p-8 rounded-2xl border border-white/10 relative overflow-hidden group hover:border-red-500/30 transition duration-300">
                        <div class="absolute -right-10 -top-10 bg-red-500/10 w-40 h-40 rounded-full blur-3xl group-hover:bg-red-500/20 transition"></div>
                        <h3 class="text-lg font-bold text-white mb-2">Modo de Manutenção</h3>
                        <p class="text-slate-400 text-sm mb-6 h-10">O site ficará inacessível (Erro 503). Apenas você terá acesso.</p>
                        
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" 
                                {{ app()->isDownForMaintenance() ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-950 peer-focus:outline-none rounded-full border border-white/10 peer-checked:bg-red-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            <span class="ml-3 text-xs font-bold uppercase tracking-wider text-slate-300">
                                {{ app()->isDownForMaintenance() ? 'ATIVADO' : 'DESATIVADO' }}
                            </span>
                        </label>
                    </div>

                    {{-- Bloqueio de Cadastros --}}
                    <div class="bg-slate-800 p-8 rounded-2xl border border-white/10 relative overflow-hidden group hover:border-orange-500/30 transition duration-300">
                        <div class="absolute -right-10 -top-10 bg-orange-500/10 w-40 h-40 rounded-full blur-3xl group-hover:bg-orange-500/20 transition"></div>
                        <h3 class="text-lg font-bold text-white mb-2">Bloqueio de Cadastros</h3>
                        <p class="text-slate-400 text-sm mb-6 h-10">Impede novos registros de usuários. Útil durante ataques.</p>
                        
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="block_registers" value="1" class="sr-only peer"
                                {{ $registrationBlocked ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-950 peer-focus:outline-none rounded-full border border-white/10 peer-checked:bg-orange-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            <span class="ml-3 text-xs font-bold uppercase tracking-wider text-slate-300">
                                {{ $registrationBlocked ? 'BLOQUEADO' : 'LIBERADO' }}
                            </span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl text-sm font-bold transition border border-white/10">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>