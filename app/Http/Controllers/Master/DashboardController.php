<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\User;
use App\Enums\TicketStatus;
use App\Services\SlaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        $openStatuses = TicketStatus::openStatuses();

        $escalatedTickets = Ticket::with(['user', 'assignee'])
            ->where('is_escalated', true)
            ->whereIn('status', $openStatuses)
            ->latest('updated_at')
            ->get();

        $recentSecurityEvents = AuditLog::with('user')
            ->whereIn('level', ['WARNING', 'DANGER'])
            ->latest()
            ->limit(6)
            ->get();

        $masterMetrics = [
            'escalated' => $escalatedTickets->count(),
            'overdue_sla' => Ticket::query()
                ->whereIn('status', $openStatuses)
                ->whereNotNull('sla_due_at')
                ->where('sla_due_at', '<', now())
                ->count(),
            'unassigned' => Ticket::query()
                ->whereIn('status', $openStatuses)
                ->whereNull('assigned_to')
                ->count(),
            'privileged_without_2fa' => User::query()
                ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_MASTER])
                ->where(function ($query) {
                    $query->whereNull('two_factor_secret')
                        ->orWhereNull('two_factor_confirmed_at');
                })
                ->count(),
            'recent_warnings' => $recentSecurityEvents->count(),
        ];

        return view('master.dashboard', compact('escalatedTickets', 'recentSecurityEvents', 'masterMetrics'));
    }

    // --- AUDITORIA (ATUALIZADO) ---
    public function audit(Request $request)
    {
        $query = AuditLog::with('user');

        // Permite filtrar logs por um usuário específico
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
        $settings = DB::table('system_settings')->pluck('value', 'key');

        return view('master.settings', [
            'registrationBlocked' => ($settings['registration_blocked'] ?? '0') === '1',
            'globalMessage' => $settings['global_message'] ?? null,
            'globalMessageStyle' => $settings['global_message_style'] ?? 'info',
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
        Cache::forget('ui:global_banner:v1');

        // Logs
        if ($request->has('block_registers')) {
            AuditLog::record('Settings', 'Bloqueou novos registros', 'WARNING');
        }

        if ($message) {
            AuditLog::record('Settings', 'Atualizou Aviso Global', 'INFO');
        }

        return back()->with('success', 'Configurações atualizadas.');
    }

    public function toggleAdmin(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Ação inválida.');
        }

        $newRole = ($user->role === User::ROLE_ADMIN) ? User::ROLE_CLIENT : User::ROLE_ADMIN;
        $user->update(['role' => $newRole]);
        Cache::forget('home:master:stats:v1');

        AuditLog::record('User Role', "Alterou papel de {$user->name} para {$newRole}", 'WARNING');

        return back()->with('success', 'Papel do usuário atualizado.');
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
    public function resolveEscalated(Request $request, Ticket $ticket, SlaService $slaService)
    {
        $request->validate([
            'solution' => ['nullable', 'string', 'max:4000'],
        ]);

        if (!$ticket->is_escalated || !in_array($ticket->status->value, TicketStatus::openStatuses())) {
            return back()->with('warning', 'Este incidente não está mais pendente de supervisão.');
        }

        DB::transaction(function () use ($ticket, $request, $slaService) {
            $ticket->update([
                'status' => TicketStatus::RESOLVED,
                'resolved_at' => now(),
                'is_escalated' => false,
            ]);

            $slaService->calculateResolutionTime($ticket);

            $ticket->messages()->create([
                'user_id' => auth()->id(),
                'message' => "Chamado resolvido pela equipe de Segurança/Infraestrutura.\n\nSolução técnica: " . ($request->input('solution') ?: 'Intervenção direta no sistema.'),
            ]);

            AuditLog::record(
                'Ticket Resolved',
                "Master resolveu o incidente escalonado #{$ticket->id} pelo painel de supervisão.",
                'SUCCESS'
            );
        });

        $ticket->user->notify(new \App\Notifications\TicketUpdated($ticket->fresh(), 'status_updated'));

        return back()->with('success', "Incidente #{$ticket->id} resolvido e registrado com sucesso.");
    }
}
