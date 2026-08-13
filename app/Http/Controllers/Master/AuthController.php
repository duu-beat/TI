<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function create()
    {
        if (Auth::check() && Auth::user()->isMaster()) {
            return redirect()->route('master.dashboard');
        }

        return view('auth.master-login');
    }

    public function store(Request $request)
    {
        // Chave de Rate Limit baseada apenas no IP para esta área crítica
        // (Bloqueia o IP inteiro se tentar bruteforce em qualquer conta Master)
        $throttleKey = 'master_login:' . $request->ip();

        // Limite RÍGIDO: 3 tentativas por minuto
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            // Log de Alta Prioridade
            AuditLog::record(
                'ALERTA DE SEGURANÇA', 
                "Tentativa de força bruta na área Master. IP: {$request->ip()}", 
                'DANGER'
            );

            throw ValidationException::withMessages([
                'email' => "Acesso bloqueado temporariamente por segurança. Aguarde {$seconds} segundos.",
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Verifica se é MASTER
        if (!$user->isMaster()) {
            $attemptedUserId = $user->id;
            Auth::logout();
            RateLimiter::hit($throttleKey);
            
            // Log de tentativa de acesso não autorizado
            AuditLog::record(
                'Acesso Negado (Master)', 
                "Usuário sem privilégios tentou acessar o Master. ID: {$attemptedUserId}",
                'WARNING'
            );
            
            throw ValidationException::withMessages([
                'email' => 'Credenciais insuficientes para acesso ao Núcleo de Segurança.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Um Master que ativou 2FA só recebe uma sessão autenticada após confirmar o código.
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            AuditLog::record(
                'Desafio 2FA Master',
                'Credenciais primárias validadas; aguardando confirmação de autenticação em dois fatores.',
                'INFO'
            );

            Auth::logout();
            $request->session()->put('login.id', $user->id);
            $request->session()->put('login.remember', $request->boolean('remember'));

            return redirect()->route('two-factor.login');
        }

        $request->session()->regenerate();

        AuditLog::record(
            'Acesso Master', 
            'Sessão iniciada no Núcleo de Segurança.', 
            'SUCCESS'
        );

        return redirect()->route('master.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}