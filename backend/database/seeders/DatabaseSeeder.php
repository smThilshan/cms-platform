<?php

namespace Database\Seeders;

use App\Enums\Privilege as PrivilegeEnum;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed all privilege rows from the enum — single source of truth
        foreach (PrivilegeEnum::cases() as $case) {
            Privilege::firstOrCreate(
                ['key' => $case->value],
                ['description' => $case->description()]
            );
        }

        $allPrivileges = Privilege::all();

        // Admin: every privilege
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin']
        );
        $adminRole->privileges()->sync($allPrivileges->pluck('id'));

        // Moderator: every privilege EXCEPT delete ones
        $moderatorRole = Role::firstOrCreate(
            ['slug' => 'moderator'],
            ['name' => 'Moderator']
        );
        $moderatorPrivileges = $allPrivileges->filter(
            fn (Privilege $p) => in_array($p->key, array_map(
                fn (PrivilegeEnum $e) => $e->value,
                PrivilegeEnum::moderatorPrivileges()
            ))
        );
        $moderatorRole->privileges()->sync($moderatorPrivileges->pluck('id'));

        // Seeded users (credentials documented in README)
        User::firstOrCreate(
            ['email' => 'admin@cms.test'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
                'role_id'  => $adminRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'moderator@cms.test'],
            [
                'name'     => 'Moderator User',
                'password' => Hash::make('password'),
                'role_id'  => $moderatorRole->id,
            ]
        );
    }
}
