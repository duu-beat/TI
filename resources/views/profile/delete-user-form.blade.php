<x-action-section>
    <x-slot name="title"><span class="text-red-500 font-bold tracking-wide">{{ __('ZONA DE PERIGO') }}</span></x-slot>
    <x-slot name="description">{{ __('Excluir permanentemente sua conta.') }}</x-slot>

    <x-slot name="content">
        <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-red-500/10 rounded-full blur-3xl group-hover:bg-red-500/20 transition duration-1000"></div>

            <div class="relative z-10">
                <div class="max-w-xl text-sm text-slate-400">
                    {{ __('Depois que sua conta for excluída, todos os seus recursos e dados serão apagados permanentemente. O processo é irreversível.') }}
                </div>

                <div class="mt-5">
                    <button wire:click="confirmUserDeletion" wire:loading.attr="disabled" class="px-6 py-3 bg-red-600 hover:bg-red-500 text-white text-sm font-bold uppercase tracking-wider rounded-xl shadow-[0_0_20px_-5px_rgba(239,68,68,0.6)] hover:shadow-[0_0_30px_-5px_rgba(239,68,68,0.8)] hover:scale-105 transition duration-300">
                        {{ __('Excluir Conta') }}
                    </button>
                </div>
            </div>
        </div>

        <x-custom-modal wire:model.live="confirmingUserDeletion">
            <div class="p-6 text-center bg-slate-900">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10 mb-6 animate-pulse">
                    <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-white mb-2">
                    {{ __('Tem certeza absoluta?') }}
                </h3>

                <p class="text-sm text-slate-400 mb-6">
                    {{ __('Por favor, digite sua senha para confirmar. Todos os seus dados serão perdidos.') }}
                </p>

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" 
                           class="w-full text-center bg-slate-950 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition outline-none" 
                           placeholder="{{ __('Sua Senha') }}" 
                           x-ref="password" 
                           wire:model="password" 
                           wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2 text-red-400 font-bold" />
                </div>
            </div>

            <div class="bg-slate-950 px-6 py-4 flex flex-row-reverse gap-3 border-t border-white/5">
                <button wire:click="deleteUser" wire:loading.attr="disabled" class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded-lg shadow-lg shadow-red-500/20 transition">
                    {{ __('Sim, Excluir Tudo') }}
                </button>
                
                <button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled" class="px-5 py-2 bg-transparent hover:bg-white/5 text-slate-300 text-sm font-bold rounded-lg border border-white/10 transition">
                    {{ __('Cancelar') }}
                </button>
            </div>
        </x-custom-modal>
    </x-slot>
</x-action-section>