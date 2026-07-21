<?php

namespace App\Policies;

use App\Enums\Privilege;
use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if (! $user->role) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPrivilege(Privilege::PagesList);
    }

    public function create(User $user): bool
    {
        return $user->hasPrivilege(Privilege::PagesCreate);
    }

    public function update(User $user, Page $page): bool
    {
        return $user->hasPrivilege(Privilege::PagesEdit);
    }

    public function delete(User $user, Page $page): bool
    {
        return $user->hasPrivilege(Privilege::PagesDelete);
    }
}
