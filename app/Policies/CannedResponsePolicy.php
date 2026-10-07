<?php

namespace App\Policies;

use App\Models\CannedResponse;
use App\Models\User;

class CannedResponsePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isMaster() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, CannedResponse $cannedResponse): bool
    {
        return $user->isAdmin();
    }
}
