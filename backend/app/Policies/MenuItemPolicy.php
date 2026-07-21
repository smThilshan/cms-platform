<?php

namespace App\Policies;

use App\Enums\Privilege;
use App\Models\MenuItem;
use App\Models\User;

class MenuItemPolicy
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
        return $user->hasPrivilege(Privilege::MenuList);
    }

    public function create(User $user): bool
    {
        return $user->hasPrivilege(Privilege::MenuCreate);
    }

    public function update(User $user, MenuItem $menuItem): bool
    {
        return $user->hasPrivilege(Privilege::MenuEdit);
    }

    public function delete(User $user, MenuItem $menuItem): bool
    {
        return $user->hasPrivilege(Privilege::MenuDelete);
    }

    public function reorder(User $user): bool
    {
        return $user->hasPrivilege(Privilege::MenuReorder);
    }
}
