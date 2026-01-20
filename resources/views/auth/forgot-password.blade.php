<x-auth.brand-layout
    title="Recuperar senha"
    subtitle="Acesso ao portal"
    headline="Recuperar acesso é rápido."
    description="Informe seu email e enviaremos um link para redefinir sua senha."
    panelHint="Verifique também a caixa de spam, às vezes o email cai lá."
>
    <div class="text-sm text-slate-300">
        Esqueceu sua senha? Sem stress. Digite seu email e você receberá um link para redefinir.
    </div>

    @session('status')
        <div class="mt-4 text-sm text-emerald-200">
            {{ $value }}
        </div>
    @endsession

    <x-validation-errors class="mt-4 mb-4 text-red-200" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="text-sm text-slate-300">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
            Enviar link de redefinição
        </button>

        <p class="text-sm text-slate-400 text-center">
            Lembrou?
            <a href="{{ route('login') }}" class="text-white underline hover:opacity-90">Voltar ao login</a>
        </p>
    </form>
</x-auth.brand-layout>
