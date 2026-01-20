<x-auth.brand-layout
    title="Acesso administrativo"
    subtitle="Painel interno"
    headline="Área restrita"
    description="Somente administradores autorizados."
    panelHint="Tentativas indevidas podem ser registradas."
>
    <x-validation-errors class="mb-4 text-red-200" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="admin_login" value="1">

        <div>
            <label class="text-sm text-slate-300">Email</label>
            <input type="email" name="email" required autofocus
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-orange-400/60 focus:ring-orange-400/20">
        </div>

        <div>
            <label class="text-sm text-slate-300">Senha</label>
            <input type="password" name="password" required
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-orange-400/60 focus:ring-orange-400/20">
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-red-500 to-orange-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
            Entrar como admin
        </button>

        <div class="text-xs text-slate-400 text-center">
            <a href="{{ route('login') }}" class="underline hover:text-white">Voltar para login do cliente</a>
        </div>
    </form>
</x-auth.brand-layout>
