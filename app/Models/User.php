<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\SoftDeletes; // Recomendado adicionar

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    // use SoftDeletes; // Descomente se criou a migration de soft deletes

    // 👑 CONSTANTES DE NÍVEL
    const ROLE_CLIENT = 'client';
    const ROLE_ADMIN = 'admin';
    const ROLE_MASTER = 'master'; // O nível de Segurança

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔥 HELPER METHODS 🔥
    
    // Verifica se é o Mestre Supremo (Segurança/Dev)
    public function isMaster(): bool
    {
        return $this->role === self::ROLE_MASTER;
    }

    // Verifica se é Admin (O Master também tem acesso de Admin)
    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MASTER]);
    }

    // Verifica se é Cliente
    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    // Escopo para queries
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', [self::ROLE_ADMIN, self::ROLE_MASTER]);
    }
}