<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    // A operação diária é exclusiva do papel Admin. O Master possui um plano
    // de supervisão separado em /seguranca e não deve acessar fluxos operacionais.
    if (!Auth::check() || Auth::user()->role !== \App\Models\User::ROLE_ADMIN) {
        abort(403);
    }

    return $next($request);
}

}
