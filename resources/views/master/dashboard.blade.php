<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-red-300/80">
                    <span class="inline-flex h-2 w-2 rounded-full bg-red-400 shadow-[0_0_10px_rgba(248,113,113,0.8)]"></span>
                    Plano de supervisão
                </div>
                <h2 class="mt-1 flex items-center gap-3 text-xl font-black text-white sm:text-2xl">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-500/25 bg-red-500/10 text-red-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Zm10-10V7a4 4 0 0 0-8 0v4h8Z" /></svg>
                    </span>
                    Centro de Segurança e Governança
                </h2>
            </div>
            <div class="flex items-center gap-2 rounded-xl border border-white/10 bg-slate-900/70 px-3 py-2 text-xs text-slate-400">
                <svg class="h-4 w-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-7-7v14" /></svg>
                Última leitura: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </x-slot>

    <main x-data="{ showResolveModal: false, selectedUrl: '', ticketSubject: '', ticketId: '' }" class="min-h-screen py-7 sm:py-9">
        <div class="mx-auto max-w-7xl space-y-7 px-4 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl border border-red-500/20 bg-gradient-to-br from-red-500/[0.12] via-slate-900/90 to-slate-950 p-6 shadow-2xl shadow-red-950/20 sm:p-8">
                <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-red-500/15 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-24 left-1/3 h-52 w-52 rounded-full bg-indigo-500/10 blur-3xl"></div>
                <div class="relative max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-red-300">Visão de alto nível</p>
                    <h1 class="mt-3 text-2xl font-black tracking-tight text-white sm:text-3xl">O Master supervisiona a operação — não executa a rotina do suporte.</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Este ambiente concentra incidentes críticos, riscos de prazo, conformidade de acessos, auditoria e integridade do sistema. Chamados operacionais, inventário e atendimento diário pertencem exclusivamente ao Admin.</p>
                </div>
            </section>

            <section aria-labelledby="risk-summary-title">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-red-300">Mapa de risco</p>
                        <h2 id="risk-summary-title" class="mt-1 text-lg font-bold text-white">Indicadores que exigem supervisão</h2>
                    </div>
                    <a href="{{ route('master.audit') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 transition hover:text-white">
                        Ver trilha de auditoria
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-red-500/20 bg-red-500/[0.07] p-5 shadow-lg shadow-red-950/10">
                        <div class="flex items-start justify-between gap-3"><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-red-300">Incidentes escalonados</p><span class="flex h-8 w-8 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 text-red-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3Z" /></svg></span></div>
                        <p class="mt-4 text-3xl font-black text-white">{{ $masterMetrics['escalated'] }}</p>
                        <p class="mt-1 text-xs text-red-200/70">Casos fora do fluxo normal</p>
                    </article>

                    <article class="rounded-2xl border border-amber-500/20 bg-amber-500/[0.07] p-5 shadow-lg shadow-amber-950/10">
                        <div class="flex items-start justify-between gap-3"><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-amber-300">SLA vencido</p><span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-500/20 bg-amber-500/10 text-amber-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></span></div>
                        <p class="mt-4 text-3xl font-black text-white">{{ $masterMetrics['overdue_sla'] }}</p>
                        <p class="mt-1 text-xs text-amber-200/70">Prazos que precisam de atenção</p>
                    </article>

                    <article class="rounded-2xl border border-indigo-500/20 bg-indigo-500/[0.07] p-5 shadow-lg shadow-indigo-950/10">
                        <div class="flex items-start justify-between gap-3"><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-indigo-300">Fila sem responsável</p><span class="flex h-8 w-8 items-center justify-center rounded-lg border border-indigo-500/20 bg-indigo-500/10 text-indigo-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m18-10a4 4 0 1 0-8 0 4 4 0 0 0 8 0ZM6 7a4 4 0 1 0-8 0 4 4 0 0 0 8 0Z" /></svg></span></div>
                        <p class="mt-4 text-3xl font-black text-white">{{ $masterMetrics['unassigned'] }}</p>
                        <p class="mt-1 text-xs text-indigo-200/70">Acompanhar com a liderança Admin</p>
                    </article>

                    <a href="{{ route('master.users.index') }}" class="group rounded-2xl border border-violet-500/20 bg-violet-500/[0.07] p-5 shadow-lg shadow-violet-950/10 transition hover:border-violet-400/40 hover:bg-violet-500/[0.11]">
                        <div class="flex items-start justify-between gap-3"><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-violet-300">Admins sem 2FA</p><span class="flex h-8 w-8 items-center justify-center rounded-lg border border-violet-500/20 bg-violet-500/10 text-violet-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.66 0 3-1.34 3-3S13.66 5 12 5 9 6.34 9 8s1.34 3 3 3Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z" /></svg></span></div>
                        <p class="mt-4 text-3xl font-black text-white">{{ $masterMetrics['admins_without_2fa'] }}</p>
                        <p class="mt-1 text-xs text-violet-200/70 group-hover:text-violet-100">Revisar identidades e acessos</p>
                    </a>
                </div>
            </section>

            <div class="grid gap-7 xl:grid-cols-12">
                <section id="incidents" class="xl:col-span-8" aria-labelledby="incidents-title">
                    <div class="overflow-hidden rounded-3xl border border-red-500/20 bg-slate-900/75 shadow-xl shadow-slate-950/25">
                        <div class="flex flex-col gap-4 border-b border-red-500/15 bg-red-500/[0.05] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                            <div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-red-300">Exceções críticas</p><h2 id="incidents-title" class="mt-1 text-lg font-bold text-white">Incidentes escalonados</h2><p class="mt-1 text-xs text-slate-400">Intervenções fora da rotina de suporte, sob responsabilidade do Master.</p></div>
                            <span class="inline-flex w-fit items-center gap-2 rounded-xl border border-red-500/20 bg-red-500/10 px-3 py-2 text-xs font-bold text-red-200"><span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>{{ $masterMetrics['escalated'] }} em análise</span>
                        </div>

                        <div class="p-4 sm:p-6">
                            @forelse($escalatedTickets as $ticket)
                                <article class="rounded-2xl border border-white/10 bg-slate-950/40 p-4 transition hover:border-red-500/30 sm:p-5">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2"><span class="rounded-md border border-red-500/20 bg-red-500/10 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-red-300">Incidente #{{ $ticket->id }}</span><span class="text-[11px] text-slate-500">Escalonado {{ $ticket->updated_at->diffForHumans() }}</span></div>
                                            <h3 class="mt-3 text-sm font-bold text-white sm:text-base">{{ $ticket->subject }}</h3>
                                            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-400"><span>Solicitante: <strong class="font-medium text-slate-200">{{ $ticket->user->name }}</strong></span><span>Responsável: <strong class="font-medium text-slate-200">{{ $ticket->assignee?->name ?? 'Não atribuído' }}</strong></span>@if($ticket->sla_due_at)<span class="{{ $ticket->sla_status === 'danger' ? 'text-red-300' : 'text-amber-300' }}">SLA: {{ $ticket->sla_remaining }}</span>@endif</div>
                                        </div>
                                        <button @click="showResolveModal = true; selectedUrl = '{{ route('master.tickets.resolve', $ticket) }}'; ticketSubject = @js($ticket->subject); ticketId = '{{ $ticket->id }}'" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-2.5 text-xs font-bold text-red-100 transition hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-400">
                                            Registrar solução
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg>
                                        </button>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-2xl border border-dashed border-emerald-500/20 bg-emerald-500/[0.04] px-5 py-10 text-center"><div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-300"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" /></svg></div><h3 class="mt-4 text-sm font-bold text-emerald-100">Nenhum incidente crítico pendente</h3><p class="mt-1 text-xs text-slate-500">O fluxo operacional está dentro da governança esperada.</p></div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <aside class="space-y-6 xl:col-span-4" aria-label="Atalhos de governança e sinais de auditoria">
                    <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/75 shadow-xl shadow-slate-950/25">
                        <div class="border-b border-white/5 p-5"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-300">Controles estratégicos</p><h2 class="mt-1 text-base font-bold text-white">Governança do ambiente</h2></div>
                        <div class="space-y-2 p-3">
                            <a href="{{ route('master.health') }}" class="group flex items-center gap-3 rounded-2xl p-3 transition hover:bg-white/5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16M5 16l4-5 3 3 5-7 2 2" /></svg></span><span class="min-w-0 flex-1"><span class="block text-xs font-bold text-slate-200">Saúde do sistema</span><span class="mt-0.5 block text-[11px] text-slate-500">Infraestrutura, fila, cache e storage</span></span><svg class="h-4 w-4 text-slate-600 transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a>
                            <a href="{{ route('master.audit') }}" class="group flex items-center gap-3 rounded-2xl p-3 transition hover:bg-white/5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-indigo-500/20 bg-indigo-500/10 text-indigo-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 4H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h7l5 5v9a2 2 0 0 1-2 2Z" /></svg></span><span class="min-w-0 flex-1"><span class="block text-xs font-bold text-slate-200">Auditoria de ações</span><span class="mt-0.5 block text-[11px] text-slate-500">Rastreabilidade de alterações sensíveis</span></span><svg class="h-4 w-4 text-slate-600 transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a>
                            <a href="{{ route('master.users.index') }}" class="group flex items-center gap-3 rounded-2xl p-3 transition hover:bg-white/5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-violet-500/20 bg-violet-500/10 text-violet-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m14-10a4 4 0 1 0-8 0 4 4 0 0 0 8 0Z" /></svg></span><span class="min-w-0 flex-1"><span class="block text-xs font-bold text-slate-200">Identidades e acessos</span><span class="mt-0.5 block text-[11px] text-slate-500">Perfis privilegiados e conformidade 2FA</span></span><svg class="h-4 w-4 text-slate-600 transition group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a>
                        </div>
                    </section>

                    <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/75 shadow-xl shadow-slate-950/25">
                        <div class="flex items-center justify-between border-b border-white/5 p-5"><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-amber-300">Sinais recentes</p><h2 class="mt-1 text-base font-bold text-white">Alertas de auditoria</h2></div><span class="rounded-lg border border-amber-500/20 bg-amber-500/10 px-2 py-1 text-[10px] font-bold text-amber-200">{{ $masterMetrics['recent_warnings'] }}</span></div>
                        <div class="divide-y divide-white/5">
                            @forelse($recentSecurityEvents as $event)
                                <a href="{{ route('master.audit') }}" class="block p-4 transition hover:bg-white/[0.03]"><div class="flex items-start gap-3"><span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $event->level === 'DANGER' ? 'bg-red-400' : 'bg-amber-400' }}"></span><div class="min-w-0 flex-1"><p class="truncate text-xs font-semibold text-slate-200">{{ $event->action }}</p><p class="mt-1 line-clamp-2 text-[11px] leading-5 text-slate-500">{{ $event->description ?? 'Evento sem descrição detalhada.' }}</p><p class="mt-1.5 text-[10px] text-slate-600">{{ $event->created_at->diffForHumans() }}{{ $event->user ? ' · ' . $event->user->name : '' }}</p></div></div></a>
                            @empty
                                <p class="p-5 text-center text-xs text-slate-500">Nenhum alerta de auditoria recente.</p>
                            @endforelse
                        </div>
                    </section>
                </aside>
            </div>
        </div>

        <div x-show="showResolveModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/85 p-4 backdrop-blur-sm" x-transition.opacity @keydown.escape.window="showResolveModal = false" role="dialog" aria-modal="true" aria-labelledby="resolve-incident-title">
            <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-red-500/30 bg-slate-900 shadow-2xl shadow-red-950/40" @click.outside="showResolveModal = false">
                <div class="h-1.5 bg-gradient-to-r from-red-500 via-rose-500 to-amber-400"></div>
                <div class="p-6 sm:p-7"><p class="text-[10px] font-bold uppercase tracking-[0.2em] text-red-300">Decisão de supervisão</p><h2 id="resolve-incident-title" class="mt-2 text-xl font-black text-white">Registrar resolução do incidente</h2><p class="mt-2 text-sm leading-6 text-slate-400">Você encerrará o incidente <strong class="font-semibold text-slate-100" x-text="'#' + ticketId"></strong> e a solução será registrada no histórico visível ao solicitante.</p>
                    <form :action="selectedUrl" method="POST" class="mt-6 space-y-5">@csrf<div><label for="master-solution" class="mb-2 block text-xs font-bold text-slate-300">Solução técnica aplicada</label><textarea id="master-solution" name="solution" rows="4" required maxlength="4000" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 p-3 text-sm text-slate-200 outline-none transition placeholder:text-slate-600 focus:border-red-400/50 focus:ring-2 focus:ring-red-500/15" placeholder="Descreva a intervenção para o cliente e para auditoria."></textarea></div><div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><button type="button" @click="showResolveModal = false" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">Cancelar</button><button type="submit" class="rounded-xl bg-red-500 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-300">Confirmar resolução</button></div></form>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
