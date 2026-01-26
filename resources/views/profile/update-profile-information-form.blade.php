<x-form-section submit="updateProfileInformation">
    <x-slot name="title">{{ __('Dados Cadastrais') }}</x-slot>
    <x-slot name="description">{{ __('Informações básicas de identificação.') }}</x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6">
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                        x-on:change="photoName = $refs.photo.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);" />
                
                <div class="flex items-center gap-5 p-4 rounded-2xl bg-slate-950/40 border border-white/5">
                    <div class="relative shrink-0">
                        <div x-show="!photoPreview">
                            <img src="{{ $this->user->profile_photo_url }}" class="h-16 w-16 rounded-2xl object-cover border-2 border-white/10 shadow-lg">
                        </div>
                        <div x-show="photoPreview" style="display: none;">
                            <span class="block h-16 w-16 rounded-2xl bg-cover bg-no-repeat bg-center border-2 border-white/10 shadow-lg" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 uppercase tracking-wider transition mb-1 flex items-center gap-1" x-on:click.prevent="$refs.photo.click()">
                            <span>📷</span> {{ __('Alterar Foto') }}
                        </button>
                        @if ($this->user->profile_photo_path)
                            <button type="button" class="block text-[10px] font-bold text-red-400 hover:text-red-300 uppercase tracking-wider transition opacity-60 hover:opacity-100" wire:click="deleteProfilePhoto">
                                {{ __('Remover') }}
                            </button>
                        @endif
                    </div>
                </div>
                <x-input-error for="photo" class="mt-2 text-red-400" />
            </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 ml-1">{{ __('Nome Completo') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-cyan-400 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <input id="name" type="text" class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-4 py-3 text-slate-200 placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all outline-none" wire:model="state.name" required autocomplete="name" />
            </div>
            <x-input-error for="name" class="mt-2 text-red-400" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 ml-1">{{ __('Email') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-cyan-400 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                </div>
                <input id="email" type="email" class="w-full rounded-xl border border-white/10 bg-slate-950/50 pl-11 pr-4 py-3 text-slate-200 placeholder:text-slate-600 focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all outline-none" wire:model="state.email" required autocomplete="username" />
            </div>
            <x-input-error for="email" class="mt-2 text-red-400" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-400 font-bold text-sm" on="saved">{{ __('Salvo.') }}</x-action-message>
        <button type="submit" wire:loading.attr="disabled" class="rounded-xl bg-white/10 border border-white/10 px-6 py-2 text-sm font-bold text-white hover:bg-white/20 hover:scale-105 transition-all shadow-lg cursor-pointer">
            {{ __('Salvar') }}
        </button>
    </x-slot>
</x-form-section>