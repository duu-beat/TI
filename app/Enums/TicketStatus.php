<?php

namespace App\Enums;

enum TicketStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case WAITING_CLIENT = 'waiting_client';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    // ✅ NOVO: Método para facilitar queries de "chamados abertos"
    public static function openStatuses(): array
    {
        return [
            self::NEW->value,
            self::IN_PROGRESS->value,
            self::WAITING_CLIENT->value,
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Novo',
            self::IN_PROGRESS => 'Em Andamento',
            self::WAITING_CLIENT => 'Aguardando Cliente',
            self::RESOLVED => 'Resolvido',
            self::CLOSED => 'Fechado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'border-blue-500 text-blue-400 bg-blue-500/10',
            self::IN_PROGRESS => 'border-yellow-500 text-yellow-400 bg-yellow-500/10',
            self::WAITING_CLIENT => 'border-purple-500 text-purple-400 bg-purple-500/10',
            self::RESOLVED => 'border-emerald-500 text-emerald-400 bg-emerald-500/10',
            self::CLOSED => 'border-slate-500 text-slate-400 bg-slate-500/10',
        };
    }
}