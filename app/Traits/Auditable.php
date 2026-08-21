<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Auditable
{
    /**
     * Campos que nunca podem ser expostos em logs de auditoria.
     */
    private const SENSITIVE_AUDIT_ATTRIBUTES = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Boot the trait to listen for model events.
     */
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            $model->recordAudit('CREATE', "Criou o registro #{$model->id}");
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            
            // Ignorar campos de timestamp
            unset($changes['updated_at']);
            
            if (empty($changes)) {
                return;
            }

            $descriptions = [];
            foreach ($changes as $key => $value) {
                if (in_array($key, self::SENSITIVE_AUDIT_ATTRIBUTES, true)) {
                    $descriptions[] = "[{$key}: conteúdo protegido]";
                    continue;
                }

                $oldValue = $model->getOriginal($key);

                // Trata enums e reduz valores extensos sem perder o contexto da alteração.
                $oldStr = $oldValue instanceof \UnitEnum ? ($oldValue->value ?? $oldValue->name) : (string) $oldValue;
                $newStr = $value instanceof \UnitEnum ? ($value->value ?? $value->name) : (string) $value;
                $descriptions[] = sprintf(
                    '[%s: %s -> %s]',
                    $key,
                    Str::limit($oldStr, 70, '…'),
                    Str::limit($newStr, 70, '…')
                );
            }

            $model->recordAudit('UPDATE', "Atualizou o registro #{$model->id}. Alterações: " . implode(' ', $descriptions));
        });

        static::deleted(function (Model $model) {
            $model->recordAudit('DELETE', "Removeu o registro #{$model->id}");
        });
    }

    /**
     * Grava o log de auditoria
     */
    protected function recordAudit(string $action, string $description): void
    {
        // Só grava se houver um usuário autenticado ou for uma ação de sistema
        $userId = Auth::id();
        
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action . ' ' . class_basename($this),
            'description' => Str::limit($description, 250, '…'),
            'ip_address' => request()->ip(),
            'level' => 'INFO',
        ]);
    }
}
