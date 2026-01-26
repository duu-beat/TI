<x-action-section>
    <x-slot name="title"><span class="text-red-400 font-bold">{{ __('Zona de Perigo') }}</span></x-slot>
    <x-slot name="description"><span class="text-slate-400">{{ __('Ações irreversíveis para sua conta.') }}</span></x-slot>

    <x-slot name="content">
        <div class="rounded-2xl border border-red-500/10 bg-red-500/5 p-5">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-red-500/10 rounded-xl text-red-500 text-xl">⚠️</div>
                <div>
                    <h4 class="text-white font-bold text-base">Deletar Conta</h4>
                    <div class="mt-1 text-sm text-slate-400 leading-relaxed">{{ __('Seus dados serão apagados permanentemente.') }}</div>
                    <div class="mt-4">
                        <button type="button" wire:click="confirmUserDeletion" wire:loading.attr="disabled" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-red-500/20 cursor-pointer">
                            {{ __('Excluir Conta') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">{{ __('Excluir Conta') }}</x-slot>
            <x-slot name="content">
                <div class="text-slate-300">{{ __('Tem certeza? Digite sua senha para confirmar.') }}</div>
                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" class="mt-2 w-full rounded-xl border border-red-500/30 bg-slate-950/50 px-4 py-3 text-slate-100 focus:border-red-500 transition outline-none" autocomplete="current-password" placeholder="{{ __('Senha') }}" x-ref="password" wire:model="password" wire:keydown.enter="deleteUser" />
                    <x-input-error for="password" class="mt-2 text-red-400" />
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">{{ __('Cancelar') }}</x-secondary-button>
                <button type="button" class="ms-3 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 shadow-lg transition cursor-pointer" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Excluir Permanentemente') }}
                </button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>