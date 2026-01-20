<x-auth.brand-layout
    title="Confirmar senha"
    subtitle="Segurança"
    headline="Só mais uma confirmação."
    description="Precisamos confirmar sua senha antes de continuar para manter sua conta protegida."
    panelHint="Se você esqueceu sua senha, use a opção de recuperação no login."
>
    <x-validation-errors class="mb-4 text-red-200" />

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="text-sm text-slate-300">Senha</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
            Confirmar
        </button>
    </form>
</x-auth.brand-layout>
