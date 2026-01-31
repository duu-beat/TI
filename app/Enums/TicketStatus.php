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
            self::NEW => 'blue',
            self::IN_PROGRESS => 'yellow',
            self::WAITING_CLIENT => 'orange',
            self::RESOLVED => 'green',
            self::CLOSED => 'gray',
        };
    }
}