<x-auth.brand-layout
    title="{{ __('auth.ui.password_reset_title') }}"
    subtitle="{{ __('auth.ui.password_reset_subtitle') }}"
    headline="{{ __('auth.ui.password_reset_headline') }}"
    description="{{ __('auth.ui.password_reset_description') }}"
    panelHint="{{ __('auth.ui.password_reset_hint') }}"
>
    <div class="text-sm text-slate-300">
        {{ __('auth.ui.password_reset_description') }}
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
            <label for="email" class="text-sm text-slate-300">{{ __('auth.ui.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
            {{ __('auth.ui.send_reset_link') }}
        </button>

        <p class="text-sm text-slate-400 text-center">
            {{ __('auth.ui.remember_me') }}
            <a href="{{ route('login') }}" class="text-white underline hover:opacity-90">{{ __('auth.ui.back_to_login') }}</a>
        </p>
    </form>
</x-auth.brand-layout>
