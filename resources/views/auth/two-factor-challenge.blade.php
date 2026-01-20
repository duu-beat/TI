<x-auth.brand-layout
    title="Verificação em 2 etapas"
    subtitle="Segurança"
    headline="Confirme que é você."
    description="Digite o código do autenticador ou use um código de recuperação."
    panelHint="Recomendado: use app autenticador (Google Authenticator, Authy, etc.)."
>
    <x-validation-errors class="mb-4 text-red-200" />

    <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4" x-data="{ recovery: false }">
        @csrf

        <div class="text-sm text-slate-300">
            <span x-show="!recovery">Digite o código do seu aplicativo autenticador.</span>
            <span x-show="recovery" style="display:none;">Digite um dos seus códigos de recuperação.</span>
        </div>

        <div x-show="!recovery">
            <label for="code" class="text-sm text-slate-300">Código</label>
            <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" autofocus
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <div x-show="recovery" style="display:none;">
            <label for="recovery_code" class="text-sm text-slate-300">Código de recuperação</label>
            <input id="recovery_code" name="recovery_code" type="text" autocomplete="one-time-code"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
            Confirmar
        </button>

        <button type="button"
                class="w-full rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition"
                x-on:click="recovery = !recovery">
            <span x-show="!recovery">Usar código de recuperação</span>
            <span x-show="recovery" style="display:none;">Usar código do app</span>
        </button>
    </form>
</x-auth.brand-layout>
