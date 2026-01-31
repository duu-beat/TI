<x-action-section>
    <x-slot name="title">{{ __('Sessões Ativas') }}</x-slot>
    <x-slot name="description">{{ __('Gerencie e saia das suas sessões ativas em outros navegadores e dispositivos.') }}</x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-slate-400 mb-6">
            {{ __('Se necessário, você pode sair de todas as outras sessões do navegador em todos os seus dispositivos. Abaixo estão suas sessões recentes.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="space-y-4 mt-5">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center justify-between p-4 rounded-2xl border transition-all duration-300 {{ $session->is_current_device ? 'bg-cyan-950/20 border-cyan-500/30 shadow-[0_0_15px_-5px_rgba(6,182,212,0.3)]' : 'bg-slate-950 border-white/5 hover:border-white/20' }}">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl {{ $session->is_current_device ? 'bg-cyan-500/20 text-cyan-400' : 'bg-white/5 text-slate-500' }}">
                                @if ($session->agent->isDesktop()) 🖥️ @else 📱 @endif
                            </div>

                            <div>
                                <div class="text-sm font-bold {{ $session->is_current_device ? 'text-cyan-400' : 'text-white' }}">
                                    {{ $session->agent->platform() ? $session->agent->platform() : __('Desconhecido') }} 
                                    - {{ $session->agent->browser() ? $session->agent->browser() : __('Desconhecido') }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $session->ip_address }},
                                    @if ($session->is_current_device)
                                        <span class="text-emerald-400 font-bold uppercase tracking-wide text-[10px] ml-1">{{ __('Este dispositivo') }}</span>
                                    @else
                                        {{ __('Última atividade') }} {{ $session->last_active }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-6">
            <button wire:click="confirmLogout" wire:loading.attr="disabled" class="flex items-center gap-2 px-5 py-3 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-xl border border-white/10 hover:border-white/30 transition shadow-lg">
                <span>🚪</span>
                {{ __('Sair de Outras Sessões') }}
            </button>
            <x-action-message class="ml-3 text-emerald-400" on="loggedOut">{{ __('Feito.') }}</x-action-message>
        </div>

        <x-custom-modal wire:model.live="confirmingLogout">
            <div class="p-6 bg-slate-900">
                <h3 class="text-lg font-bold text-white mb-4">
                    {{ __('Confirmar Saída Global') }}
                </h3>

                <p class="text-sm text-slate-400 mb-4">
                    {{ __('Por favor, digite sua senha para confirmar que deseja sair das suas outras sessões em todos os dispositivos.') }}
                </p>

                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" 
                           class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition outline-none" 
                           placeholder="{{ __('Senha') }}" 
                           x-ref="password" 
                           wire:model="password" 
                           wire:keydown.enter="logoutOtherBrowserSessions" />

                    <x-input-error for="password" class="mt-2 text-red-400" />
                </div>
            </div>

            <div class="bg-slate-950 px-6 py-4 flex flex-row-reverse gap-3 border-t border-white/5">
                <button wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold rounded-lg shadow-[0_0_15px_rgba(8,145,178,0.5)] transition">
                    {{ __('Confirmar Saída') }}
                </button>
                
                <button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled" class="px-5 py-2 bg-transparent hover:bg-white/5 text-slate-300 text-sm font-bold rounded-lg border border-white/10 transition">
                    {{ __('Cancelar') }}
                </button>
            </div>
        </x-custom-modal>
    </x-slot>
</x-action-section>