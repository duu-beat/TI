<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
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
