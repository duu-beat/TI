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
        // 🔒 HARDENING: Apenas Master supremo pode criar outro Master. 
        // Se o master atual quiser criar, permitimos, mas com log estrito de nível DANGER.
        $allowedRoles = ['client', 'admin'];
        if (auth()->user()->email === 'master@ti.com' || auth()->user()->isMaster()) {
            $allowedRoles = ['client', 'admin', 'master'];
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
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
        // 🔒 HARDENING: Um Master não pode rebaixar ou editar outro Master, a menos que seja ele mesmo.
        if ($user->isMaster() && $user->id !== auth()->id()) {
            AuditLog::record('Security Alert', "Tentativa não autorizada de modificar outro Master ({$user->email})", 'DANGER');
            return back()->with('error', 'Você não tem permissão para modificar outro usuário Master.');
        }

        $allowedRoles = ['client', 'admin'];
        if ($user->id === auth()->id() || auth()->user()->isMaster()) {
            $allowedRoles = ['client', 'admin', 'master'];
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in($allowedRoles)],
            'password' => ['nullable', 'string', 'min:8'],
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