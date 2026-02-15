<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // 1. Se for MASTER (Segurança), vai para o painel Master
        if ($user->role === 'master') {
            return redirect()->route('master.dashboard');
        }

        // 2. Se for ADMIN (Suporte), vai para o painel Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 3. Se for Cliente (ou qualquer outro), vai para a Dashboard do Cliente
        // Mantemos o 'intended' aqui para clientes, caso eles tenham clicado em um link de chamado por e-mail
        return redirect()->intended(route('client.dashboard'));
    }
}