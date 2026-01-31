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

    // ✅ Cores atualizadas para o estilo Neon/Dark
    public function color(): string
    {
        return match($this) {
            // Azul Ciano para novo
            self::NEW => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
            
            // Amarelo para andamento
            self::IN_PROGRESS => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            
            // Laranja para aguardando
            self::WAITING_CLIENT => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
            
            // Verde Esmeralda para resolvido
            self::RESOLVED => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            
            // Cinza para fechado
            self::CLOSED => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        };
    }
}