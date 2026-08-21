<x-app-layout>
    <x-slot name="header">
        <div class="flex min-w-0 items-center gap-3"><a href="{{ route('admin.assets.show', $asset) }}" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-slate-900/80 text-slate-400 transition hover:border-indigo-400/40 hover:bg-indigo-500/10 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Voltar para o ativo"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 19-7-7 7-7" /></svg></a><div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-300">Confirmação presencial</p><h2 class="truncate text-lg font-bold text-white sm:text-xl">Assinar termo de responsabilidade</h2></div></div>
    </x-slot>

    <main class="mx-auto max-w-5xl py-2" aria-labelledby="term-sign-title">
        <div class="grid gap-6 lg:grid-cols-12">
            <section class="lg:col-span-7">
                <form method="POST" action="{{ route('admin.assets.terms.store-signature', [$asset, $term]) }}" x-data="termSignaturePad()" class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-2xl shadow-slate-950/20 backdrop-blur-xl">
                    @csrf
                    <div class="border-b border-white/5 px-6 py-7 sm:px-8"><div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10 text-emerald-300"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 13 4 4L19 7" /></svg></span><div><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-300">Etapa final</p><h1 id="term-sign-title" class="mt-1 text-xl font-black tracking-tight text-white sm:text-2xl">{{ $term->typeLabel() }}</h1><p class="mt-2 text-sm leading-6 text-slate-400">{{ $term->recipient->name }}, leia o termo e assine no quadro abaixo para concluir a movimentação.</p></div></div></div>

                    <div class="space-y-6 px-6 py-7 sm:px-8">
                        <x-validation-errors />
                        @if(session('error'))<div class="rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-100" role="alert">{{ session('error') }}</div>@endif
                        <section class="max-h-72 overflow-y-auto rounded-2xl border border-white/10 bg-slate-950/50 p-5" aria-labelledby="term-text-title"><h2 id="term-text-title" class="text-xs font-bold uppercase tracking-wider text-slate-400">Texto do termo</h2><div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-300">{{ $term->terms_text }}</div></section>

                        <section aria-labelledby="signature-title"><div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between"><div><h2 id="signature-title" class="text-sm font-bold text-white">Assinatura do responsável</h2><p class="mt-1 text-xs leading-5 text-slate-500">Use o dedo, caneta ou mouse para assinar dentro do quadro.</p></div><button type="button" @click="clear()" class="inline-flex w-fit items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-300 transition hover:border-rose-400/30 hover:bg-rose-500/10 hover:text-rose-200"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16m-2 0-.8 12.1a2 2 0 0 1-2 1.9H8.8a2 2 0 0 1-2-1.9L6 7m3 0V4h6v3" /></svg>Limpar assinatura</button></div><div class="mt-4 overflow-hidden rounded-2xl border-2 border-dashed border-indigo-400/25 bg-white shadow-inner"><canvas x-ref="canvas" class="h-52 w-full touch-none cursor-crosshair" aria-label="Área para assinatura digital"></canvas></div><input type="hidden" name="signature" x-model="signatureData"><x-input-error for="signature" class="mt-2" /></section>

                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-white/10 bg-white/[0.03] p-4"><input type="checkbox" required class="mt-0.5 rounded border-white/20 bg-slate-900 text-indigo-500 focus:ring-indigo-400"><span class="text-xs leading-5 text-slate-400">Confirmo que li o termo, reconheço a movimentação deste ativo e autorizo o registro da minha assinatura para fins de controle interno.</span></label>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-white/5 bg-slate-950/25 px-6 py-5 sm:flex-row sm:items-center sm:justify-end sm:px-8"><a href="{{ route('admin.assets.show', $asset) }}" class="rounded-xl px-4 py-2.5 text-center text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">Assinar depois</a><button type="submit" :disabled="!signatureData" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-emerald-400 disabled:cursor-not-allowed disabled:opacity-45 focus:outline-none focus:ring-2 focus:ring-emerald-200"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 13 4 4L19 7" /></svg>Confirmar e assinar</button></div>
                </form>
            </section>

            <aside class="space-y-5 lg:col-span-5"><section class="rounded-3xl border border-white/10 bg-slate-900/65 p-5 shadow-xl shadow-slate-950/15"><p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Resumo do ativo</p><h2 class="mt-2 text-lg font-bold text-white">{{ $asset->name }}</h2><dl class="mt-5 space-y-3 text-xs"><div class="flex justify-between gap-4"><dt class="text-slate-500">Patrimônio</dt><dd class="font-mono font-semibold text-slate-300">{{ $asset->tag }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Serial</dt><dd class="max-w-[60%] truncate text-right font-mono font-semibold text-slate-300">{{ $asset->serial_number ?: 'Não informado' }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Responsável</dt><dd class="max-w-[60%] truncate text-right font-semibold text-slate-300">{{ $term->recipient->name }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Emitido por</dt><dd class="max-w-[60%] truncate text-right font-semibold text-slate-300">{{ $term->issuer->name }}</dd></div></dl></section><section class="rounded-3xl border border-indigo-400/15 bg-gradient-to-br from-indigo-500/10 to-slate-900/80 p-5"><h2 class="text-sm font-bold text-white">Após a confirmação</h2><ul class="mt-4 space-y-3 text-xs leading-5 text-slate-400"><li class="flex gap-2"><span class="font-bold text-indigo-300">1.</span>A assinatura é registrada em armazenamento privado.</li><li class="flex gap-2"><span class="font-bold text-indigo-300">2.</span>O responsável do ativo é atualizado conforme a movimentação.</li><li class="flex gap-2"><span class="font-bold text-indigo-300">3.</span>Um PDF do termo fica disponível no histórico do ativo.</li></ul></section></aside>
        </div>
    </main>

    <script>
        function termSignaturePad() {
            return {
                signatureData: '',
                canvas: null,
                context: null,
                drawing: false,
                resizeObserver: null,
                cssWidth: 0,
                cssHeight: 0,
                init() {
                    this.canvas = this.$refs.canvas;
                    this.resizeCanvas();

                    if ('ResizeObserver' in window) {
                        this.resizeObserver = new ResizeObserver(() => this.resizeCanvas());
                        this.resizeObserver.observe(this.canvas);
                    }

                    // O layout global exibe o conteúdo após uma transição curta;
                    // esta segunda medição evita um canvas 1×1 em telas móveis.
                    this.$nextTick(() => window.requestAnimationFrame(() => {
                        this.resizeCanvas();
                        window.setTimeout(() => this.resizeCanvas(), 240);
                    }));

                    const position = (event) => {
                        const rect = this.canvas.getBoundingClientRect();
                        return { x: event.clientX - rect.left, y: event.clientY - rect.top };
                    };
                    this.canvas.addEventListener('pointerdown', (event) => {
                        event.preventDefault();
                        this.resizeCanvas();
                        this.canvas.setPointerCapture?.(event.pointerId);
                        const point = position(event);
                        this.drawing = true;
                        this.context.beginPath();
                        this.context.moveTo(point.x, point.y);
                    });
                    this.canvas.addEventListener('pointermove', (event) => {
                        if (!this.drawing) return;
                        event.preventDefault();
                        const point = position(event);
                        this.context.lineTo(point.x, point.y);
                        this.context.stroke();
                    });
                    ['pointerup', 'pointercancel'].forEach((type) => this.canvas.addEventListener(type, () => this.finish()));
                },
                resizeCanvas() {
                    const rect = this.canvas.getBoundingClientRect();
                    const width = Math.max(1, Math.round(rect.width));
                    const height = Math.max(1, Math.round(rect.height));
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const pixelWidth = Math.round(width * ratio);
                    const pixelHeight = Math.round(height * ratio);

                    if (this.canvas.width === pixelWidth && this.canvas.height === pixelHeight && this.context) return;

                    const drawing = this.signatureData;
                    this.canvas.width = pixelWidth;
                    this.canvas.height = pixelHeight;
                    this.cssWidth = width;
                    this.cssHeight = height;
                    this.context = this.canvas.getContext('2d');
                    this.context.setTransform(ratio, 0, 0, ratio, 0, 0);
                    this.context.strokeStyle = '#111827';
                    this.context.lineWidth = 2.5;
                    this.context.lineCap = 'round';
                    this.context.lineJoin = 'round';

                    if (drawing) {
                        const image = new Image();
                        image.onload = () => this.context.drawImage(image, 0, 0, this.cssWidth, this.cssHeight);
                        image.src = drawing;
                    }
                },
                finish() {
                    if (!this.drawing) return;
                    this.drawing = false;
                    this.signatureData = this.canvas.toDataURL('image/png');
                },
                clear() {
                    this.context.clearRect(0, 0, this.cssWidth, this.cssHeight);
                    this.signatureData = '';
                }
            };
        }
    </script>
</x-app-layout>
