<?php

namespace App\Services;

use App\Models\Role;

class RoleService
{
    public function syncPrivileges(Role $role, array $privilegeIds): void
    {
        $role->privileges()->sync($privilegeIds);
    }
}
