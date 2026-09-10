<x-auth.brand-layout
    title="{{ __('auth.ui.new_password_title') }}"
    subtitle="{{ __('auth.ui.password_reset_subtitle') }}"
    headline="{{ __('auth.ui.new_password_title') }}"
    description="{{ __('auth.ui.password_reset_description') }}"
    panelHint="{{ __('auth.ui.password_reset_hint') }}"
>
    <x-validation-errors class="mb-4 text-red-200" />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="text-sm text-slate-300">{{ __('auth.ui.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <div>
            <label for="password" class="text-sm text-slate-300">{{ __('auth.ui.new_password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <div>
            <label for="password_confirmation" class="text-sm text-slate-300">{{ __('auth.ui.confirm_new_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500
                          focus:border-cyan-400/60 focus:ring-cyan-400/20">
        </div>

        <button type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
            {{ __('auth.ui.update_password') }}
        </button>
    </form>
</x-auth.brand-layout>
