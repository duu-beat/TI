<?php

namespace App\Policies;

use App\Models\KnowledgeBase;
use App\Models\User;

class KnowledgeBasePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isMaster() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, KnowledgeBase $article): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, KnowledgeBase $article): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, KnowledgeBase $article): bool
    {
        return $user->isAdmin();
    }
}
