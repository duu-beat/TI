<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MasterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🔒 A área é exclusiva do papel Master.
        if (!Auth::check() || !Auth::user()->isMaster()) {
            abort(403, 'Acesso restrito ao nível de Segurança/Sistema.');
        }

        $user = Auth::user();
        $hasConfirmedTwoFactor = filled($user->two_factor_secret) && $user->two_factor_confirmed_at !== null;

        // O perfil continua acessível para que o Master conclua a ativação do 2FA.
        if (!$hasConfirmedTwoFactor && !$request->routeIs('master.profile')) {
            return redirect()
                ->to(route('master.profile') . '#seguranca-da-conta')
                ->with('warning', 'Ative e confirme a autenticação em dois fatores para acessar o Núcleo de Segurança.');
        }

        return $next($request);
    }
}