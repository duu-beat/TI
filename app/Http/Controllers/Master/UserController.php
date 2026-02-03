<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Lista todos os usuários do sistema.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro de busca simples
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Ordena por mais recente
        $users = $query->latest()->paginate(20)->withQueryString();

        return view('master.users.index', compact('users'));
    }

    /**
     * Cria um novo usuário (Admin ou Cliente).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['client', 'admin', 'master'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(), // Já cria verificado
        ]);

        AuditLog::record(
            'User Created', 
            "Criou o usuário {$user->name} com papel: {$user->role}", 
            'WARNING'
        );

        return back()->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Atualiza os dados do usuário.
     */
    public function update(Request $request, User $user)
    {
        // Impede edição de outro Master se quem estiver logado não for o próprio (opcional, mas seguro)
        if ($user->isMaster() && $user->id !== auth()->id()) {
             // return back()->with('error', 'Apenas o próprio Master pode editar seus dados.');
             // (Deixei comentado caso queira permitir, mas cuidado com a segurança)
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['client', 'admin', 'master'])],
            'password' => ['nullable', 'string', 'min:8'], // Senha opcional na edição
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Só atualiza a senha se foi preenchida
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        AuditLog::record(
            'User Updated', 
            "Atualizou o usuário {$user->name} (ID: {$user->id})", 
            'WARNING'
        );

        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove um usuário (Banir/Excluir).
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode se auto-excluir.');
        }

        if ($user->isMaster()) {
            return back()->with('error', 'Não é possível excluir outro Master por aqui.');
        }

        $user->delete();

        AuditLog::record(
            'User Deleted', 
            "Excluiu o usuário {$user->name} ({$user->email})", 
            'DANGER'
        );

        return back()->with('success', 'Usuário removido do sistema.');
    }
}