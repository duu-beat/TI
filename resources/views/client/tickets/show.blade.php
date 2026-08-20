<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-3">
                <a href="{{ route('client.tickets.index') }}" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-slate-900/80 text-slate-400 transition hover:border-indigo-400/40 hover:bg-indigo-500/10 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Voltar para meus chamados">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-md border border-indigo-400/20 bg-indigo-500/10 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.14em] text-indigo-300">{{ $ticket->category ?? 'Suporte' }}</span>
                        <span class="text-[11px] text-slate-500">Aberto {{ $ticket->created_at->diffForHumans() }}</span>
                        @if($ticket->sla_due_at && !in_array($ticket->status, [\App\Enums\TicketStatus::RESOLVED, \App\Enums\TicketStatus::CLOSED]))
                            <div class="flex items-center gap-1.5 rounded-md border px-2 py-1 text-[10px] font-bold uppercase tracking-wider
                                {{ $ticket->sla_status === 'danger' ? 'border-rose-500/30 bg-rose-500/10 text-rose-400' : 
                                   ($ticket->sla_status === 'warning' ? 'border-amber-500/30 bg-amber-500/10 text-amber-400' : 'border-emerald-500/30 bg-emerald-500/10 text-emerald-400') }}">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                <span>Prazo: {{ $ticket->sla_remaining }}</span>
                            </div>
                        @endif
                    </div>
                    <h2 class="mt-1 truncate text-lg font-bold text-white sm:text-xl">Chamado #{{ $ticket->id }}</h2>
                </div>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                @if($ticket->is_escalated)
                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-rose-400/25 bg-rose-500/10 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-rose-300">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3Z" /></svg>
                        Escalonado
                    </span>
                @endif
                <x-ticket-status :status="$ticket->status" />
            </div>
        </div>
    </x-slot>

    <main x-data="{
            messages: @js($ticket->messages),
            typingUser: null,
            typingTimeout: null,
            init() { this.initEcho() },
            initEcho() {
                if (!window.Echo) return;
                window.Echo.private(`ticket.{{ $ticket->id }}`)
                    .listen('.message.sent', (event) => {
                        if (!this.messages.find((message) => message.id === event.message.id) && !event.message.is_internal) {
                            this.messages.push(event.message);
                            this.$nextTick(() => document.getElementById('ticket-updates')?.scrollIntoView({ behavior: 'smooth', block: 'end' }));
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
        }" x-init="init()" class="min-h-screen pb-40 pt-6 sm:pt-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-12 lg:gap-7">
                <section class="space-y-6 lg:col-span-8" aria-label="Acompanhamento do chamado">
                    <article class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900/75 shadow-2xl shadow-slate-950/25 backdrop-blur-xl">
                        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-indigo-500/12 blur-3xl"></div>
                        <div class="relative border-b border-white/5 px-5 py-5 sm:px-7 sm:py-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-indigo-300">Resumo do chamado</p>
                                    <h1 class="mt-2 text-xl font-black leading-snug text-white sm:text-2xl">{{ $ticket->subject }}</h1>
                                </div>
                                <div class="flex shrink-0 items-center gap-2 rounded-xl border border-white/10 bg-slate-950/35 px-3 py-2 text-xs text-slate-400">
                                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    Atualizado {{ $ticket->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div class="relative p-5 sm:p-7">
                            <div class="rounded-2xl border border-white/5 bg-slate-950/50 p-4 text-sm leading-7 text-slate-300 shadow-inner shadow-black/10 sm:p-5">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                            @if($ticket->messages->isNotEmpty() && $ticket->messages->first()->attachments->isNotEmpty())
                                <div class="mt-6 border-t border-white/5 pt-5">
                                    <div class="mb-3 flex items-center gap-2"><svg class="h-4 w-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13.5 10.5-3 3m0 0-3-3m3 3v-9m5.5 5.5v6A2.5 2.5 0 0 1 13.5 19h-7A2.5 2.5 0 0 1 4 16.5v-6" /></svg><h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Anexos enviados</h2></div>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($ticket->messages->first()->attachments as $attachment)
                                            <x-ticket-attachment-card :attachment="$attachment" compact />
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>

                    @if(in_array($ticket->status, [\App\Enums\TicketStatus::CLOSED, \App\Enums\TicketStatus::RESOLVED]) && !$ticket->npsSurvey()->exists())
                        <section class="relative overflow-hidden rounded-3xl border border-violet-400/20 bg-gradient-to-r from-violet-600/80 to-indigo-600/80 p-5 shadow-xl shadow-indigo-950/30 sm:p-6">
                            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3"><div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-xl">★</div><div><h2 class="font-bold text-white">Como foi seu atendimento?</h2><p class="mt-1 text-sm text-indigo-100">Seu chamado foi concluído. Sua avaliação ajuda a melhorar o suporte.</p></div></div>
                                <a href="{{ route('client.tickets.nps.show', $ticket) }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-indigo-700 transition hover:bg-indigo-50">Avaliar atendimento</a>
                            </div>
                        </section>
                    @endif

                    @php($activeVisit = $ticket->technicalVisits()->whereIn('status', ['scheduled', 'in_transit', 'in_service'])->first())
                    @if($activeVisit)
                        <section class="rounded-3xl border border-cyan-400/20 bg-cyan-500/[0.07] p-5 shadow-xl shadow-cyan-950/10 sm:p-6">
                            <div class="flex gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-300"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" /></svg></div><div class="min-w-0 flex-1"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="text-sm font-bold text-cyan-200">Visita técnica em andamento</h2><span class="rounded-md border border-cyan-400/20 bg-cyan-400/10 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-cyan-200">{{ $activeVisit->getStatusLabel() }}</span></div><p class="mt-2 text-sm text-slate-300">Agendada para <strong class="font-semibold text-white">{{ $activeVisit->scheduled_at->format('d/m/Y \à\s H:i') }}</strong>.</p></div></div>
                        </section>
                    @endif

                    <section id="ticket-updates" class="rounded-3xl border border-white/10 bg-slate-900/55 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-7" aria-labelledby="ticket-updates-title">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/5 pb-5">
                            <div><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-indigo-300">Histórico</p><h2 id="ticket-updates-title" class="mt-1 text-lg font-bold text-white">Atualizações do atendimento</h2></div>
                            <span class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-[11px] font-semibold text-slate-400" x-text="Math.max(messages.length - 1, 0) + ' interação(ões)'"></span>
                        </div>

                        <div class="relative mt-6 space-y-5 before:absolute before:bottom-4 before:left-5 before:top-4 before:w-px before:bg-white/10 sm:before:left-6">
                            <template x-for="(message, index) in messages" :key="message.id">
                                <article x-show="index > 0 && !message.is_internal" class="relative flex gap-3 sm:gap-4" :class="message.user_id == {{ auth()->id() }} ? 'sm:flex-row-reverse' : ''">
                                    <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border text-xs font-black shadow-lg ring-4 ring-slate-900" :class="message.user_id == {{ auth()->id() }} ? 'border-indigo-400/40 bg-indigo-500 text-white' : 'border-white/10 bg-slate-800 text-cyan-200'">
                                        <span x-text="(message.user?.name || 'S').charAt(0)"></span>
                                    </div>
                                    <div class="min-w-0 flex-1" :class="message.user_id == {{ auth()->id() }} ? 'sm:text-right' : ''">
                                        <div class="inline-block w-full max-w-3xl rounded-2xl border p-4 text-left shadow-lg sm:p-5" :class="message.user_id == {{ auth()->id() }} ? 'border-indigo-400/20 bg-indigo-500/10 sm:rounded-tr-md' : 'border-white/10 bg-slate-950/45 sm:rounded-tl-md'">
                                            <div class="mb-3 flex items-center justify-between gap-3" :class="message.user_id == {{ auth()->id() }} ? 'sm:flex-row-reverse' : ''">
                                                <div class="flex min-w-0 items-center gap-2"><span class="truncate text-xs font-bold text-white" x-text="message.user?.name || 'Suporte'" ></span><template x-if="message.user?.role === 'admin' || message.user?.role === 'master'"><span class="rounded-md border border-cyan-400/20 bg-cyan-400/10 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-cyan-200">Suporte</span></template></div>
                                                <time class="shrink-0 text-[10px] text-slate-500" x-text="new Date(message.created_at).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' })"></time>
                                            </div>
                                            <div class="text-sm leading-6 text-slate-300" x-html="message.message.replace(/\n/g, '<br>')"></div>
                                            <template x-if="message.attachments && message.attachments.length"><div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/5 pt-3 sm:grid-cols-3"><template x-for="attachment in message.attachments" :key="attachment.id"><a :href="attachmentUrl(attachment)" target="_blank" rel="noopener" class="overflow-hidden rounded-xl border border-white/10 bg-slate-950/60 transition hover:border-cyan-400/45"><template x-if="isImageAttachment(attachment)"><img :src="attachmentUrl(attachment)" :alt="attachmentName(attachment)" class="h-20 w-full object-cover" /></template><template x-if="isPdfAttachment(attachment)"><iframe :src="attachmentUrl(attachment) + '#toolbar=0'" :title="attachmentName(attachment)" class="h-20 w-full bg-white" loading="lazy"></iframe></template><template x-if="!isImageAttachment(attachment) && !isPdfAttachment(attachment)"><div class="flex h-20 items-center justify-center text-2xl">📎</div></template><div class="flex items-center gap-1 p-2"><span class="min-w-0 flex-1 truncate text-[10px] text-slate-300" x-text="attachmentName(attachment)"></span><span class="text-cyan-300">↗</span></div></a></template></div></template>
                                        </div>
                                    </div>
                                </article>
                            </template>
                            <p x-show="messages.length <= 1" class="rounded-xl border border-dashed border-white/10 bg-slate-950/30 px-4 py-5 text-center text-sm text-slate-500">Ainda não há atualizações. Quando a equipe responder, elas aparecerão aqui.</p>
                        </div>
                    </section>

                    @if($ticket->rating)
                        <section class="rounded-3xl border border-emerald-400/20 bg-emerald-500/[0.06] p-5 sm:p-6"><div class="flex gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">★</div><div><p class="text-[10px] font-bold uppercase tracking-[0.16em] text-emerald-300">Sua avaliação</p><div class="mt-1 flex text-lg text-amber-300">@foreach(range(1, 5) as $i)<span class="{{ $i <= $ticket->rating ? '' : 'text-slate-700' }}">★</span>@endforeach</div>@if($ticket->rating_comment)<p class="mt-2 text-sm italic text-slate-400">“{{ $ticket->rating_comment }}”</p>@endif</div></div></section>
                    @endif
                </section>

                <aside class="space-y-5 lg:col-span-4" aria-label="Detalhes do chamado">
                    <section class="rounded-3xl border border-white/10 bg-slate-900/75 p-5 shadow-xl shadow-slate-950/20 backdrop-blur-xl sm:p-6 lg:sticky lg:top-6">
                        <div class="flex items-center justify-between border-b border-white/5 pb-4"><h2 class="text-sm font-bold text-white">Visão do chamado</h2><span class="text-[11px] text-slate-500">#{{ $ticket->id }}</span></div>
                        <dl class="mt-5 space-y-4">
                            <div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</dt><dd class="mt-2"><x-ticket-status :status="$ticket->status" /></dd></div>
                            <div class="border-t border-white/5 pt-4"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Prioridade</dt><dd class="mt-2">@if($ticket->priority === \App\Enums\TicketPriority::HIGH)<span class="inline-flex rounded-lg border border-rose-400/20 bg-rose-500/10 px-2.5 py-1 text-xs font-bold text-rose-300">Alta prioridade</span>@elseif($ticket->priority === \App\Enums\TicketPriority::MEDIUM)<span class="inline-flex rounded-lg border border-amber-400/20 bg-amber-500/10 px-2.5 py-1 text-xs font-bold text-amber-300">Média prioridade</span>@else<span class="inline-flex rounded-lg border border-emerald-400/20 bg-emerald-500/10 px-2.5 py-1 text-xs font-bold text-emerald-300">Baixa prioridade</span>@endif</dd></div>
                            <div class="border-t border-white/5 pt-4"><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Responsável</dt><dd class="mt-2 flex items-center gap-2">@if($ticket->assignedTo)<span class="flex h-8 w-8 items-center justify-center rounded-lg border border-indigo-400/20 bg-indigo-500/10 text-xs font-bold text-indigo-200">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($ticket->assignedTo->name, 0, 1)) }}</span><span class="text-sm font-medium text-slate-200">{{ $ticket->assignedTo->name }}</span>@else<span class="text-sm text-slate-500">Aguardando atribuição</span>@endif</dd></div>
                            <div class="border-t border-white/5 pt-4 text-xs"><div class="flex items-center justify-between gap-3 py-1.5"><dt class="text-slate-500">Criado em</dt><dd class="text-right text-slate-300">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd></div><div class="flex items-center justify-between gap-3 py-1.5"><dt class="text-slate-500">Última interação</dt><dd class="text-right text-slate-300">{{ $ticket->updated_at->diffForHumans() }}</dd></div></div>
                        </dl>
                    </section>
                </aside>
            </div>
        </div>

        @if(!in_array($ticket->status, [\App\Enums\TicketStatus::CLOSED, \App\Enums\TicketStatus::RESOLVED]))
            <div class="pointer-events-none fixed inset-x-0 bottom-0 z-40 px-3 pb-3 sm:px-6 sm:pb-5">
                <div class="pointer-events-auto mx-auto max-w-5xl">
                    <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" enctype="multipart/form-data" x-data="attachmentUploader()" class="overflow-hidden rounded-2xl border border-white/10 bg-slate-900/95 shadow-2xl shadow-slate-950/60 backdrop-blur-2xl">
                        @csrf
                        <div class="flex items-center justify-between border-b border-white/5 px-4 py-2.5"><div class="flex items-center gap-2"><span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/15 text-indigo-200"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01" /></svg></span><span class="text-xs font-bold text-slate-200">Adicionar atualização</span></div><span class="text-[11px] text-slate-500">O suporte será notificado</span></div>
                        <x-validation-errors class="mx-4 mt-3" />
                        <div x-show="errors.length" x-cloak class="mx-4 mt-3 rounded-xl border border-rose-500/20 bg-rose-500/10 p-2.5 text-xs text-rose-200" role="alert"><template x-for="error in errors" :key="error"><p x-text="error"></p></template></div>
                        <div x-show="files.length" x-cloak class="grid grid-cols-2 gap-2 border-b border-white/5 p-3 sm:grid-cols-5"><template x-for="(item, index) in files" :key="item.file.name + item.file.size"><article class="relative overflow-hidden rounded-xl border border-white/10 bg-slate-950/60"><template x-if="item.isImage"><img :src="item.preview" :alt="item.file.name" class="h-20 w-full object-cover" /></template><template x-if="item.isPdf"><iframe :src="item.preview" :title="'Prévia de ' + item.file.name" class="h-20 w-full bg-white" loading="lazy"></iframe></template><template x-if="!item.isImage && !item.isPdf"><div class="flex h-20 items-center justify-center text-2xl">📎</div></template><div class="flex items-center gap-1 p-2"><span class="min-w-0 flex-1 truncate text-[10px] text-slate-300" x-text="item.file.name"></span><button type="button" @click="removeFile(index)" class="rounded p-0.5 text-slate-500 hover:text-rose-300" :aria-label="'Remover ' + item.file.name">×</button></div></article></template></div>
                        <div class="flex items-end gap-3 p-3 sm:p-4"><input id="client-reply-attachments" type="file" name="attachments[]" multiple class="sr-only" x-ref="attachmentsInput" accept=".jpg,.jpeg,.png,.webp,.pdf,.txt,.doc,.docx,.xls,.xlsx,.zip" @change="handleFiles($event.target.files)"><label for="client-reply-attachments" class="flex h-11 w-11 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-400 transition hover:border-indigo-400/35 hover:bg-indigo-500/10 hover:text-indigo-200" title="Anexar arquivos"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13.5 10.5-3 3m0 0-3-3m3 3v-9m5.5 5.5v6A2.5 2.5 0 0 1 13.5 19h-7A2.5 2.5 0 0 1 4 16.5v-6" /></svg></label><div class="relative min-w-0 flex-1"><textarea name="message" rows="1" @input="broadcastTyping()" class="block max-h-32 w-full resize-none rounded-xl border border-white/10 bg-slate-950/60 px-3 py-3 text-sm text-white placeholder:text-slate-600 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20" placeholder="Escreva uma atualização para a equipe..." required oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 128) + 'px'"></textarea><div x-show="typingUser" x-cloak class="absolute -top-8 left-0 rounded-full border border-indigo-400/20 bg-slate-800 px-2 py-1 text-[10px] text-indigo-200 shadow-lg"><span x-text="typingUser + ' está digitando...'" ></span></div></div><button type="submit" class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-500 px-4 text-sm font-bold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-900"><span class="hidden sm:inline">Enviar</span><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 14-7-4 14-3-5-7-2Z" /></svg></button></div>
                    </form>
                </div>
            </div>
        @endif
    </main>
</x-app-layout>
