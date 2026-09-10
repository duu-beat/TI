<x-auth.brand-layout
    title="{{ __('auth.ui.verify_email_title') }}"
    subtitle="{{ __('auth.ui.password_reset_subtitle') }}"
    headline="{{ __('auth.ui.verify_headline') }}"
    description="{{ __('auth.ui.verify_description') }}"
    panelHint="{{ __('auth.ui.verify_hint') }}"
>
    <div class="text-sm text-slate-300">
        {{ __('auth.ui.verify_thanks') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 text-sm text-emerald-200">
            {{ __('auth.ui.verification_sent') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                {{ __('auth.ui.resend_verification') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-2xl bg-white/10 px-6 py-3 font-semibold text-white hover:bg-white/15 transition">
                {{ __('auth.ui.logout') }}
            </button>
        </form>
    </div>
</x-auth.brand-layout>
