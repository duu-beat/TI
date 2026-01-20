<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        return redirect()->intended(
            $user->role === 'admin'
                ? route('admin.dashboard')
                : route('client.dashboard')
        );
    }
}
