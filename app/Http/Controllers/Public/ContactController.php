<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail; // Descomentar se fores configurar envio de email real

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function submit(Request $request)
    {
        // 1. Bloqueio de segurança para Admins
        // Impede que um admin envie o formulário mesmo que force a requisição (ex: via Postman)
        if (auth()->check() && auth()->user()->role === 'admin') {
            return back()->withErrors(['message' => 'Administradores não podem abrir chamados por aqui. Utilize o painel.']);
        }

        // 2. Validar os dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10',
        ]);

        // 3. Lógica de Envio (Aqui podes enviar um email ou salvar na BD)
        // Por enquanto, vamos apenas simular o sucesso.
        
        /* Mail::raw($validated['message'], function($msg) use ($validated) {
            $msg->to('admin@suporteti.com')
                ->subject('Novo Contacto: ' . $validated['name']);
        }); 
        */

        // 4. Redirecionar com mensagem de sucesso
        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contacto em breve.');
    }
}