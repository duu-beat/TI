<x-action-section>
    <x-slot name="title">{{ __('Autenticação de Dois Fatores') }}</x-slot>
    <x-slot name="description">{{ __('Adicione segurança adicional à sua conta usando a autenticação de dois fatores.') }}</x-slot>

    <x-slot name="content">
        {{-- Status --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="h-12 w-12 rounded-full flex items-center justify-center text-xl shadow-inner {{ $this->enabled ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/50' : 'bg-slate-800 text-slate-500 border border-white/10' }}">
                {!! $this->enabled ? '🛡️' : '🔓' !!}
            </div>
            <div>
                @if ($this->enabled)
                    @if ($showingConfirmation)
                        <h4 class="text-sm font-bold text-white uppercase tracking-wide">{{ __('Finalizar ativação da autenticação de dois fatores.') }}</h4>
                    @else
                        <h4 class="text-sm font-bold text-white uppercase tracking-wide">{{ __('Você ativou a autenticação de dois fatores.') }}</h4>
                    @endif
                @else
                    <h4 class="text-sm font-bold text-white uppercase tracking-wide">{{ __('Você não ativou a autenticação de dois fatores.') }}</h4>
                @endif
                
                <p class="text-xs text-slate-400 mt-1">
                    {{ __('Quando a autenticação de dois fatores está ativada, você será solicitado a fornecer um token seguro e aleatório durante a autenticação. Você pode recuperar esse token do aplicativo Google Authenticator do seu telefone.') }}
                </p>
            </div>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mb-6 p-4 bg-white rounded-xl inline-block border-4 border-white shadow-xl">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
                
                <div class="mb-6">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Chave de Configuração') }}:</p>
                    <code class="px-3 py-2 rounded-lg bg-slate-950 border border-white/10 text-cyan-400 font-mono text-xs select-all block w-full text-center">
                        {{ decrypt($this->user->two_factor_secret) }}
                    </code>
                </div>

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Código') }}</label>
                        <input type="text" class="w-full rounded-xl border border-emerald-500/50 bg-slate-950/50 px-4 py-2.5 text-slate-100 text-center tracking-widest text-lg focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition outline-none" inputmode="numeric" autofocus autocomplete="one-time-code" wire:model="code" wire:keydown.enter="confirmTwoFactorAuthentication" placeholder="000 000" />
                        <x-input-error for="code" class="mt-2 text-red-400" />
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="mb-6">
                    <p class="text-sm font-bold text-slate-300 mb-3">
                        {{ __('Armazene esses códigos de recuperação em um gerenciador de senhas seguro. Eles podem ser usados para recuperar o acesso à sua conta se o dispositivo de autenticação de dois fatores for perdido.') }}
                    </p>
                    <div class="grid grid-cols-2 gap-2 bg-slate-950 rounded-xl p-3 border border-white/10 font-mono text-[10px] text-slate-400">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div class="bg-white/5 p-1.5 rounded text-center">{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div class="mt-6 flex flex-col gap-2">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled" class="w-full rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-4 py-3 font-bold text-sm transition shadow-lg cursor-pointer">
                        {{ __('Ativar') }}
                    </button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <button type="button" class="w-full px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs font-bold text-white hover:bg-slate-700 transition cursor-pointer">
                            {{ __('Gerar Novos Códigos de Recuperação') }}
                        </button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <button type="button" class="w-full rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-4 py-3 font-bold text-sm transition cursor-pointer" wire:loading.attr="disabled">
                            {{ __('Confirmar') }}
                        </button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <button type="button" class="w-full px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs font-bold text-white hover:bg-slate-700 transition cursor-pointer">
                            {{ __('Mostrar Códigos de Recuperação') }}
                        </button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <button type="button" wire:loading.attr="disabled" class="w-full px-4 py-2 text-xs font-bold text-slate-500 hover:text-white transition cursor-pointer">
                            {{ __('Cancelar') }}
                        </button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <button type="button" wire:loading.attr="disabled" class="w-full px-4 py-2 bg-red-500/10 border border-red-500/20 rounded-xl text-xs font-bold text-red-400 hover:bg-red-500/20 transition mt-2 cursor-pointer">
                            {{ __('Desativar') }}
                        </button>
                    </x-confirms-password>
                @endif
            @endif
        </div>
    </x-slot>
</x-action-section>