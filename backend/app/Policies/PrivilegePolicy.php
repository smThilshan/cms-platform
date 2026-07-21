<?php

namespace App\Policies;

use App\Enums\Privilege as PrivilegeEnum;
use App\Models\Privilege;
use App\Models\User;

class PrivilegePolicy
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
        return $user->hasPrivilege(PrivilegeEnum::PrivilegesList);
    }

    public function create(User $user): bool
    {
        return $user->hasPrivilege(PrivilegeEnum::PrivilegesCreate);
    }

    public function update(User $user, Privilege $privilege): bool
    {
        return $user->hasPrivilege(PrivilegeEnum::PrivilegesEdit);
    }

    public function delete(User $user, Privilege $privilege): bool
    {
        return $user->hasPrivilege(PrivilegeEnum::PrivilegesDelete);
    }
}
