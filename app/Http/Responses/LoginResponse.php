<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // 🚨 MUDANÇA AQUI:
        // Se for Admin, forçamos o redirecionamento para o dashboard de admin.
        // Não usamos 'intended()' aqui para evitar que ele volte para páginas de cliente.
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Se for Cliente, mantemos o comportamento padrão (vai para onde queria ir)
        return redirect()->intended(route('client.dashboard'));
    }
}