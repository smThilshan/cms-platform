<?php

namespace Database\Factories;

use App\Enums\Privilege as PrivilegeEnum;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $role = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);

            $this->seedPrivilegesIfMissing();

            $role->privileges()->sync(Privilege::all()->pluck('id'));
            $user->update(['role_id' => $role->id]);
            $user->setRelation('role', $role->load('privileges'));
        });
    }

    public function moderator(): static
    {
        return $this->afterCreating(function (User $user) {
            $role = Role::firstOrCreate(['slug' => 'moderator'], ['name' => 'Moderator']);

            $this->seedPrivilegesIfMissing();

            $moderatorKeys = array_map(fn ($e) => $e->value, PrivilegeEnum::moderatorPrivileges());
            $ids = Privilege::whereIn('key', $moderatorKeys)->pluck('id');
            $role->privileges()->sync($ids);

            $user->update(['role_id' => $role->id]);
            $user->setRelation('role', $role->load('privileges'));
        });
    }

    public function noRole(): static
    {
        return $this->state(['role_id' => null]);
    }

    private function seedPrivilegesIfMissing(): void
    {
        if (Privilege::count() === 0) {
            foreach (PrivilegeEnum::cases() as $case) {
                Privilege::firstOrCreate(
                    ['key' => $case->value],
                    ['description' => $case->description()]
                );
            }
        }
    }
}
