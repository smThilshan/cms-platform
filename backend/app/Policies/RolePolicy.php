<?php

namespace App\Policies;

use App\Enums\Privilege;
use App\Models\Role;
use App\Models\User;

class RolePolicy
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
        return $user->hasPrivilege(Privilege::RolesList);
    }

    public function create(User $user): bool
    {
        return $user->hasPrivilege(Privilege::RolesCreate);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPrivilege(Privilege::RolesEdit);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPrivilege(Privilege::RolesDelete);
    }
}
