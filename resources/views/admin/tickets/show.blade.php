<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-3">
                <a href="{{ route('admin.tickets.index') }}" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-slate-900/80 text-slate-400 transition hover:border-indigo-400/40 hover:bg-indigo-500/10 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Voltar para a fila de chamados">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-md border border-indigo-400/20 bg-indigo-500/10 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.14em] text-indigo-300">{{ $ticket->category->name ?? $ticket->category ?? 'Suporte' }}</span>
                        <span class="text-[11px] text-slate-500">Criado {{ $ticket->created_at->diffForHumans() }}</span>
                        @if($ticket->sla_due_at && !in_array($ticket->status, [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED]))
                            <div class="flex items-center gap-1.5 rounded-md border px-2 py-1 text-[10px] font-bold uppercase tracking-wider
                                {{ $ticket->sla_status === 'danger' ? 'border-rose-500/30 bg-rose-500/10 text-rose-400' : 
                                   ($ticket->sla_status === 'warning' ? 'border-amber-500/30 bg-amber-500/10 text-amber-400' : 'border-emerald-500/30 bg-emerald-500/10 text-emerald-400') }}">
                                <svg class="h-3 w-3 {{ $ticket->sla_status === 'danger' ? 'animate-pulse' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                <span>SLA: {{ $ticket->sla_remaining }}</span>
                            </div>
                        @endif
                    </div>
                    <h2 class="mt-1 truncate text-lg font-bold text-white sm:text-xl">Chamado #{{ $ticket->id }}</h2>
                </div>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                @if($ticket->is_escalated)
                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-rose-400/25 bg-rose-500/10 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-rose-300"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3Z" /></svg>Escalonado</span>
                @endif
                <x-ticket-status :status="$ticket->status" />
            </div>
        </div>
    </x-slot>

    <main x-data="{
            replyMode: 'public',
            replyMessage: '',
            messages: @js($ticket->messages),
            typingUser: null,
            typingTimeout: null,
            init() { this.initEcho() },
            initEcho() {
                if (!window.Echo) return;
                window.Echo.private(`ticket.{{ $ticket->id }}`)
                    .listen('.message.sent', (event) => {
                        if (!this.messages.find((message) => message.id === event.message.id)) {
                            this.messages.push(event.message);
                            this.$nextTick(() => document.getElementById('admin-ticket-timeline')?.scrollIntoView({ behavior: 'smooth', block: 'end' }));
                        }
                    })
                    .listenForWhisper('typing', (event) => {
                        this.typingUser = event.name;
                        window.clearTimeout(this.typingTimeout);
                        this.typingTimeout = window.setTimeout(() => this.typingUser = null, 3000);
                    });
            },
            attachmentUrl(attachment) { return attachment.url || ('/storage/' + (attachment.file_path || '')); },
            attachmentName(attachment) { return attachment.name || attachment.file_name || 'Anexo'; },
            isImageAttachment(attachment) { return (attachment.mime_type || '').startsWith('image/') || /\.(jpe?g|png|webp)$/i.test(this.attachmentName(attachment)); },
            isPdfAttachment(attachment) { return attachment.mime_type === 'application/pdf' || /\.pdf$/i.test(this.attachmentName(attachment)); },
            broadcastTyping() {
                if (window.Echo) window.Echo.private(`ticket.{{ $ticket->id }}`).whisper('typing', { name: '{{ auth()->user()->name }}' });
            }
        }" x-init="init()" class="min-h-screen pb-44 pt-6 sm:pt-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-12 lg:gap-7">
                <section class="space-y-6 lg:col-span-8" aria-label="Detalhes e histórico técnico">
                    <article class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900/75 shadow-2xl shadow-slate-950/25 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-indigo-500/12 blur-3xl"></div>
                        <header class="relative flex flex-col gap-4 border-b border-white/5 bg-white/[0.025] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                            <div class="flex min-w-0 items-center gap-3.5">
                                <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-slate-800 text-base font-black text-white shadow-inner">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($ticket->user->name, 0, 1)) }}
                                    <span class="absolute -bottom-1.5 -right-1.5 rounded-md border border-indigo-400/25 bg-slate-950 px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider text-indigo-200">Cliente</span>
                                </div>
                                <div class="min-w-0"><h1 class="truncate text-base font-bold text-white">{{ $ticket->user->name }}</h1><div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500"><span class="truncate">{{ $ticket->user->email }}</span><span class="hidden h-1 w-1 rounded-full bg-slate-700 sm:block"></span><span>{{ $clientHistory['total_tickets'] ?? 0 }} chamado(s)</span></div></div>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-slate-950/35 px-3 py-2 text-xs text-slate-400"><span class="text-slate-500">Atualizado</span> {{ $ticket->updated_at->diffForHumans() }}</div>
                        </header>
                        <div class="relative p-5 sm:p-7">
                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-indigo-300">Solicitação</p>
                            <h2 class="mt-2 text-xl font-black leading-snug text-white sm:text-2xl">{{ $ticket->subject }}</h2>
                            <div class="mt-5 rounded-2xl border border-white/5 bg-slate-950/50 p-4 text-sm leading-7 text-slate-300 shadow-inner shadow-black/10 sm:p-5">{!! nl2br(e($ticket->description ?? $ticket->messages->first()?->message)) !!}</div>
                            @if($ticket->messages->isNotEmpty() && $ticket->messages->first()->attachments->isNotEmpty())
                                <div class="mt-6 border-t border-white/5 pt-5"><div class="mb-3 flex items-center gap-2"><svg class="h-4 w-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13.5 10.5-3 3m0 0-3-3m3 3v-9m5.5 5.5v6A2.5 2.5 0 0 1 13.5 19h-7A2.5 2.5 0 0 1 4 16.5v-6" /></svg><h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Evidências enviadas</h3></div><div class="flex flex-wrap gap-3">@foreach($ticket->messages->first()->attachments as $attachment)<x-ticket-attachment-card :attachment="$attachment" compact />@endforeach</div></div>
                            @endif
                        </div>
                    </article>

                    @if($ticket->checklists->isNotEmpty())
                        <section class="overflow-hidden rounded-3xl border border-indigo-400/20 bg-slate-900/70 shadow-xl shadow-slate-950/20 backdrop-blur-xl" x-data="{
                            async toggleItem(id) {
                                const response = await fetch(`{{ route('admin.tickets.checklist.toggle', [$ticket, ':id']) }}`.replace(':id', id), { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' } });
                                if (!response.ok) window.location.reload();
                            }
                        }">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-indigo-400/15 bg-indigo-500/[0.07] px-5 py-4 sm:px-6"><div class="flex items-center gap-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/15 text-indigo-200"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2 2h2a2 2 0 0 1 2-2M9 5a2 2 0 0 0-2 2m0 9 2 2 4-4" /></svg></span><div><h2 class="text-sm font-bold text-white">Checklist de atendimento</h2><p class="text-[11px] text-slate-500">Acompanhe as etapas necessárias</p></div></div><span class="rounded-md border border-indigo-400/15 bg-indigo-400/10 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-indigo-200">{{ $ticket->checklists->where('is_completed', true)->count() }}/{{ $ticket->checklists->count() }} concluídos</span></div>
                            <div class="space-y-1 p-3 sm:p-4">@foreach($ticket->checklists as $item)<label class="flex cursor-pointer items-start gap-3 rounded-xl px-3 py-3 transition hover:bg-white/[0.03]"><input type="checkbox" @change="toggleItem({{ $item->id }})" {{ $item->is_completed ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-slate-600 bg-slate-950 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900"><span class="min-w-0 flex-1"><span class="block text-sm {{ $item->is_completed ? 'text-slate-500 line-through' : 'text-slate-200' }}">{{ $item->task }}</span>@if($item->is_completed && $item->completedBy)<span class="mt-1 block text-[10px] text-slate-600">Concluído por {{ $item->completedBy->name }} em {{ $item->completed_at->format('d/m H:i') }}</span>@endif</span></label>@endforeach</div>
                        </section>
                    @endif

                    <section id="admin-ticket-timeline" class="rounded-3xl border border-white/10 bg-slate-900/55 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-7" aria-labelledby="admin-ticket-timeline-title">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/5 pb-5"><div><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-indigo-300">Linha do tempo</p><h2 id="admin-ticket-timeline-title" class="mt-1 text-lg font-bold text-white">Comunicação e notas técnicas</h2></div><div class="flex items-center gap-2 text-[11px] text-slate-500"><span class="h-2 w-2 rounded-full bg-indigo-400"></span>Público <span class="ml-2 h-2 w-2 rounded-full bg-amber-400"></span>Interno</div></div>
                        <div class="relative mt-6 space-y-5 before:absolute before:bottom-4 before:left-5 before:top-4 before:w-px before:bg-white/10 sm:before:left-6">
                            <template x-for="(message, index) in messages" :key="message.id">
                                <article x-show="index > 0" class="relative flex gap-3 sm:gap-4" :class="message.user_id == {{ auth()->id() }} && !message.is_internal ? 'sm:flex-row-reverse' : ''">
                                    <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border text-xs font-black shadow-lg ring-4 ring-slate-900" :class="message.is_internal ? 'border-amber-400/35 bg-amber-500/15 text-amber-200' : (message.user_id == {{ auth()->id() }} ? 'border-indigo-400/40 bg-indigo-500 text-white' : 'border-white/10 bg-slate-800 text-cyan-200')"><span x-text="message.is_internal ? '⌁' : (message.user?.name || 'S').charAt(0)"></span></div>
                                    <div class="min-w-0 flex-1" :class="message.user_id == {{ auth()->id() }} && !message.is_internal ? 'sm:text-right' : ''">
                                        <div class="inline-block w-full max-w-3xl rounded-2xl border p-4 text-left shadow-lg sm:p-5" :class="message.is_internal ? 'border-amber-400/20 bg-amber-950/30 sm:rounded-tl-md' : (message.user_id == {{ auth()->id() }} ? 'border-indigo-400/20 bg-indigo-500/10 sm:rounded-tr-md' : 'border-white/10 bg-slate-950/45 sm:rounded-tl-md')">
                                            <div class="mb-3 flex items-center justify-between gap-3" :class="message.user_id == {{ auth()->id() }} && !message.is_internal ? 'sm:flex-row-reverse' : ''"><div class="flex min-w-0 items-center gap-2"><span class="truncate text-xs font-bold" :class="message.is_internal ? 'text-amber-200' : 'text-white'" x-text="message.user?.name || 'Sistema'"></span><template x-if="message.is_internal"><span class="rounded-md border border-amber-400/20 bg-amber-400/10 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-amber-200">Nota interna</span></template><template x-if="!message.is_internal && (message.user?.role === 'admin' || message.user?.role === 'master')"><span class="rounded-md border border-cyan-400/20 bg-cyan-400/10 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-cyan-200">Equipe</span></template></div><time class="shrink-0 text-[10px] text-slate-500" x-text="new Date(message.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' })"></time></div>
                                            <div class="text-sm leading-6 text-slate-300" x-html="message.message.replace(/\n/g, '<br>')"></div>
                                            <template x-if="message.is_internal && message.time_spent"><div class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-amber-400/15 bg-amber-400/[0.06] px-2 py-1 text-[10px] font-semibold text-amber-200"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg><span x-text="message.time_spent + ' min registrados'"></span></div></template>
                                            <template x-if="message.attachments && message.attachments.length"><div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/5 pt-3 sm:grid-cols-3"><template x-for="attachment in message.attachments" :key="attachment.id"><a :href="attachmentUrl(attachment)" target="_blank" rel="noopener" class="overflow-hidden rounded-xl border border-white/10 bg-slate-950/60 transition hover:border-cyan-400/45"><template x-if="isImageAttachment(attachment)"><img :src="attachmentUrl(attachment)" :alt="attachmentName(attachment)" class="h-20 w-full object-cover" /></template><template x-if="isPdfAttachment(attachment)"><iframe :src="attachmentUrl(attachment) + '#toolbar=0'" :title="attachmentName(attachment)" class="h-20 w-full bg-white" loading="lazy"></iframe></template><template x-if="!isImageAttachment(attachment) && !isPdfAttachment(attachment)"><div class="flex h-20 items-center justify-center text-2xl">📎</div></template><div class="flex items-center gap-1 p-2"><span class="min-w-0 flex-1 truncate text-[10px] text-slate-300" x-text="attachmentName(attachment)"></span><span class="text-cyan-300">↗</span></div></a></template></div></template>
                                        </div>
                                    </div>
                                </article>
                            </template>
                            <p x-show="messages.length <= 1" class="rounded-xl border border-dashed border-white/10 bg-slate-950/30 px-4 py-5 text-center text-sm text-slate-500">Ainda não há interações além da abertura deste chamado.</p>
                        </div>
                    </section>

                    @if($ticket->rating)
                        <section class="rounded-3xl border border-emerald-400/20 bg-emerald-500/[0.06] p-5 sm:p-6"><div class="flex gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">★</div><div><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-emerald-300">Avaliação do cliente</p><div class="mt-1 flex text-lg text-amber-300">@foreach(range(1, 5) as $i)<span class="{{ $i <= $ticket->rating ? '' : 'text-slate-700' }}">★</span>@endforeach</div>@if($ticket->rating_comment)<p class="mt-2 text-sm italic text-slate-400">“{{ $ticket->rating_comment }}”</p>@endif</div></div></section>
                    @endif
                </section>

                <aside class="space-y-5 lg:col-span-4" aria-label="Painel de operação do chamado">
                    <section class="rounded-3xl border border-white/10 bg-slate-900/75 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6 lg:sticky lg:top-6">
                        <div class="flex items-center justify-between border-b border-white/5 pb-4"><div><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-indigo-300">Operação</p><h2 class="mt-1 text-sm font-bold text-white">Controle do chamado</h2></div><span class="rounded-lg border border-white/10 bg-slate-950/35 px-2 py-1 text-[10px] font-mono text-slate-500">#{{ $ticket->id }}</span></div>
                        <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="mt-5 space-y-3">@csrf @method('PATCH')<div><label for="ticket-status" class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</label><select id="ticket-status" name="status" class="block w-full rounded-xl border border-white/10 bg-slate-950/70 px-3 py-2.5 text-sm text-slate-200 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">@foreach(\App\Enums\TicketStatus::cases() as $status)<option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }} class="bg-slate-900">{{ $status->label() }}</option>@endforeach</select></div><button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-500/15 transition hover:bg-indigo-400">Salvar status</button></form>
                        <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="mt-5 border-t border-white/5 pt-5">@csrf @method('PATCH')<label for="ticket-assignee" class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Responsável</label><div class="flex gap-2"><select id="ticket-assignee" name="assigned_to" class="min-w-0 flex-1 rounded-xl border border-white/10 bg-slate-950/70 px-3 py-2.5 text-xs text-slate-200 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"><option value="">Não atribuído</option>@foreach($admins ?? [] as $admin)<option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }} class="bg-slate-900">{{ $admin->name }}</option>@endforeach</select><button type="submit" class="rounded-xl border border-white/10 bg-white/5 px-3 text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Salvar responsável"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" /></svg></button></div></form>
                        <div class="mt-5 grid gap-2 border-t border-white/5 pt-5 sm:grid-cols-2"><a href="{{ route('admin.visits.create', $ticket) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-cyan-400/20 bg-cyan-500/[0.07] px-3 py-2.5 text-xs font-bold text-cyan-200 transition hover:bg-cyan-500/15"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" /></svg>Visita</a><div x-data="{ open: false }" class="relative"><button type="button" @click="open = !open" class="inline-flex w-full items-center justify-center rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-xs font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">Mesclar</button><div x-show="open" x-cloak @click.outside="open = false" class="absolute bottom-full right-0 z-30 mb-2 w-60 rounded-2xl border border-white/10 bg-slate-900 p-3 shadow-2xl"><p class="mb-2 text-[11px] text-slate-400">Informe o chamado de destino.</p><form action="{{ route('admin.tickets.merge', $ticket) }}" method="POST" onsubmit="return confirm('Esta ação fechará o chamado atual.');">@csrf<input type="number" name="target_ticket_id" required class="block w-full rounded-lg border border-white/10 bg-slate-950 px-2.5 py-2 text-xs text-white focus:border-indigo-400 focus:outline-none" placeholder="ID do chamado"><button type="submit" class="mt-2 w-full rounded-lg bg-indigo-500 px-3 py-2 text-xs font-bold text-white hover:bg-indigo-400">Confirmar mesclagem</button></form></div></div></div>
                        @if(!$ticket->is_escalated)<form action="{{ route('admin.tickets.escalate', $ticket) }}" method="POST" class="mt-2" onsubmit="return confirm('Escalar este chamado para Segurança?');">@csrf<button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-rose-400/20 bg-rose-500/[0.07] px-3 py-2.5 text-xs font-bold text-rose-300 transition hover:bg-rose-500/15"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3Z" /></svg>Escalar para Segurança</button></form>@endif
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6" x-data="{ showTagModal: false }">
                        <div class="flex items-center justify-between border-b border-white/5 pb-4"><div><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Classificação</p><h2 class="mt-1 text-sm font-bold text-white">Tags do chamado</h2></div><button type="button" @click="showTagModal = true" class="rounded-lg border border-indigo-400/20 bg-indigo-500/10 px-2.5 py-1.5 text-[11px] font-bold text-indigo-200 transition hover:bg-indigo-500/20">Adicionar</button></div>
                        <div class="mt-4 flex flex-wrap gap-2">@forelse($ticket->tags ?? [] as $tag)<span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-[11px] font-semibold" style="background-color: {{ $tag->color }}18; color: {{ $tag->color }}; border-color: {{ $tag->color }}55;">{{ $tag->name }}<form action="{{ route('admin.tickets.tags.detach', [$ticket, $tag]) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="opacity-70 transition hover:opacity-100" aria-label="Remover tag {{ $tag->name }}">×</button></form></span>@empty<span class="text-xs text-slate-500">Nenhuma tag atribuída.</span>@endforelse</div>
                        <div x-show="showTagModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm" @keydown.escape.window="showTagModal = false"><div @click.stop class="w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 p-5 shadow-2xl sm:p-6"><div class="flex items-center justify-between"><h3 class="text-base font-bold text-white">Gerenciar tags</h3><button type="button" @click="showTagModal = false" class="rounded-lg p-1 text-slate-500 hover:bg-white/5 hover:text-white" aria-label="Fechar">×</button></div><form action="{{ route('admin.tickets.tags.attach', $ticket) }}" method="POST">@csrf<div class="mt-5 max-h-64 space-y-1 overflow-y-auto">@foreach($tags ?? [] as $availableTag)<label class="flex cursor-pointer items-center gap-3 rounded-xl p-2.5 hover:bg-white/5"><input type="checkbox" name="tags[]" value="{{ $availableTag->id }}" {{ $ticket->tags->contains($availableTag->id) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/20 bg-slate-950 text-indigo-500 focus:ring-indigo-500"><span class="h-3 w-3 rounded-full" style="background-color: {{ $availableTag->color }}"></span><span class="text-sm text-slate-300">{{ $availableTag->name }}</span></label>@endforeach</div><div class="mt-5 flex justify-end gap-2"><button type="button" @click="showTagModal = false" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-400 hover:bg-white/5 hover:text-white">Cancelar</button><button type="submit" class="rounded-xl bg-indigo-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-indigo-400">Salvar tags</button></div></form></div></div>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6">
                        <div class="border-b border-white/5 pb-4"><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Inventário</p><h2 class="mt-1 text-sm font-bold text-white">Equipamentos do cliente</h2></div>
                        <div class="mt-4 space-y-2.5">@forelse($ticket->user->assets as $asset)<div class="rounded-2xl border border-white/5 bg-slate-950/35 p-3.5"><div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-wider text-indigo-300">{{ $asset->type }}</p><p class="mt-1 truncate text-sm font-bold text-white">{{ $asset->name }}</p></div><span class="shrink-0 rounded-md px-1.5 py-1 text-[9px] font-bold" style="color: #cbd5e1; background: rgba(255,255,255,.05)">{{ $asset->getStatusLabel() }}</span></div><div class="mt-2 text-[11px] text-slate-500">Patrimônio <span class="font-medium text-slate-300">{{ $asset->tag }}</span>@if($asset->serial_number)<span class="mx-1 text-slate-700">•</span>S/N <span class="font-medium text-slate-300">{{ $asset->serial_number }}</span>@endif</div></div>@empty<div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/25 p-4 text-center"><p class="text-xs text-slate-500">Nenhum equipamento vinculado.</p><a href="{{ route('admin.assets.create', ['user_id' => $ticket->user_id]) }}" class="mt-2 inline-block text-xs font-bold text-indigo-300 hover:text-indigo-200">Vincular equipamento</a></div>@endforelse</div>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6"><div class="border-b border-white/5 pb-4"><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Contexto</p><h2 class="mt-1 text-sm font-bold text-white">Histórico rápido</h2></div><dl class="mt-4 space-y-2 text-xs"><div class="flex justify-between gap-4"><dt class="text-slate-500">Criado em</dt><dd class="text-right text-slate-300">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Última mensagem</dt><dd class="text-right text-slate-300">{{ $ticket->updated_at->diffForHumans() }}</dd></div>@if(isset($clientHistory['last_ticket']))<div class="mt-3 border-t border-white/5 pt-3"><dt class="mb-2 text-slate-500">Chamado anterior</dt><dd><a href="{{ route('admin.tickets.show', $clientHistory['last_ticket']) }}" class="flex items-center justify-between gap-2 rounded-xl border border-white/5 bg-slate-950/35 px-3 py-2.5 text-indigo-200 transition hover:border-indigo-400/25 hover:bg-indigo-500/[0.06]"><span class="font-mono text-[11px]">#{{ $clientHistory['last_ticket']->id }}</span><span class="min-w-0 flex-1 truncate text-right text-xs">{{ \Illuminate\Support\Str::limit($clientHistory['last_ticket']->subject, 24) }}</span></a></dd></div>@endif</dl></section>
                </aside>
            </div>
        </div>

        @if($ticket->status !== \App\Enums\TicketStatus::CLOSED)
            <div class="pointer-events-none fixed inset-x-0 bottom-0 z-40 px-3 pb-3 sm:px-6 sm:pb-5">
                <div class="pointer-events-auto mx-auto max-w-5xl">
                    <div class="flex items-end gap-1 pl-3"><button type="button" @click="replyMode = 'public'" class="rounded-t-xl border-x border-t px-3 py-2 text-[11px] font-bold transition" :class="replyMode === 'public' ? 'border-white/10 bg-slate-900 text-white' : 'border-transparent bg-slate-950/60 text-slate-500 hover:text-slate-300'">Resposta pública</button><button type="button" @click="replyMode = 'internal'" class="inline-flex items-center gap-1.5 rounded-t-xl border-x border-t px-3 py-2 text-[11px] font-bold transition" :class="replyMode === 'internal' ? 'border-amber-400/30 bg-amber-950 text-amber-200' : 'border-transparent bg-slate-950/60 text-slate-500 hover:text-amber-200'"><svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2Zm10-10V7a4 4 0 0 0-8 0v4" /></svg>Nota interna</button></div>
                    <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data" 
                          x-data="{
                            ...attachmentUploader(),
                            wikiQuery: '',
                            wikiResults: [],
                            showWiki: false,
                            async searchWiki() {
                                if (this.wikiQuery.length < 2) { this.wikiResults = []; return; }
                                const res = await fetch(`{{ route('admin.wiki.search') }}?q=${this.wikiQuery}`);
                                this.wikiResults = await res.json();
                            },
                            insertWiki(article) {
                                const link = `\n📖 *Veja mais detalhes aqui:* [${article.title}](${article.url})\n`;
                                this.replyMessage += link;
                                this.wikiQuery = '';
                                this.wikiResults = [];
                                this.showWiki = false;
                            }
                          }"
                          class="overflow-hidden rounded-2xl border bg-slate-900/95 shadow-2xl shadow-slate-950/60 backdrop-blur-2xl transition" :class="replyMode === 'internal' ? 'border-amber-400/30 shadow-amber-950/20' : 'border-white/10'">
                        @csrf
                        <input type="hidden" name="is_internal" :value="replyMode === 'internal' ? 1 : 0">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-2 border-b border-white/5 bg-slate-950/35 px-3 py-2.5">
                            <div class="relative">
                                <select @change="replyMessage += $event.target.value ? $event.target.value + '\n' : ''; $event.target.value = ''" class="cursor-pointer appearance-none bg-transparent py-1 pr-5 text-xs font-semibold text-slate-300 focus:outline-none">
                                    <option value="" class="bg-slate-900 text-slate-500">Inserir resposta pronta</option>
                                    @if(isset($cannedResponses))
                                        @foreach($cannedResponses->groupBy('category') as $category => $items)
                                            <optgroup label="{{ $category ?: 'Geral' }}" class="bg-slate-900 text-indigo-400 font-bold">
                                                @foreach($items as $canned)
                                                    <option value="{{ $canned->content }}" class="bg-slate-900 text-white font-normal">{{ $canned->title }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endif
                                </select>
                                <svg class="pointer-events-none absolute right-0 top-1/2 h-3 w-3 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" /></svg>
                            </div>
                            
                            <div class="h-4 w-px bg-white/10"></div>

                            <div class="relative flex-1 max-w-xs">
                                <input type="text" x-model="wikiQuery" @input.debounce.300ms="searchWiki()" @focus="showWiki = true"
                                       class="bg-transparent border-none focus:ring-0 text-[11px] text-slate-300 w-full p-0 placeholder-slate-600"
                                       placeholder="Buscar ajuda na Wiki...">
                                
                                <div x-show="showWiki && wikiResults.length > 0" @click.away="showWiki = false" x-cloak
                                     class="absolute bottom-full left-0 mb-2 w-72 bg-slate-800 border border-white/10 rounded-xl shadow-2xl z-50 overflow-hidden">
                                    <div class="p-2 bg-slate-900/50 border-b border-white/5 text-[9px] font-bold text-slate-500 uppercase tracking-widest">Sugestões da Wiki</div>
                                    <template x-for="article in wikiResults" :key="article.id">
                                        <button type="button" @click="insertWiki(article)"
                                                class="w-full text-left p-3 hover:bg-indigo-600 group transition flex items-center justify-between gap-2 border-b border-white/5 last:border-0">
                                            <div class="min-w-0">
                                                <div class="text-[8px] font-bold text-indigo-400 group-hover:text-indigo-200 uppercase" x-text="article.category"></div>
                                                <div class="text-xs text-white font-medium truncate" x-text="article.title"></div>
                                            </div>
                                            <svg class="w-3 h-3 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="h-4 w-px bg-white/10"></div><div x-show="replyMode === 'internal'" x-cloak class="flex items-center gap-2"><label for="time-spent" class="text-[10px] font-bold uppercase tracking-wider text-amber-200">Tempo</label><input id="time-spent" type="number" name="time_spent" min="0" placeholder="min" class="w-16 rounded-lg border border-amber-400/20 bg-amber-950/40 px-2 py-1 text-xs text-white placeholder:text-amber-200/30 focus:border-amber-400 focus:outline-none"></div><span x-show="replyMode === 'internal'" x-cloak class="ml-auto rounded-md border border-amber-400/15 bg-amber-400/[0.07] px-2 py-1 text-[10px] font-semibold text-amber-200">Visível apenas para a equipe</span></div>
                        <x-validation-errors class="mx-4 mt-3" />
                        <div x-show="errors.length" x-cloak class="mx-4 mt-3 rounded-xl border border-rose-500/20 bg-rose-500/10 p-2.5 text-xs text-rose-200" role="alert"><template x-for="error in errors" :key="error"><p x-text="error"></p></template></div>
                        <div x-show="files.length" x-cloak class="grid grid-cols-2 gap-2 border-b border-white/5 p-3 sm:grid-cols-5"><template x-for="(item, index) in files" :key="item.file.name + item.file.size"><article class="relative overflow-hidden rounded-xl border border-white/10 bg-slate-950/60"><template x-if="item.isImage"><img :src="item.preview" :alt="item.file.name" class="h-20 w-full object-cover" /></template><template x-if="item.isPdf"><iframe :src="item.preview" :title="'Prévia de ' + item.file.name" class="h-20 w-full bg-white" loading="lazy"></iframe></template><template x-if="!item.isImage && !item.isPdf"><div class="flex h-20 items-center justify-center text-2xl">📎</div></template><div class="flex items-center gap-1 p-2"><span class="min-w-0 flex-1 truncate text-[10px] text-slate-300" x-text="item.file.name"></span><button type="button" @click="removeFile(index)" class="rounded p-0.5 text-slate-500 hover:text-rose-300" :aria-label="'Remover ' + item.file.name">×</button></div></article></template></div>
                        <div class="flex items-end gap-3 p-3 sm:p-4"><input id="admin-reply-attachments" type="file" name="attachments[]" multiple class="sr-only" x-ref="attachmentsInput" accept=".jpg,.jpeg,.png,.webp,.pdf,.txt,.doc,.docx,.xls,.xlsx,.zip" @change="handleFiles($event.target.files)"><label for="admin-reply-attachments" class="flex h-11 w-11 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-400 transition hover:border-indigo-400/35 hover:bg-indigo-500/10 hover:text-indigo-200" title="Anexar arquivos"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13.5 10.5-3 3m0 0-3-3m3 3v-9m5.5 5.5v6A2.5 2.5 0 0 1 13.5 19h-7A2.5 2.5 0 0 1 4 16.5v-6" /></svg></label><div class="relative min-w-0 flex-1"><textarea name="message" rows="1" x-model="replyMessage" @input="broadcastTyping()" class="block max-h-32 w-full resize-none rounded-xl border border-white/10 bg-slate-950/60 px-3 py-3 text-sm text-white placeholder:text-slate-600 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20" :placeholder="replyMode === 'internal' ? 'Registre uma observação técnica para a equipe...' : 'Escreva uma resposta clara para o cliente...'" required oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 128) + 'px'"></textarea><div x-show="typingUser" x-cloak class="absolute -top-8 left-0 rounded-full border border-indigo-400/20 bg-slate-800 px-2 py-1 text-[10px] text-indigo-200 shadow-lg"><span x-text="typingUser + ' está digitando...'" ></span></div></div><button type="submit" class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white shadow-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900" :class="replyMode === 'internal' ? 'bg-amber-500 text-slate-950 shadow-amber-500/15 hover:bg-amber-400 focus:ring-amber-400' : 'bg-indigo-500 shadow-indigo-500/20 hover:bg-indigo-400 focus:ring-indigo-400'"><span x-text="replyMode === 'internal' ? 'Registrar' : 'Enviar'"></span><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 14-7-4 14-3-5-7-2Z" /></svg></button></div>
                    </form>
                </div>
            </div>
        @endif
    </main>
</x-app-layout>
