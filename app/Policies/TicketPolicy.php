<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; 
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {

        // 1. Admin vê tudo
        if ($user->isAdmin()) {
            return true;
        }

        // 2. Cliente vê apenas os seus
        return $user->id == $ticket->user_id;
    } // 👈 FALTAVA ESTA CHAVE AQUI

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}