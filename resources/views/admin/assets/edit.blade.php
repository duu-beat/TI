<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.assets.index') }}" 
               class="group flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 transition hover:bg-indigo-600 hover:text-white hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    📦 Editar: {{ $asset->name }}
                </h2>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">Atualizar informações do ativo #{{ $asset->tag }}</p>
            </div>
        </div>
    </x-slot>

    <div x-data="{ loaded: false, tab: 'details' }" x-init="setTimeout(() => loaded = true, 300)" class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- SKELETON --}}
            <div x-show="!loaded" class="space-y-6 animate-pulse">
                <div class="h-12 w-full max-w-md bg-slate-800/50 rounded-xl border border-white/5"></div>
                <div class="h-80 bg-slate-900/50 rounded-3xl border border-white/5"></div>
            </div>

            {{-- CONTEÚDO REAL --}}
            <div x-show="loaded" style="display: none;"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- TABS --}}
                <div class="flex items-center gap-2 mb-6">
                    <button @click="tab = 'details'" 
                            :class="tab === 'details' ? 'bg-indigo-600 text-white shadow-indigo-500/20' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                            class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg">
                        Detalhes do Equipamento
                    </button>
                    <button @click="tab = 'history'" 
                            :class="tab === 'history' ? 'bg-indigo-600 text-white shadow-indigo-500/20' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                            class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg flex items-center gap-2">
                        Histórico de Movimentação
                        <span class="px-2 py-0.5 rounded-full bg-black/20 text-[10px]">{{ $asset->history->count() }}</span>
                    </button>
                </div>

                <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl">
                    {{-- Background Decorativo --}}
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    {{-- ABA: DETALHES --}}
                    <form x-show="tab === 'details'" action="{{ route('admin.assets.update', $asset) }}" method="POST" class="relative z-10 p-8 sm:p-10 space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Nome --}}
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Nome do Equipamento</label>
                                <input type="text" name="name" value="{{ old('name', $asset->name) }}" required 
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            {{-- Patrimônio --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Nº Patrimônio (Tag)</label>
                                <input type="text" name="tag" value="{{ old('tag', $asset->tag) }}" required 
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                                <x-input-error for="tag" class="mt-2" />
                            </div>

                            {{-- Tipo --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Tipo de Ativo</label>
                                <select name="type" required 
                                        class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                    <option value="Laptop" class="bg-slate-900" {{ old('type', $asset->type) == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                    <option value="Desktop" class="bg-slate-900" {{ old('type', $asset->type) == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                    <option value="Monitor" class="bg-slate-900" {{ old('type', $asset->type) == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                    <option value="Impressora" class="bg-slate-900" {{ old('type', $asset->type) == 'Impressora' ? 'selected' : '' }}>Impressora</option>
                                    <option value="Celular" class="bg-slate-900" {{ old('type', $asset->type) == 'Celular' ? 'selected' : '' }}>Celular</option>
                                    <option value="Outros" class="bg-slate-900" {{ old('type', $asset->type) == 'Outros' ? 'selected' : '' }}>Outros</option>
                                </select>
                            </div>

                            {{-- Marca --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Marca</label>
                                <input type="text" name="brand" value="{{ old('brand', $asset->brand) }}"
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                            </div>

                            {{-- Modelo --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Modelo</label>
                                <input type="text" name="model" value="{{ old('model', $asset->model) }}"
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                            </div>

                            {{-- Serial --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Número de Série</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                            </div>

                            {{-- Usuário Responsável --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Usuário Responsável</label>
                                <select name="user_id" 
                                        class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                    <option value="" class="bg-slate-900">-- Sem Vínculo (Em Estoque) --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" class="bg-slate-900" {{ old('user_id', $asset->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Status Atual</label>
                                <select name="status" required 
                                        class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer appearance-none">
                                    <option value="active" class="bg-slate-900" {{ old('status', $asset->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="maintenance" class="bg-slate-900" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Em Manutenção</option>
                                    <option value="retired" class="bg-slate-900" {{ old('status', $asset->status) == 'retired' ? 'selected' : '' }}>Aposentado</option>
                                    <option value="lost" class="bg-slate-900" {{ old('status', $asset->status) == 'lost' ? 'selected' : '' }}>Extraviado</option>
                                </select>
                            </div>

                            {{-- Datas --}}
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Data de Compra</label>
                                <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}"
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Expiração da Garantia</label>
                                <input type="date" name="warranty_expiration" value="{{ old('warranty_expiration', $asset->warranty_expiration ? $asset->warranty_expiration->format('Y-m-d') : '') }}"
                                       class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
                            </div>

                            {{-- Notas --}}
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">Observações Técnicas</label>
                                <textarea name="notes" rows="4" 
                                          class="w-full bg-slate-950/50 border-white/5 rounded-2xl py-3 px-4 text-slate-200 focus:border-indigo-500/50 focus:bg-slate-900 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">{{ old('notes', $asset->notes) }}</textarea>
                            </div>

                            {{-- Painel de Assinatura Digital --}}
                            <div class="md:col-span-2 mt-4" x-data="signaturePad()">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Assinatura Digital de Recebimento (Opcional)</label>
                                <div class="relative bg-white rounded-2xl overflow-hidden border-2 border-white/10 shadow-inner">
                                    <canvas x-ref="canvas" class="w-full h-40 cursor-crosshair"></canvas>
                                    <button type="button" @click="clear()" class="absolute top-3 right-3 p-2 bg-slate-900/80 text-white rounded-xl hover:bg-red-500 transition-all shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                <input type="hidden" name="signature" x-model="signatureData">
                                <p class="text-[10px] text-slate-500 mt-3 italic flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Assine acima para confirmar a entrega do equipamento ao novo responsável.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-white/5">
                            <a href="{{ route('admin.assets.index') }}" 
                               class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-2xl transition text-center">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-10 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 active:translate-y-0">
                                Atualizar Equipamento
                            </button>
                        </div>
                    </form>

                    {{-- ABA: HISTÓRICO --}}
                    <div x-show="tab === 'history'" class="p-8 sm:p-10 space-y-8 relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white">Linha do Tempo</h3>
                            <span class="text-xs text-slate-500 italic">Registros automáticos de auditoria</span>
                        </div>

                        <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-indigo-500 before:via-slate-700 before:to-transparent">
                            @forelse($asset->history as $log)
                                <div class="relative flex items-start gap-6 group">
                                    {{-- Ícone do Evento --}}
                                    <div class="absolute left-0 flex items-center justify-center w-10 h-10 rounded-full bg-slate-900 border-2 border-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.3)] z-10 transition-transform group-hover:scale-110">
                                        @if($log->action === 'create')
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 ml-12 bg-white/5 rounded-2xl p-5 border border-white/5 hover:border-white/10 transition shadow-sm">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                            <div class="text-sm font-bold text-white">
                                                {{ $log->action === 'create' ? 'Cadastro Inicial' : 'Atualização de Registro' }}
                                            </div>
                                            <div class="text-[10px] font-mono text-slate-500 uppercase tracking-widest">
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm text-slate-400 leading-relaxed">
                                            {{ $log->description }}
                                        </p>

                                        <div class="mt-4 pt-4 border-t border-white/5 flex items-center gap-3">
                                            <div class="h-6 w-6 rounded-full bg-indigo-500/20 flex items-center justify-center text-[10px] font-bold text-indigo-400 border border-indigo-500/20">
                                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            <span class="text-xs text-slate-500">Realizado por <strong class="text-slate-300">{{ $log->user->name ?? 'Sistema' }}</strong></span>
                                        </div>

                                        {{-- Exibição da Assinatura --}}
                                        @if($log->signature)
                                            <div class="mt-4 p-3 bg-white rounded-xl border border-white/10 inline-block">
                                                <p class="text-[10px] text-slate-400 uppercase font-bold mb-2">Assinatura Digital:</p>
                                                <img src="{{ $log->signature }}" alt="Assinatura" class="h-16 w-auto grayscale contrast-125">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="text-4xl mb-4 opacity-20">📜</div>
                                    <p class="text-slate-500 text-sm">Nenhum histórico registrado para este equipamento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function signaturePad() {
            return {
                signatureData: '',
                isDrawing: false,
                ctx: null,
                init() {
                    const canvas = this.$refs.canvas;
                    this.ctx = canvas.getContext('2d');
                    
                    const resize = () => {
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        this.ctx.scale(ratio, ratio);
                        
                        this.ctx.strokeStyle = '#0f172a';
                        this.ctx.lineWidth = 2.5;
                        this.ctx.lineJoin = 'round';
                        this.ctx.lineCap = 'round';
                    };

                    window.addEventListener('resize', resize);
                    setTimeout(resize, 100);
                    
                    const getPos = (e) => {
                        const rect = canvas.getBoundingClientRect();
                        return {
                            x: (e.clientX || (e.touches && e.touches[0].clientX)) - rect.left,
                            y: (e.clientY || (e.touches && e.touches[0].clientY)) - rect.top
                        };
                    };

                    const start = (e) => {
                        this.isDrawing = true;
                        const pos = getPos(e);
                        this.ctx.beginPath();
                        this.ctx.moveTo(pos.x, pos.y);
                    };

                    const move = (e) => {
                        if (!this.isDrawing) return;
                        if (e.cancelable) e.preventDefault();
                        const pos = getPos(e);
                        this.ctx.lineTo(pos.x, pos.y);
                        this.ctx.stroke();
                    };

                    const stop = () => {
                        if (this.isDrawing) {
                            this.isDrawing = false;
                            this.signatureData = canvas.toDataURL('image/png');
                        }
                    };

                    canvas.addEventListener('mousedown', start);
                    canvas.addEventListener('mousemove', move);
                    window.addEventListener('mouseup', stop);
                    
                    canvas.addEventListener('touchstart', start, { passive: false });
                    canvas.addEventListener('touchmove', move, { passive: false });
                    canvas.addEventListener('touchend', stop);
                },
                clear() {
                    this.ctx.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
                    this.signatureData = '';
                }
            }
        }
    </script>
</x-app-layout>
