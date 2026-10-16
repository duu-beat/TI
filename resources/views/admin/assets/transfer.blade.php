<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.assets.show', $asset) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('assets.ui.back_to_asset') }}</a>
            <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-400">{{ __('assets.transfer.eyebrow') }}</p><h2 class="mt-1 text-xl font-bold text-white">{{ __('assets.transfer.title') }}</h2></div>
        </div>
    </x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-slate-900/70 p-7 shadow-2xl sm:p-9">
            <div class="rounded-2xl border border-cyan-400/15 bg-cyan-400/5 p-4"><p class="text-xs font-bold uppercase tracking-widest text-cyan-300">{{ $asset->tag }}</p><p class="mt-1 text-lg font-bold text-white">{{ $asset->name }}</p><p class="mt-1 text-sm text-slate-400">{{ __('assets.transfer.current_location') }}: {{ $asset->branch?->name ?: __('assets.ui.without_branch') }} · {{ $asset->sector?->name ?: __('assets.ui.without_sector') }}</p></div>
            <form method="POST" action="{{ route('admin.assets.transfer.store', $asset) }}" class="mt-7 space-y-6">@csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2"><label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('assets.ui.responsible_user') }}</label><select name="user_id" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white"><option value="">{{ __('assets.ui.without_assignment') }}</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('user_id', $asset->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>@endforeach</select>@error('user_id')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('assets.ui.branch') }}</label><select name="branch_id" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white"><option value="">{{ __('assets.ui.without_branch') }}</option>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $asset->branch_id) == $branch->id)>{{ $branch->name }}</option>@endforeach</select>@error('branch_id')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('assets.ui.sector') }}</label><select name="sector_id" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white"><option value="">{{ __('assets.ui.without_sector') }}</option>@foreach($sectors as $sector)<option value="{{ $sector->id }}" @selected(old('sector_id', $asset->sector_id) == $sector->id)>{{ $sector->name }}</option>@endforeach</select>@error('sector_id')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('assets.transfer.reason') }}</label><textarea name="reason" required maxlength="500" rows="4" class="w-full rounded-xl border border-white/10 bg-slate-950/50 px-4 py-3 text-white" placeholder="{{ __('assets.transfer.reason_placeholder') }}">{{ old('reason') }}</textarea>@error('reason')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror</div>
                </div>
                <div class="flex justify-end gap-3 border-t border-white/5 pt-6"><a href="{{ route('admin.assets.show', $asset) }}" class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-400">{{ __('assets.ui.cancel') }}</a><button class="rounded-xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white hover:bg-cyan-500">{{ __('assets.transfer.confirm') }}</button></div>
            </form>
        </div>
    </div></div>
</x-app-layout>
