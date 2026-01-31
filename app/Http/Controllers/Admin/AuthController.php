<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     * Este é o método que estava faltando ("create").
     */
    public function create()
    {
        // Se já for admin, não precisa logar de novo
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    /**
     * Processa o envio do formulário de login.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. Tenta autenticar (email + senha)
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 2. Verifica se é realmente ADMIN
        if (Auth::user()->role !== 'admin') {
            Auth::logout(); // Desloga se for cliente a tentar entrar
            
            throw ValidationException::withMessages([
                'email' => 'Esta conta não possui permissões de administrador.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Faz o logout do admin.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}