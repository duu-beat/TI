<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Exibe o login da Área de Segurança (Tela Vermelha).
     */
    public function create()
    {
        // Se já estiver logado como MASTER, joga pro dashboard
        if (Auth::check() && Auth::user()->isMaster()) {
            return redirect()->route('master.dashboard');
        }

        return view('auth.master-login');
    }

    /**
     * Processa o login Master.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. Tenta logar (email + senha)
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 2. AQUI ESTÁ A CHAVE: Verifica se é MASTER
        // Se for Admin comum ou Cliente tentando entrar aqui, derruba a conexão.
        if (!Auth::user()->isMaster()) {
            Auth::logout();
            
            throw ValidationException::withMessages([
                'email' => 'Acesso negado. Nível de credencial insuficiente para esta área.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('master.dashboard'));
    }

    /**
     * Logout específico do Master.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home'); // Ou redirecionar para master.login de volta
    }
}