<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-400">Inventário interno</p>
                <h2 class="mt-1 text-xl font-bold text-white">Ficha do Ativo</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.assets.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition">
                    Voltar ao inventário
                </a>
                <a href="{{ route('admin.assets.edit', $asset) }}" class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-bold text-white hover:bg-cyan-500 transition">
                    Manutenção e edição
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
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Patrimônio #{{ $asset->tag }}</p>
                                <h1 class="mt-1 text-2xl font-black text-white sm:text-3xl">{{ $asset->name }}</h1>
                                <p class="mt-1 text-sm text-slate-400">{{ collect([$asset->brand, $asset->model, $asset->type])->filter()->join(' · ') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            <div class="rounded-2xl border border-white/5 bg-slate-950/40 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Status</p>
                                <p class="mt-2 text-sm font-bold text-{{ $asset->getStatusColor() }}-400">{{ $asset->getStatusLabel() }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/5 bg-slate-950/40 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Número de série</p>
                                <p class="mt-2 truncate font-mono text-sm text-slate-200">{{ $asset->serial_number ?: 'Não informado' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/5 bg-slate-950/40 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Responsável</p>
                                <p class="mt-2 truncate text-sm font-semibold text-slate-200">{{ $asset->user?->name ?: 'Disponível em estoque' }}</p>
                            </div>
                        </div>

                        @if ($asset->notes)
                            <div class="mt-5 rounded-2xl border border-white/5 bg-white/[0.03] p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Observações</p>
                                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-300">{{ $asset->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <aside class="border-t border-white/10 bg-slate-950/50 p-7 lg:border-l lg:border-t-0" x-data>
                        <div class="rounded-2xl bg-white p-4 shadow-xl">
                            <img src="{{ route('admin.assets.qr-code', $asset) }}" class="mx-auto h-56 w-56" alt="QR Code interno do ativo {{ $asset->tag }}">
                        </div>
                        <p class="mt-5 text-center text-xs font-bold uppercase tracking-[0.16em] text-slate-300">Etiqueta interna · {{ $asset->tag }}</p>
                        <p class="mt-2 text-center text-xs leading-relaxed text-slate-500">Ao escanear, o técnico autenticado abre esta ficha do ativo.</p>
                        <a href="{{ route('admin.assets.qr-label', $asset) }}" target="_blank" rel="noopener" class="mt-5 block w-full rounded-xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-3 text-center text-sm font-bold text-cyan-300 hover:bg-cyan-400/20 transition">
                            Abrir etiqueta para impressão
                        </a>
                    </aside>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-400">Rastreabilidade</p>
                            <h3 class="mt-1 text-lg font-bold text-white">Histórico de movimentações</h3>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-slate-400">{{ $asset->history->count() }} registros</span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($asset->history as $history)
                            <article class="rounded-2xl border border-white/5 bg-slate-950/30 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-sm font-semibold text-slate-200">{{ $history->description }}</p>
                                    <time class="shrink-0 text-xs text-slate-500">{{ $history->created_at->format('d/m/Y H:i') }}</time>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Registrado por {{ $history->user?->name ?: 'Sistema' }}</p>
                            </article>
                        @empty
                            <p class="rounded-2xl border border-dashed border-white/10 p-5 text-center text-sm text-slate-500">Ainda não há movimentações registradas para este ativo.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-400">Suporte</p>
                            <h3 class="mt-1 text-lg font-bold text-white">Últimos chamados vinculados</h3>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-slate-400">{{ $asset->tickets->count() }} exibidos</span>
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
                            <p class="rounded-2xl border border-dashed border-white/10 p-5 text-center text-sm text-slate-500">Nenhum chamado foi vinculado a este ativo.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
