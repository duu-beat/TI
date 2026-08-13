<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Redireciona o usuário após uma confirmação bem-sucedida de 2FA.
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user?->isMaster()) {
            return redirect()->route('master.dashboard');
        }

        if ($user?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('client.dashboard'));
    }
}
