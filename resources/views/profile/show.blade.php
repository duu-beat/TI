<x-app-layout>
    @php
        $user = auth()->user();
        $isAdmin = $user->role === 'admin'; // Ajuste conforme seu campo no DB
        $accent = $isAdmin ? 'red' : 'cyan';
    @endphp

    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight">
            {{ __('Central da Conta') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- HEADER BANNER --}}
            <div class="relative bg-slate-900/80 border border-white/10 rounded-[2.5rem] p-8 mb-8 flex flex-col md:flex-row items-center gap-8 overflow-hidden shadow-2xl">
                {{-- Glow --}}
                <div class="absolute top-0 right-0 w-96 h-96 bg-{{ $accent }}-500/20 rounded-full blur-[100px] pointer-events-none"></div>

                {{-- Avatar --}}
                <div class="relative shrink-0 group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-{{ $accent }}-500 to-purple-600 rounded-full blur opacity-25 group-hover:opacity-75 transition duration-1000"></div>
                    <img class="relative h-32 w-32 rounded-full object-cover border-4 border-slate-950 shadow-2xl" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                    <div class="absolute bottom-0 right-0 bg-slate-950 rounded-full p-1.5 border border-white/10">
                        <div class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-{{ $accent }}-500 text-slate-950">
                            {{ $isAdmin ? 'ADMIN' : 'CLIENTE' }}
                        </div>
                    </div>
                </div>

                {{-- Infos --}}
                <div class="text-center md:text-left z-10 flex-1">
                    <h1 class="text-4xl font-black text-white tracking-tight mb-2">{{ $user->name }}</h1>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-slate-400">
                        <span class="flex items-center gap-1">📧 {{ $user->email }}</span>
                        <span class="flex items-center gap-1">📅 Membro desde {{ $user->created_at->format('Y') }}</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex gap-6 z-10 border-t md:border-t-0 md:border-l border-white/10 pt-6 md:pt-0 md:pl-8 mt-6 md:mt-0">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $user->two_factor_secret ? '100%' : '50%' }}</div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest">Segurança</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-{{ $accent }}-400">Ativo</div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest">Status</div>
                    </div>
                </div>
            </div>

            {{-- GRID LAYOUT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 auto-rows-fr">

                {{-- 1. Dados Pessoais --}}
                <div class="md:col-span-2 xl:col-span-2">
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        @livewire('profile.update-profile-information-form')
                    @endif
                </div>

                {{-- 2. Senha --}}
                <div class="md:col-span-1 xl:col-span-1">
                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        @livewire('profile.update-password-form')
                    @endif
                </div>

                {{-- 3. 2FA --}}
                <div class="md:col-span-1 xl:col-span-1">
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        @livewire('profile.two-factor-authentication-form')
                    @endif
                </div>

                {{-- 4. Sessões --}}
                <div class="md:col-span-1 xl:col-span-1">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                {{-- 5. Deletar Conta --}}
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="md:col-span-2 xl:col-span-1">
                        @if(!$isAdmin)
                            @livewire('profile.delete-user-form')
                        @else
                            {{-- Admin não pode deletar --}}
                            <div class="h-full flex flex-col justify-center items-center bg-slate-900/50 border border-red-500/20 rounded-[2rem] p-8 text-center relative overflow-hidden shadow-xl">
                                <div class="absolute inset-0 bg-red-500/5 pattern-grid-lg opacity-20"></div>
                                <div class="text-4xl mb-4 grayscale opacity-50">🛡️</div>
                                <h3 class="text-red-400 font-bold text-lg">Área Protegida</h3>
                                <p class="text-xs text-slate-500 mt-2 px-4">
                                    Contas administrativas não podem ser excluídas pelo painel.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>