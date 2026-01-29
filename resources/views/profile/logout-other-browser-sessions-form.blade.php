<x-action-section>
    <x-slot name="title">{{ __('Sessões de Navegador') }}</x-slot>
    <x-slot name="description">{{ __('Gerencie e saia das suas sessões ativas em outros navegadores e dispositivos.') }}</x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-slate-400 mb-6">
            {{ __('Se necessário, você pode sair de todas as outras sessões do navegador em todos os seus dispositivos. Algumas de suas sessões recentes estão listadas abaixo; no entanto, esta lista pode não ser exaustiva. Se você acha que sua conta foi comprometida, também deve atualizar sua senha.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="space-y-3">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-950/30 border {{ $session->is_current_device ? 'border-cyan-500/30 bg-cyan-500/5' : 'border-white/5' }} transition hover:border-white/10">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center text-xl {{ $session->is_current_device ? 'bg-cyan-500/20 text-cyan-400' : 'bg-white/5 text-slate-500' }}">
                                @if ($session->agent->isDesktop()) 💻 @else 📱 @endif
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white flex items-center gap-2">
                                    {{ $session->agent->platform() ?: 'Desconhecido' }} 
                                    <span class="w-1 h-1 rounded-full bg-slate-600"></span> 
                                    {{ $session->agent->browser() ?: 'Desconhecido' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                    <span>{{ $session->ip_address }}</span>
                                    @if ($session->is_current_device)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-cyan-500/20 text-cyan-400 uppercase tracking-wide">{{ __('Este dispositivo') }}</span>
                                    @else
                                        <span>• {{ $session->last_active }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-8 pt-6 border-t border-white/5">
            <button type="button" wire:click="confirmLogout" wire:loading.attr="disabled" class="flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-bold text-white hover:bg-slate-700 transition hover:border-red-500/50 hover:text-red-400 group">
                <span class="group-hover:grayscale-0 grayscale transition text-lg">🚪</span>
                {{ __('Sair de outras sessões de navegador') }}
            </button>
            <x-action-message class="ms-3 text-emerald-400 font-bold" on="loggedOut">{{ __('Concluído.') }}</x-action-message>
        </div>

        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="title">{{ __('Sair de outras sessões de navegador') }}</x-slot>
            <x-slot name="content">
                <span class="text-slate-300">{{ __('Por favor, digite sua senha para confirmar que deseja sair das suas outras sessões de navegador em todos os seus dispositivos.') }}</span>
                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-cyan-400/50 transition outline-none" placeholder="{{ __('Senha') }}" x-ref="password" wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions" />
                    <x-input-error for="password" class="mt-2 text-red-400" />
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">{{ __('Cancelar') }}</x-secondary-button>
                <button type="button" class="ms-3 px-4 py-2 bg-gradient-to-r from-red-500 to-orange-500 rounded-xl text-sm font-bold text-white hover:shadow-lg transition hover:scale-105" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                    {{ __('Sair de Outras Sessões') }}
                </button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>