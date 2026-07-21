<?php

namespace App\Traits;

use App\Enums\Privilege;

trait HasPrivileges
{
    public function hasPrivilege(Privilege $privilege): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->privileges
            ->contains('key', $privilege->value);
    }

    public function privilegeKeys(): array
    {
        if (! $this->role) {
            return [];
        }

        return $this->role->privileges
            ->pluck('key')
            ->all();
    }
}
