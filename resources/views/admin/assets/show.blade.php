<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-400">{{ __('assets.ui.internal_inventory') }}</p>
                <h2 class="mt-1 text-xl font-bold text-white">{{ __('assets.ui.asset_sheet') }}</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.assets.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition">
                    {{ __('assets.ui.back_to_inventory') }}
                </a>
                <a href="{{ route('admin.assets.terms.create', $asset) }}" class="rounded-xl border border-indigo-400/25 bg-indigo-500/10 px-4 py-2 text-sm font-bold text-indigo-200 hover:bg-indigo-500 hover:text-white transition">
                    {{ __('assets.ui.issue_term') }}
                </a>
                <a href="{{ route('admin.assets.edit', $asset) }}" class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-bold text-white hover:bg-cyan-500 transition">
                    {{ __('assets.ui.maintenance_editing') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-2xl">
                <div class="grid gap-0 lg:grid-cols-[1fr_330px]">
                    <div class="p-7 sm:p-9">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/10 text-3xl">
                                @switch($asset->type)
                                    @case('Laptop') 💻 @break
                                    @case('Desktop') 🖥️ @break
                                    @case('Monitor') 🖥️ @break
                                    @case('Celular') 📱 @break
                                    @case('Impressora') 🖨️ @break
                                    @default 📦
                                @endswitch
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">{{ __('assets.ui.asset_tag') }} #{{ $asset->tag }}</p>
                                <h1 class="mt-1 text-2xl font-black text-white sm:text-3xl">{{ $asset->name }}</h1>
                                <p class="mt-1 text-sm text-slate-400">{{ collect([$asset->brand, $asset->model, $asset->type])->filter()->join(' · ') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            <div class="rounded-2xl border border-white/5 bg-slate-950/40 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('assets.ui.status') }}</p>
                                <p class="mt-2 text-sm font-bold text-{{ $asset->getStatusColor() }}-400">{{ $asset->getStatusLabel() }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/5 bg-slate-950/40 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('assets.ui.serial_number') }}</p>
                                <p class="mt-2 truncate font-mono text-sm text-slate-200">{{ $asset->serial_number ?: __('assets.ui.not_informed') }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/5 bg-slate-950/40 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('assets.ui.responsible_user') }}</p>
                                <p class="mt-2 truncate text-sm font-semibold text-slate-200">{{ $asset->user?->name ?: __('assets.ui.available_in_stock') }}</p>
                            </div>
                        </div>

                        @if ($asset->notes)
                            <div class="mt-5 rounded-2xl border border-white/5 bg-white/[0.03] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('assets.ui.observations') }}</p>
                                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-300">{{ $asset->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <aside class="border-t border-white/10 bg-slate-950/50 p-7 lg:border-l lg:border-t-0" x-data>
                        <div class="rounded-2xl bg-white p-4 shadow-xl">
                            <img src="{{ route('admin.assets.qr-code', $asset) }}" class="mx-auto h-56 w-56" alt="{{ __('assets.ui.qr_code_internal', ['tag' => $asset->tag]) }}">
                        </div>
                        <p class="mt-5 text-center text-xs font-bold uppercase tracking-[0.16em] text-slate-300">{{ __('assets.ui.label_internal', ['tag' => $asset->tag]) }}</p>
                        <p class="mt-2 text-center text-xs leading-relaxed text-slate-500">{{ __('assets.ui.scan_opens_sheet') }}</p>
                        <a href="{{ route('admin.assets.qr-label', $asset) }}" target="_blank" rel="noopener" class="mt-5 block w-full rounded-xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-3 text-center text-sm font-bold text-cyan-300 hover:bg-cyan-400/20 transition">
                            {{ __('assets.ui.open_label_print') }}
                        </a>
                    </aside>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-400">{{ __('assets.ui.traceability') }}</p>
                            <h3 class="mt-1 text-lg font-bold text-white">{{ __('assets.ui.movement_history_title') }}</h3>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-slate-400">{{ __('assets.ui.records_count', ['count' => $asset->history->count()]) }}</span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($asset->history as $history)
                            <article class="rounded-2xl border border-white/5 bg-slate-950/30 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-sm font-semibold text-slate-200">{{ $history->description }}</p>
                                    <time class="shrink-0 text-xs text-slate-500">{{ $history->created_at->format('d/m/Y H:i') }}</time>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">{{ __('assets.ui.registered_by') }} {{ $history->user?->name ?: __('assets.ui.system') }}</p>
                            </article>
                        @empty
                            <p class="rounded-2xl border border-dashed border-white/10 p-5 text-center text-sm text-slate-500">{{ __('assets.ui.empty_movements') }}</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-400">{{ __('assets.ui.support') }}</p>
                            <h3 class="mt-1 text-lg font-bold text-white">{{ __('assets.ui.linked_tickets') }}</h3>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-slate-400">{{ __('assets.ui.displayed_count', ['count' => $asset->tickets->count()]) }}</span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($asset->tickets as $ticket)
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="block rounded-2xl border border-white/5 bg-slate-950/30 p-4 hover:border-cyan-400/30 hover:bg-slate-950/50 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="font-semibold text-slate-200">#{{ $ticket->id }} · {{ $ticket->subject }}</p>
                                    <span class="shrink-0 text-xs font-bold uppercase text-slate-500">{{ $ticket->status->value }}</span>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </a>
                        @empty
                            <p class="rounded-2xl border border-dashed border-white/10 p-5 text-center text-sm text-slate-500">{{ __('assets.ui.empty_tickets') }}</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-3xl border border-indigo-400/15 bg-slate-900/60 p-6 shadow-xl">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-300">{{ __('assets.ui.digital_responsibility') }}</p><h3 class="mt-1 text-lg font-bold text-white">{{ __('assets.ui.delivery_return_terms') }}</h3></div>
                    <a href="{{ route('admin.assets.terms.create', $asset) }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-indigo-500 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-400"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" /></svg>{{ __('assets.ui.new_term') }}</a>
                </div>

                <div class="mt-5 grid gap-3 xl:grid-cols-2">
                    @forelse($asset->responsibilityTerms as $term)
                        <article class="rounded-2xl border border-white/10 bg-slate-950/35 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-sm font-bold text-slate-100">{{ $term->typeLabel() }}</p><p class="mt-1 text-xs text-slate-500">{{ $term->recipient->name }} · emitido por {{ $term->issuer->name }}</p></div><span class="rounded-lg px-2 py-1 text-[10px] font-bold uppercase tracking-wider {{ $term->isSigned() ? 'border border-emerald-400/20 bg-emerald-500/10 text-emerald-300' : ($term->status === 'cancelled' ? 'border border-slate-400/15 bg-slate-500/10 text-slate-400' : 'border border-amber-400/20 bg-amber-500/10 text-amber-300') }}">{{ $term->isSigned() ? __('assets.ui.signed') : ($term->status === 'cancelled' ? __('assets.ui.cancelled') : __('assets.ui.pending')) }}</span></div>
                            <p class="mt-3 text-xs text-slate-500">{{ $term->isSigned() ? __('assets.ui.signed_at', ['date' => $term->signed_at?->format('d/m/Y H:i')]) : __('assets.ui.awaiting_signature') }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @if($term->isSigned())
                                    <a href="{{ route('admin.assets.terms.download', [$asset, $term]) }}" class="inline-flex rounded-lg border border-indigo-400/20 bg-indigo-500/10 px-3 py-2 text-xs font-bold text-indigo-200 transition hover:bg-indigo-500 hover:text-white">{{ __('assets.ui.download_pdf') }}</a>
                                @elseif($term->isPending())
                                    <a href="{{ route('admin.assets.terms.sign', [$asset, $term]) }}" class="inline-flex rounded-lg border border-amber-400/20 bg-amber-500/10 px-3 py-2 text-xs font-bold text-amber-200 transition hover:bg-amber-500/20">{{ __('assets.ui.open_signature') }}</a>
                                    <form method="POST" action="{{ route('admin.assets.terms.cancel', [$asset, $term]) }}" onsubmit="return confirm('{{ __('assets.ui.cancel_pending_term') }}')">@csrf<button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold text-slate-500 transition hover:bg-rose-500/10 hover:text-rose-200">{{ __('assets.ui.cancel') }}</button></form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="xl:col-span-2 rounded-2xl border border-dashed border-white/10 px-5 py-8 text-center"><p class="text-sm font-semibold text-slate-300">{{ __('assets.ui.empty_terms') }}</p><p class="mt-1 text-xs text-slate-500">{{ __('assets.ui.empty_terms_hint') }}</p></div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
