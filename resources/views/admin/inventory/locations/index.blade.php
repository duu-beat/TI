<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-400">{{ __('assets.locations.eyebrow') }}</p>
                <h2 class="mt-1 text-xl font-bold text-white">{{ __('assets.locations.title') }}</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="$dispatch('open-branch-form')" class="rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-cyan-500">{{ __('assets.locations.new_branch') }}</button>
                <button type="button" @click="$dispatch('open-sector-form')" class="rounded-xl border border-indigo-400/25 bg-indigo-500/10 px-4 py-2.5 text-sm font-bold text-indigo-200 transition hover:bg-indigo-500 hover:text-white">{{ __('assets.locations.new_sector') }}</button>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showBranchForm: false, showSectorForm: false }" @open-branch-form.window="showBranchForm = true" @open-sector-form.window="showSectorForm = true">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-300">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                    <p class="font-bold">{{ __('assets.locations.validation_title') }}</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-2">
                @forelse($branches as $branch)
                    <section class="rounded-3xl border border-white/10 bg-slate-900/65 p-6 shadow-xl">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-400">{{ $branch->code ?: __('assets.locations.no_code') }}</p>
                                <h3 class="mt-1 text-xl font-black text-white">{{ $branch->name }}</h3>
                                @if($branch->address)<p class="mt-1 text-sm text-slate-500">{{ $branch->address }}</p>@endif
                            </div>
                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-slate-400">{{ $branch->assets_count }} {{ __('assets.locations.assets') }}</span>
                        </div>

                        <div class="mt-5 space-y-2">
                            @forelse($branch->sectors as $sector)
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-white/5 bg-slate-950/35 px-4 py-3">
                                    <div><p class="text-sm font-semibold text-slate-200">{{ $sector->name }}</p><p class="text-xs text-slate-500">{{ $sector->assets_count }} {{ __('assets.locations.assets') }}</p></div>
                                    <div class="flex gap-2">
                                        <button type="button" @click="showSectorForm = true; $nextTick(() => { $refs.sectorForm.action = '{{ route('admin.inventory.locations.sectors.update', $sector) }}'; $refs.sectorName.value = @js($sector->name); $refs.sectorBranch.value = '{{ $branch->id }}'; $refs.sectorMethod.value = 'PUT'; })" class="text-xs font-bold text-indigo-300 hover:text-white">{{ __('assets.locations.edit') }}</button>
                                        <form method="POST" action="{{ route('admin.inventory.locations.sectors.destroy', $sector) }}" onsubmit="return confirm('{{ __('assets.locations.confirm_delete_sector') }}')">@csrf @method('DELETE')<button class="text-xs font-bold text-rose-300 hover:text-rose-200">{{ __('assets.locations.delete') }}</button></form>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-xl border border-dashed border-white/10 px-4 py-5 text-center text-sm text-slate-500">{{ __('assets.locations.empty_sectors') }}</p>
                            @endforelse
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-white/5 pt-4">
                            <span class="text-xs text-slate-500">{{ $branch->sectors_count }} {{ __('assets.locations.sectors') }}</span>
                            <div class="flex gap-3">
                                <button type="button" @click="showBranchForm = true; $nextTick(() => { $refs.branchForm.action = '{{ route('admin.inventory.locations.branches.update', $branch) }}'; $refs.branchName.value = @js($branch->name); $refs.branchCode.value = @js($branch->code); $refs.branchAddress.value = @js($branch->address); $refs.branchMethod.value = 'PUT'; })" class="text-xs font-bold text-cyan-300 hover:text-white">{{ __('assets.locations.edit') }}</button>
                                <form method="POST" action="{{ route('admin.inventory.locations.branches.destroy', $branch) }}" onsubmit="return confirm('{{ __('assets.locations.confirm_delete_branch') }}')">@csrf @method('DELETE')<button class="text-xs font-bold text-rose-300 hover:text-rose-200">{{ __('assets.locations.delete') }}</button></form>
                            </div>
                        </div>
                    </section>
                @empty
                    <div class="lg:col-span-2 rounded-3xl border border-dashed border-white/10 bg-slate-900/50 px-6 py-14 text-center text-sm text-slate-500">{{ __('assets.locations.empty_branches') }}</div>
                @endforelse
            </div>
        </div>

        <div x-show="showBranchForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-slate-900 p-6 shadow-2xl" @click.outside="showBranchForm = false">
                <div class="flex items-center justify-between"><h3 class="text-xl font-bold text-white">{{ __('assets.locations.branch_form') }}</h3><button type="button" @click="showBranchForm = false" class="text-slate-400 hover:text-white">✕</button></div>
                <form x-ref="branchForm" method="POST" action="{{ route('admin.inventory.locations.branches.store') }}" class="mt-6 space-y-4">
                    @csrf <input x-ref="branchMethod" type="hidden" name="_method" value="POST">
                    <input x-ref="branchName" name="name" required placeholder="{{ __('assets.locations.branch_name') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white">
                    <input x-ref="branchCode" name="code" placeholder="{{ __('assets.locations.branch_code') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white">
                    <input x-ref="branchAddress" name="address" placeholder="{{ __('assets.locations.branch_address') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white">
                    <div class="flex justify-end gap-3"><button type="button" @click="showBranchForm = false" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-400">{{ __('assets.ui.cancel') }}</button><button class="rounded-xl bg-cyan-600 px-5 py-2 text-sm font-bold text-white">{{ __('assets.ui.save') }}</button></div>
                </form>
            </div>
        </div>

        <div x-show="showSectorForm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-slate-900 p-6 shadow-2xl" @click.outside="showSectorForm = false">
                <div class="flex items-center justify-between"><h3 class="text-xl font-bold text-white">{{ __('assets.locations.sector_form') }}</h3><button type="button" @click="showSectorForm = false" class="text-slate-400 hover:text-white">✕</button></div>
                <form x-ref="sectorForm" method="POST" action="{{ route('admin.inventory.locations.sectors.store') }}" class="mt-6 space-y-4">
                    @csrf <input x-ref="sectorMethod" type="hidden" name="_method" value="POST">
                    <select x-ref="sectorBranch" name="branch_id" required class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white"><option value="">{{ __('assets.locations.select_branch') }}</option>@foreach($branches as $branch)<option value="{{ $branch->id }}">{{ $branch->name }}</option>@endforeach</select>
                    <input x-ref="sectorName" name="name" required placeholder="{{ __('assets.locations.sector_name') }}" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white">
                    <div class="flex justify-end gap-3"><button type="button" @click="showSectorForm = false" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-400">{{ __('assets.ui.cancel') }}</button><button class="rounded-xl bg-indigo-600 px-5 py-2 text-sm font-bold text-white">{{ __('assets.ui.save') }}</button></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
