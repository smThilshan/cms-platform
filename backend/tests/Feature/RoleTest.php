<?php

namespace Tests\Feature;

use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_roles(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->getJson('/api/admin/roles')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_moderator_cannot_list_roles(): void
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->getJson('/api/admin/roles')
            ->assertForbidden();
    }

    public function test_admin_can_create_a_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson('/api/admin/roles', [
                'name' => 'Editor',
                'slug' => 'editor',
            ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'editor');
    }

    public function test_admin_can_create_role_with_privileges(): void
    {
        $admin     = User::factory()->admin()->create();
        $privilege = Privilege::where('key', 'pages.list')->first();

        $this->actingAs($admin)
            ->postJson('/api/admin/roles', [
                'name'          => 'Viewer',
                'slug'          => 'viewer',
                'privilege_ids' => [$privilege->id],
            ])
            ->assertCreated();

        $role = Role::where('slug', 'viewer')->first();
        $this->assertTrue($role->privileges->contains('id', $privilege->id));
    }

    public function test_moderator_cannot_create_roles(): void
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->postJson('/api/admin/roles', [
                'name' => 'Something',
                'slug' => 'something',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_delete_a_role(): void
    {
        $admin = User::factory()->admin()->create();
        $role  = Role::factory()->create();

        $this->actingAs($admin)
            ->deleteJson("/api/admin/roles/{$role->id}")
            ->assertOk();

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_moderator_cannot_delete_a_role(): void
    {
        $moderator = User::factory()->moderator()->create();
        $role      = Role::factory()->create();

        $this->actingAs($moderator)
            ->deleteJson("/api/admin/roles/{$role->id}")
            ->assertForbidden();
    }
}
