<x-form-section submit="updatePassword">
    <x-slot name="title">{{ __('Atualizar Senha') }}</x-slot>
    <x-slot name="description">{{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.') }}</x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 ml-1">{{ __('Senha Atual') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-purple-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <input id="current_password" type="password" class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-4 py-3 text-slate-200 focus:border-purple-500/50 focus:ring-4 focus:ring-purple-500/10 transition outline-none" wire:model="state.current_password" autocomplete="current-password" />
            </div>
            <x-input-error for="current_password" class="mt-2 text-red-400" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 ml-1">{{ __('Nova Senha') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-purple-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <input id="password" type="password" class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-4 py-3 text-slate-200 focus:border-purple-500/50 focus:ring-4 focus:ring-purple-500/10 transition outline-none" wire:model="state.password" autocomplete="new-password" />
            </div>
            <x-input-error for="password" class="mt-2 text-red-400" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 ml-1">{{ __('Confirmar Senha') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-purple-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <input id="password_confirmation" type="password" class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-4 py-3 text-slate-200 focus:border-purple-500/50 focus:ring-4 focus:ring-purple-500/10 transition outline-none" wire:model="state.password_confirmation" autocomplete="new-password" />
            </div>
            <x-input-error for="password_confirmation" class="mt-2 text-red-400" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-400 font-bold text-sm" on="saved">{{ __('Senha atualizada.') }}</x-action-message>
        <button type="submit" class="rounded-xl bg-white/10 border border-white/10 px-6 py-2 text-sm font-bold text-white hover:bg-white/20 hover:scale-105 transition shadow-lg cursor-pointer">
            {{ __('Salvar') }}
        </button>
    </x-slot>
</x-form-section>