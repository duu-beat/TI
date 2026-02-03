<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-400 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            {{ __('Core do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SKELETON --}}
            <div x-show="!loaded" class="space-y-8 animate-pulse">
                <div class="grid md:grid-cols-2 gap-8">
                    <x-skeleton type="box" height="h-64" class="rounded-2xl" />
                    <x-skeleton type="box" height="h-64" class="rounded-2xl" />
                </div>
                <x-skeleton type="box" height="h-48" class="rounded-2xl" />
            </div>

            <div x-show="loaded" style="display: none;"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                @if(session('success'))
                    <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('master.settings.update') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    {{-- GRID SUPERIOR --}}
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

                    {{-- ✅ NOVO: COMUNICAÇÃO GLOBAL --}}
                    <div class="bg-slate-800 p-8 rounded-2xl border border-white/10 relative overflow-hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-10 w-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                                📢
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Aviso Global</h3>
                                <p class="text-xs text-slate-400">Exibe uma faixa de alerta no topo de todas as páginas.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Mensagem do Banner</label>
                                <textarea name="global_message" rows="2" 
                                    class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-blue-500 focus:ring-blue-500 placeholder-slate-600"
                                    placeholder="Ex: Sistema passará por manutenção às 22h...">{{ $globalMessage ?? '' }}</textarea>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Estilo (Cor)</label>
                                    <select name="global_message_style" class="w-full rounded-xl bg-slate-950 border border-white/10 text-white focus:border-blue-500 focus:ring-blue-500">
                                        <option value="info" {{ ($globalMessageStyle ?? '') == 'info' ? 'selected' : '' }}>🔵 Informação (Azul)</option>
                                        <option value="warning" {{ ($globalMessageStyle ?? '') == 'warning' ? 'selected' : '' }}>🟠 Alerta (Laranja)</option>
                                        <option value="danger" {{ ($globalMessageStyle ?? '') == 'danger' ? 'selected' : '' }}>🔴 Perigo (Vermelho)</option>
                                        <option value="success" {{ ($globalMessageStyle ?? '') == 'success' ? 'selected' : '' }}>🟢 Sucesso (Verde)</option>
                                    </select>
                                </div>
                                <div class="flex items-center pt-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="global_message_active" value="1" class="rounded border-white/10 bg-slate-950 text-blue-600 focus:ring-blue-500"
                                            {{ !empty($globalMessage) ? 'checked' : '' }}>
                                        <span class="text-sm text-white font-bold">Ativar Aviso</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white rounded-xl text-sm font-bold transition shadow-lg shadow-blue-500/20 flex items-center gap-2">
                             Salvar Todas as Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>