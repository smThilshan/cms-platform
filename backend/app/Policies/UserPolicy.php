<?php

namespace App\Policies;

use App\Enums\Privilege;
use App\Models\User;

class UserPolicy
{
    public function before(User $user): bool|null
    {
        if (!$user->role) return false;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPrivilege(Privilege::UsersList);
    }

    public function create(User $user): bool
    {
        return $user->hasPrivilege(Privilege::UsersCreate);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPrivilege(Privilege::UsersEdit);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPrivilege(Privilege::UsersDelete);
    }
}
