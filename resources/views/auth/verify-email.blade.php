<x-auth.brand-layout
    title="Verificar email"
    subtitle="Acesso ao portal"
    headline="Confirme seu email."
    description="Enviamos um link de verificação. Assim você libera o acesso ao portal."
    panelHint="Se não chegou, clique em reenviar. Verifique também spam."
>
    <div class="text-sm text-slate-300">
        Obrigado por se cadastrar! Antes de continuar, verifique seu email clicando no link que enviamos.
        Se não recebeu, você pode reenviar.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 text-sm text-emerald-200">
            Um novo link de verificação foi enviado para seu email.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                Reenviar email de verificação
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition">
                Sair
            </button>
        </form>
    </div>
</x-auth.brand-layout>
