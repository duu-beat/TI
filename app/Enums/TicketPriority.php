<?php

namespace App\Enums;

enum TicketPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match($this) {
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LOW => 'bg-green-500/10 text-green-400 border border-green-500/20',
            self::MEDIUM => 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20',
            self::HIGH => 'bg-red-500/10 text-red-400 border border-red-500/20',
        };
    }
}