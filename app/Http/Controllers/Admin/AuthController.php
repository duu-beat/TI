<?php

namespace App\Http\Controllers\Admin;

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
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function store(Request $request)
    {
        // 1. Chave única para o Rate Limiter (IP + Email)
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // 2. Verifica se o usuário excedeu o limite (5 tentativas por minuto)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            AuditLog::record(
                'Bloqueio de Login (Admin)', 
                "IP {$request->ip()} bloqueado por {$seconds}s após múltiplas falhas.", 
                'WARNING'
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 3. Tenta autenticar
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Conta a falha no Rate Limiter
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 4. Verifica permissão de ADMIN
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            RateLimiter::hit($throttleKey); // Penaliza tentativa sem permissão
            
            throw ValidationException::withMessages([
                'email' => 'Esta conta não possui permissões de administrador.',
            ]);
        }

        // 5. Sucesso: Limpa o limitador e registra Log
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        AuditLog::record(
            'Login Admin', 
            'Acesso ao painel administrativo realizado com sucesso.', 
            'INFO'
        );

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request)
    {
        // Log de saída (Opcional, mas bom para rastreio)
        if (Auth::check()) {
            AuditLog::record('Logout Admin', 'Usuário encerrou a sessão.', 'INFO');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}