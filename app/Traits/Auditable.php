<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
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

            $description = "Atualizou o registro #{$model->id}. Alterações: ";
            foreach ($changes as $key => $value) {
                $oldValue = $model->getOriginal($key);
                
                // Tratar Enums
                $oldStr = $oldValue instanceof \UnitEnum ? ($oldValue->value ?? $oldValue->name) : (string)$oldValue;
                $newStr = $value instanceof \UnitEnum ? ($value->value ?? $value->name) : (string)$value;
                
                $description .= "[{$key}: {$oldStr} -> {$newStr}] ";
            }

            $model->recordAudit('UPDATE', trim($description));
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
            'description' => $description,
            'ip_address' => request()->ip(),
            'level' => 'INFO',
        ]);
    }
}
