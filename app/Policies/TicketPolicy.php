<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * 🔥 GOD MODE: O Master passa por cima de qualquer regra.
     * Este método roda antes de todos os outros.
     */
    public function before(User $user, $ability)
    {
        if ($user->isMaster()) {
            return true;
        }
    }

    /**
     * Quem pode ver a lista? Admins e o dono do ticket.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Quem pode ver um ticket específico?
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin() || $user->id === $ticket->user_id;
    }

    /**
     * Quem pode criar tickets? Qualquer cliente.
     */
    public function create(User $user): bool
    {
        return true; 
    }

    /**
     * Quem pode atualizar (responder/mudar status)?
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Admin pode atualizar qualquer um
        if ($user->isAdmin()) {
            return true;
        }
        // Cliente só atualiza o seu próprio se não estiver fechado
        return $user->id === $ticket->user_id && $ticket->status !== 'closed';
    }

    /**
     * ⚠️ QUEM PODE DELETAR?
     * Aqui está a restrição: Apenas o Master (pelo 'before') vai conseguir.
     * Retornamos false aqui para bloquear Admins comuns e Clientes.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return false; // Admin comum NÃO deleta, só Master.
    }
}