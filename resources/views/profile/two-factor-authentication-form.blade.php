<x-action-section>
    <x-slot name="title">{{ __('Autenticação de Dois Fatores') }}</x-slot>
    <x-slot name="description">{{ __('Adicione uma camada extra de segurança à sua conta.') }}</x-slot>

    <x-slot name="content">
        {{-- Status Header --}}
        <div class="flex items-center gap-5 mb-8 p-5 rounded-2xl {{ $this->enabled ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-slate-950 border border-white/10' }}">
            <div class="h-14 w-14 rounded-full flex items-center justify-center text-2xl shadow-inner {{ $this->enabled ? 'bg-emerald-500/20 text-emerald-400 ring-2 ring-emerald-500/30' : 'bg-slate-800 text-slate-500 ring-1 ring-white/10' }}">
                {!! $this->enabled ? '🛡️' : '🔓' !!}
            </div>
            <div>
                @if ($this->enabled)
                    @if ($showingConfirmation)
                        <h4 class="text-base font-bold text-white uppercase tracking-wide">{{ __('Finalizando Configuração') }}</h4>
                        <p class="text-xs text-slate-400 mt-1">{{ __('Escaneie o QR Code abaixo para confirmar.') }}</p>
                    @else
                        <h4 class="text-base font-bold text-emerald-400 uppercase tracking-wide flex items-center gap-2">
                            {{ __('Proteção Ativa') }} <span class="animate-pulse">●</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">{{ __('Sua conta está blindada com 2FA.') }}</p>
                    @endif
                @else
                    <h4 class="text-base font-bold text-white uppercase tracking-wide">{{ __('Proteção Desativada') }}</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ __('Ative para proteger sua conta contra invasões.') }}</p>
                @endif
            </div>
        </div>

        @if ($this->enabled)
            <div class="grid md:grid-cols-2 gap-8">
                @if ($showingQrCode)
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-cyan-400 uppercase tracking-wider">
                            {{ __('1. Escaneie o código') }}
                        </p>
                        <div class="p-4 bg-white rounded-2xl inline-block shadow-[0_0_30px_-5px_rgba(255,255,255,0.3)]">
                            {!! $this->user->twoFactorQrCodeSvg() !!}
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">{{ __('Ou digite a chave:') }}</p>
                            <code class="px-4 py-2 rounded-lg bg-slate-950 border border-cyan-500/30 text-cyan-400 font-mono text-sm select-all block w-full text-center">
                                {{ decrypt($this->user->two_factor_secret) }}
                            </code>
                        </div>
                    </div>
                @endif

                @if ($showingConfirmation)
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-cyan-400 uppercase tracking-wider">
                            {{ __('2. Confirme o código') }}
                        </p>
                        <div class="bg-slate-950 p-6 rounded-2xl border border-white/10">
                            <label class="block text-xs text-slate-500 mb-2">{{ __('Código do App Autenticador') }}</label>
                            <input type="text" 
                                   class="w-full text-center text-2xl tracking-[0.5em] font-mono bg-slate-900 border border-white/20 rounded-xl py-3 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition outline-none text-white placeholder-slate-700"
                                   inputmode="numeric" 
                                   autofocus 
                                   autocomplete="one-time-code" 
                                   wire:model="code" 
                                   wire:keydown.enter="confirmTwoFactorAuthentication" 
                                   placeholder="000000" />
                            <x-input-error for="code" class="mt-2 text-center" />
                        </div>
                    </div>
                @endif
            </div>

            @if ($showingRecoveryCodes)
                <div class="mt-8 bg-slate-950 border border-dashed border-white/20 rounded-2xl p-6 relative">
                    <div class="absolute -top-3 left-4 bg-slate-900 px-2 text-xs font-bold text-amber-400 uppercase tracking-wider">
                        ⚠️ {{ __('Códigos de Recuperação') }}
                    </div>
                    <p class="text-sm text-slate-400 mb-4">
                        {{ __('Guarde estes códigos em um local seguro (gerenciador de senhas). Se perder seu celular, eles são a única forma de recuperar sua conta.') }}
                    </p>
                    <div class="grid grid-cols-2 gap-3 font-mono text-xs">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div class="bg-white/5 border border-white/5 p-2 rounded text-center text-slate-300 tracking-wider hover:bg-white/10 hover:text-white transition cursor-copy select-all">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-bold uppercase tracking-wide rounded-xl shadow-lg shadow-emerald-500/20 transition hover:scale-105">
                        {{ __('Ativar Segurança') }}
                    </button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <button type="button" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold uppercase rounded-lg border border-white/10 transition">
                            {{ __('Gerar Novos Códigos') }}
                        </button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <button type="button" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold uppercase rounded-xl shadow-[0_0_20px_rgba(8,145,178,0.5)] transition" wire:loading.attr="disabled">
                            {{ __('Confirmar Ativação') }}
                        </button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <button type="button" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold uppercase rounded-lg border border-white/10 transition">
                            {{ __('Ver Códigos') }}
                        </button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <button type="button" wire:loading.attr="disabled" class="px-4 py-2 text-xs font-bold text-slate-400 hover:text-white transition">
                            {{ __('Cancelar') }}
                        </button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <button type="button" wire:loading.attr="disabled" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 text-xs font-bold uppercase rounded-lg border border-red-500/20 transition">
                            {{ __('Desativar') }}
                        </button>
                    </x-confirms-password>
                @endif
            @endif
        </div>
    </x-slot>
</x-action-section>