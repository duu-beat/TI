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
        // 🔒 BLOQUEIO TOTAL: Só passa se for 'master'
        if (!Auth::check() || !Auth::user()->isMaster()) {
            abort(403, 'Acesso restrito ao nível de Segurança/Sistema.');
        }

        return $next($request);
    }
}