<x-form-section submit="updateProfileInformation">
    <x-slot name="title">{{ __('Informações do Perfil') }}</x-slot>
    <x-slot name="description">{{ __('Atualize as informações de perfil e endereço de e-mail da sua conta.') }}</x-slot>

    <x-slot name="form">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            {{-- Inicializa o Alpine passando o objeto $wire --}}
            <div x-data="photoCropper($wire)" class="col-span-6 sm:col-span-4">
                <input type="file" id="photo-input" class="hidden" x-ref="photoInput" accept="image/*" x-on:change="selectFile">

                <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Foto de Perfil') }}</label>

                <div class="flex items-center gap-6 p-4 rounded-2xl bg-slate-950/40 border border-white/5 relative overflow-hidden group">
                    <div class="relative shrink-0 z-10">
                        <div class="relative h-32 w-32 rounded-2xl overflow-hidden border-4 border-white/10 shadow-lg">
                             <img x-bind:src="photoPreview ? photoPreview : '{{ $this->user->profile_photo_url }}?v={{ time() }}'" 
                                  class="h-full w-full object-cover">
                        </div>
                    </div>

                    <div class="z-10">
                        <button type="button" class="flex items-center gap-3 px-5 py-3 bg-white/5 hover:bg-cyan-500/20 border border-white/10 hover:border-cyan-500/50 rounded-xl text-sm font-bold text-white uppercase tracking-wider transition-all duration-300 group/btn mb-3" x-on:click.prevent="$refs.photoInput.click()">
                            {{ __('Nova Foto') }}
                        </button>

                        @if ($this->user->profile_photo_path)
                            <button type="button" class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-red-400 hover:text-red-300 bg-red-500/5 hover:bg-red-500/20 border border-transparent hover:border-red-500/30 rounded-lg uppercase tracking-wider transition opacity-70 hover:opacity-100" wire:click="deleteProfilePhoto">
                                {{ __('Remover') }}
                            </button>
                        @endif
                    </div>
                </div>
                <x-input-error for="photo" class="mt-2" />

                <div x-show="cropping" style="display: none;" class="fixed inset-0 z-[9999] flex flex-col bg-black">
                    
                    <div class="px-6 py-4 bg-zinc-900 border-b border-white/10 flex justify-between items-center shrink-0 z-50">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span>✂️</span> {{ __('Ajustar Recorte') }}
                        </h3>
                        <button type="button" x-on:click="cancelCrop" class="text-zinc-400 hover:text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex-1 relative bg-black w-full h-full flex items-center justify-center overflow-hidden">
                        <div class="relative w-full h-[70vh]">
                            <img x-ref="cropperImage" src="" class="block max-w-full max-h-full mx-auto opacity-0">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-zinc-900 border-t border-white/10 flex justify-end gap-4 shrink-0 z-50">
                        <button type="button" x-on:click="cancelCrop" class="px-6 py-2 text-sm font-bold text-white border border-white/10 rounded-lg hover:bg-white/5 transition">
                            {{ __('Cancelar') }}
                        </button>
                        
                        <button type="button" x-on:click="cropAndSave" :disabled="loading" class="px-8 py-2 text-sm font-bold text-black bg-cyan-500 rounded-lg hover:bg-cyan-400 shadow-[0_0_20px_rgba(6,182,212,0.5)] transition flex items-center gap-2">
                            <span x-show="!loading">{{ __('Salvar Foto') }}</span>
                            <span x-show="loading" class="animate-pulse">{{ __('Salvando...') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Campos Nome e Email --}}
        <div class="col-span-6 sm:col-span-4 mt-4">
            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1">{{ __('Nome Completo') }}</label>
            <input id="name" type="text" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-200 focus:border-cyan-500/50 outline-none transition" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 mt-4">
            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1">{{ __('E-mail') }}</label>
            <input id="email" type="email" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-slate-200 focus:border-cyan-500/50 outline-none transition" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-400 font-bold" on="saved">{{ __('Salvo.') }}</x-action-message>
        <button wire:loading.attr="disabled" type="submit" class="rounded-xl bg-cyan-600 hover:bg-cyan-500 border border-cyan-400/20 px-6 py-3 text-sm font-bold text-white uppercase tracking-wider shadow-lg transition hover:scale-105">
            {{ __('Salvar Alterações') }}
        </button>
    </x-slot>
</x-form-section>