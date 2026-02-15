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

        // Verifica se é MASTER
        if (!Auth::user()->isMaster()) {
            Auth::logout();
            RateLimiter::hit($throttleKey);
            
            // Log de tentativa de acesso não autorizado
            AuditLog::record(
                'Acesso Negado (Master)', 
                "Usuário sem privilégios tentou acessar o Master. ID: " . ($request->user()->id ?? 'N/A'), 
                'WARNING'
            );
            
            throw ValidationException::withMessages([
                'email' => 'Credenciais insuficientes para acesso ao Núcleo de Segurança.',
            ]);
        }

        RateLimiter::clear($throttleKey);
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