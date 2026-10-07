<?php

namespace App\Policies;

use App\Models\ChecklistTemplate;
use App\Models\User;

class ChecklistTemplatePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isMaster() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ChecklistTemplate $checklistTemplate): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ChecklistTemplate $checklistTemplate): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ChecklistTemplate $checklistTemplate): bool
    {
        return $user->isAdmin();
    }
}
