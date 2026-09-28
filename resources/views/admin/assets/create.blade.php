<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.assets.index') }}" 
               class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    📦 {{ __('assets.ui.new_asset') }}
                </h2>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">{{ __('assets.ui.add_to_inventory') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl">
                {{-- Background Decorativo --}}
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <form action="{{ route('admin.assets.store') }}" method="POST" class="relative z-10 p-8 sm:p-10 space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nome --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.equipment_name') }}</label>
                            <input type="text" name="name" required placeholder="{{ __('assets.ui.equipment_name_placeholder') }}"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        {{-- Patrimônio --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.asset_tag') }}</label>
                            <input type="text" name="tag" required placeholder="{{ __('assets.ui.asset_tag_placeholder') }}"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                            <x-input-error for="tag" class="mt-2" />
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.asset_type') }}</label>
                            <select name="type" required 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="Laptop" class="bg-slate-900">Laptop</option>
                                <option value="Desktop" class="bg-slate-900">Desktop</option>
                                <option value="Monitor" class="bg-slate-900">Monitor</option>
                                <option value="Impressora" class="bg-slate-900">Impressora</option>
                                <option value="Celular" class="bg-slate-900">Celular</option>
                                <option value="Outros" class="bg-slate-900">Outros</option>
                            </select>
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.brand') }}</label>
                            <input type="text" name="brand" placeholder="{{ __('assets.ui.brand_placeholder') }}"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                        </div>

                        {{-- Modelo --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.model') }}</label>
                            <input type="text" name="model" placeholder="{{ __('assets.ui.model_placeholder') }}"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                        </div>

                        {{-- Serial --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.serial_number') }}</label>
                            <input type="text" name="serial_number" placeholder="{{ __('assets.ui.serial_placeholder') }}"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700">
                        </div>

                        {{-- {{ __('assets.ui.responsible_user') }} --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.responsible_user') }}</label>
                            <select name="user_id" 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="" class="bg-slate-900">{{ __('assets.ui.without_assignment') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" class="bg-slate-900">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.initial_status') }}</label>
                            <select name="status" required 
                                    class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                <option value="active" class="bg-slate-900">{{ __('assets.statuses.active') }}</option>
                                <option value="maintenance" class="bg-slate-900">{{ __('assets.statuses.maintenance') }}</option>
                                <option value="retired" class="bg-slate-900">{{ __('assets.statuses.retired') }}</option>
                                <option value="lost" class="bg-slate-900">{{ __('assets.statuses.lost') }}</option>
                            </select>
                        </div>

                        {{-- Datas --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.purchase_date') }}</label>
                            <input type="date" name="purchase_date"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.warranty_expiration') }}</label>
                            <input type="date" name="warranty_expiration"
                                   class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                        </div>

                        {{-- Notas --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">{{ __('assets.ui.technical_notes') }}</label>
                            <textarea name="notes" rows="4" placeholder="{{ __('assets.ui.technical_notes_placeholder') }}"
                                      class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition placeholder-slate-700"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-white/5">
                        <a href="{{ route('admin.assets.index') }}" 
                           class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-2xl transition text-center">
                            {{ __('assets.ui.cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-10 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 active:translate-y-0">
                            {{ __('assets.ui.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
