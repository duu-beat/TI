<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // 👈 Importante adicionar isso

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // ✅ AQUI ESTÁ A CORREÇÃO: Registre os dois middlewares
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,   // <--- Essa linha estava faltando
            'master' => \App\Http\Middleware\MasterMiddleware::class,
        ]);

        // 2. REDIRECIONAMENTO INTELIGENTE 🧠
        // Se tentar acessar "/seguranca" e não estiver logado -> vai para login da segurança
        // Se for qualquer outra coisa -> vai para login normal
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('seguranca*')) {
                return route('master.login');
            }
            return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();