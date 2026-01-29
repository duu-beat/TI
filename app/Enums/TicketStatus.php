<?php

namespace App\Enums;

enum TicketStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case WAITING_CLIENT = 'waiting_client';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

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
            self::NEW => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
            self::IN_PROGRESS => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
            self::WAITING_CLIENT => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            self::RESOLVED => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::CLOSED => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        };
    }
}