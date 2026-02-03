<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Models\AuditLog; // ✅ Importante
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        $escalatedTickets = Ticket::with('user')
            ->where('is_escalated', true)
            ->where('status', '!=', 'resolved')
            ->latest()
            ->get();

        $admins = User::where('role', User::ROLE_ADMIN)->get();

        return view('master.dashboard', compact('escalatedTickets', 'admins'));
    }

    // --- AUDITORIA (ATUALIZADO) ---
    public function audit(Request $request)
    {
        $query = AuditLog::with('user');

        // ✅ Permite filtrar logs por um usuário específico (Ex: ver logs de um admin)
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Pega os logs reais do banco (50 por página)
        $logs = $query->latest()->paginate(50)->withQueryString();

        return view('master.audit', compact('logs'));
    }

    
    // --- CONFIGURAÇÕES ---
    public function settings()
    {
        // Busca configurações do banco
        $settings = DB::table('system_settings')->pluck('value', 'key');

        return view('master.settings', [
            'registrationBlocked' => ($settings['registration_blocked'] ?? '0') === '1',
            'globalMessage'       => $settings['global_message'] ?? null,
            'globalMessageStyle'  => $settings['global_message_style'] ?? 'info',
        ]);
    }

    public function updateSettings(Request $request)
    {
        // 1. Modo Manutenção
        if ($request->has('maintenance_mode')) {
            if (!app()->isDownForMaintenance()) {
                Artisan::call('down', ['--secret' => 'seguranca-bypass', '--render' => 'errors.503']);
                AuditLog::record('System', 'Ativou Modo de Manutenção', 'DANGER');
            }
        } else {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
                AuditLog::record('System', 'Desativou Modo de Manutenção', 'SUCCESS');
            }
        }

        // 2. Bloqueio de Cadastro
        $blockRegister = $request->has('block_registers') ? '1' : '0';
        DB::table('system_settings')->updateOrInsert(['key' => 'registration_blocked'], ['value' => $blockRegister, 'updated_at' => now()]);

        // 3. Aviso Global
        $message = $request->has('global_message_active') ? $request->global_message : null;
        DB::table('system_settings')->updateOrInsert(['key' => 'global_message'], ['value' => $message, 'updated_at' => now()]);
        DB::table('system_settings')->updateOrInsert(['key' => 'global_message_style'], ['value' => $request->global_message_style, 'updated_at' => now()]);

        // Logs
        if ($request->has('block_registers')) AuditLog::record('Settings', 'Bloqueou novos registros', 'WARNING');
        if ($message) AuditLog::record('Settings', 'Atualizou Aviso Global', 'INFO');

        return back()->with('success', 'Configurações atualizadas.');
    }

    public function toggleAdmin(Request $request, User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Ação inválida.');

        $newRole = ($user->role === User::ROLE_ADMIN) ? User::ROLE_CLIENT : User::ROLE_ADMIN;
        $user->update(['role' => $newRole]);
        
        AuditLog::record('User Role', "Alterou papel de {$user->name} para {$newRole}", 'WARNING');

        return back()->with('success', "Papel do usuário atualizado.");
    }

    /**
     * Exibe o conteúdo do laravel.log
     */
    public function systemLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logFile)) {
            // Lê o arquivo em um array (cada linha é um item)
            $fileContent = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            // Pega as últimas 200 linhas para não travar a tela
            $logs = array_slice($fileContent, -200);
            
            // Inverte para ver o erro mais recente no topo
            $logs = array_reverse($logs);
        }

        return view('master.system-logs', compact('logs'));
    }

    /**
     * Limpa o arquivo de log
     */
    public function clearSystemLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            File::put($logFile, ''); // Esvazia o arquivo
            
            // Registra na auditoria que alguém limpou os rastros
            AuditLog::record('System', 'Limpou os logs de erro do sistema (laravel.log)', 'WARNING');
        }

        return back()->with('success', 'Logs do sistema foram limpos.');
    }

    /**
     * Resolução imediata via Painel de Segurança.
     */
    public function resolveEscalated(Request $request, Ticket $ticket)
    {
        // 1. Atualiza o status para RESOLVIDO
        $ticket->update([
            'status' => \App\Enums\TicketStatus::RESOLVED,
            'is_escalated' => false // Remove o alerta de escalonamento pois foi resolvido
        ]);

        // 2. (Opcional) Adiciona uma mensagem automática no chat avisando o cliente
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => "Chamado resolvido pela equipe de Segurança/Infraestrutura.\n\nSolução Técnica: " . ($request->solution ?? 'Intervenção direta no sistema.'),
        ]);

        // 3. Grava no Log de Auditoria
        AuditLog::record(
            'Ticket Resolved', 
            "Master resolveu o chamado #{$ticket->id} via Painel de Controle.", 
            'SUCCESS'
        );

        return back()->with('success', "Chamado #{$ticket->id} encerrado com sucesso.");
    }
}