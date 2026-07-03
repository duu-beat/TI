{{-- 🔔 NOTIFICAÇÕES (Componente Blade Modular) --}}
<div x-data="notifications()" x-init="init()" class="relative">
    <button @click="open = !open" 
            class="relative p-2 rounded-xl bg-slate-900 border border-white/10 text-slate-400 hover:text-white hover:border-indigo-500/50 transition-all group"
            aria-label="Notificações"
            :aria-expanded="open.toString()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        <template x-if="unreadCount > 0">
            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-500 text-[10px] font-bold text-white items-center justify-center" x-text="unreadCount"></span>
            </span>
        </template>
    </button>

    {{-- Dropdown de Notificações --}}
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-slate-900 border border-white/10 shadow-2xl overflow-hidden z-50"
         style="display: none;">
        
        <div class="p-4 border-b border-white/5 flex items-center justify-between bg-white/5">
            <h3 class="font-bold text-white text-sm">Notificações</h3>
            <button @click="markAllAsRead()" x-show="unreadCount > 0" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 uppercase tracking-wider">Marcar todas como lidas</button>
        </div>

        <div class="max-h-[400px] overflow-y-auto divide-y divide-white/5">
            <template x-for="notif in list" :key="notif.id">
                <div @click="markAsRead(notif)" 
                     class="p-4 hover:bg-white/5 transition cursor-pointer relative group"
                     :class="!notif.read_at ? 'bg-indigo-500/5' : ''">
                    <div class="flex gap-3">
                        <div class="h-8 w-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0 border border-white/5 group-hover:border-indigo-500/30 transition">
                            <template x-if="notif.type === 'ticket'">🎫</template>
                            <template x-if="notif.type === 'system'">⚙️</template>
                            <template x-if="notif.type === 'alert'">⚠️</template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-bold text-white mb-0.5" x-text="notif.title"></div>
                            <p class="text-xs text-slate-400 line-clamp-2" x-text="notif.message"></p>
                            <div class="text-[10px] text-slate-600 mt-2 flex items-center justify-between">
                                <span x-text="formatDate(notif.created_at)"></span>
                                <span x-show="!notif.read_at" class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="list.length === 0">
                <div class="p-8 text-center">
                    <div class="text-3xl mb-2 opacity-20">🔔</div>
                    <p class="text-xs text-slate-500">Nenhuma notificação por aqui.</p>
                </div>
            </template>
        </div>

        <div class="p-3 bg-white/5 border-t border-white/5 text-center">
            <a href="{{ route('notifications.index') }}" class="text-[10px] font-bold text-slate-500 hover:text-white transition uppercase tracking-widest">Ver histórico completo</a>
        </div>
    </div>
</div>
