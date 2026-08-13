<x-form-section submit="updateProfileInformation">
    <x-slot name="title">{{ __('Informações do Perfil') }}</x-slot>
    <x-slot name="description">{{ __('Atualize seus dados e mantenha sua identidade de acesso atualizada.') }}</x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="col-span-6 sm:col-span-4">
                <input id="photo-input" type="file" class="hidden" wire:model="photo" accept="image/png,image/jpeg,image/webp" />

                <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2" for="photo-input">
                    {{ __('Foto de Perfil') }}
                </label>

                <div class="flex flex-col sm:flex-row sm:items-center gap-5 p-5 rounded-2xl bg-slate-950/40 border border-white/5">
                    <div class="relative h-24 w-24 shrink-0 rounded-2xl overflow-hidden border-4 border-white/10 shadow-lg">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="h-full w-full object-cover" alt="{{ __('Pré-visualização da nova foto de perfil') }}">
                        @else
                            <img src="{{ $this->user->profile_photo_url }}" class="h-full w-full object-cover" alt="{{ $this->user->name }}">
                        @endif

                        <div wire:loading wire:target="photo" class="absolute inset-0 flex items-center justify-center bg-slate-950/80 text-xs font-bold text-cyan-300">
                            {{ __('Enviando') }}
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-sm text-slate-400">
                            {{ __('Use uma imagem JPG, PNG ou WEBP de até 2 MB. A foto é exibida apenas em sua conta autenticada.') }}
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <label for="photo-input" class="cursor-pointer inline-flex items-center px-4 py-2 rounded-xl bg-white/5 hover:bg-cyan-500/20 border border-white/10 hover:border-cyan-500/50 text-sm font-bold text-white uppercase tracking-wider transition">
                                {{ __('Escolher Foto') }}
                            </label>

                            @if ($this->user->profile_photo_path)
                                <button type="button" wire:click="deleteProfilePhoto" wire:loading.attr="disabled" wire:target="photo,deleteProfilePhoto" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-red-400 hover:text-red-300 bg-red-500/5 hover:bg-red-500/20 border border-red-500/20 uppercase tracking-wider transition">
                                    {{ __('Remover') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <div class="col-span-6 sm:col-span-4 mt-4">
            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1" for="name">{{ __('Nome Completo') }}</label>
            <input id="name" type="text" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-200 focus:border-cyan-500/50 outline-none transition" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 mt-4">
            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1" for="email">{{ __('E-mail') }}</label>
            <input id="email" type="email" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-200 focus:border-cyan-500/50 outline-none transition" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-400 font-bold" on="saved">{{ __('Salvo.') }}</x-action-message>
        <button wire:loading.attr="disabled" wire:target="photo,updateProfileInformation" type="submit" class="rounded-xl bg-cyan-600 hover:bg-cyan-500 border border-cyan-400/20 px-6 py-3 text-sm font-bold text-white uppercase tracking-wider shadow-lg transition hover:scale-105">
            {{ __('Salvar Alterações') }}
        </button>
    </x-slot>
</x-form-section>
