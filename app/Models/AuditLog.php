<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',       // Ex: "Login", "Update"
        'description',  // Detalhes: "Alterou a senha do usuário X"
        'ip_address',
        'level'         // 'INFO', 'WARNING', 'DANGER', 'SUCCESS'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper para gravar logs rapidamente de qualquer lugar.
     */
    public static function record(string $action, ?string $description = null, string $level = 'INFO'): void
    {
        self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'level'       => $level,
        ]);
    }
}