<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivilegeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_privileges(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->getJson('/api/admin/privileges')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_moderator_cannot_list_privileges(): void
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->getJson('/api/admin/privileges')
            ->assertForbidden();
    }

    public function test_admin_can_create_a_privilege(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson('/api/admin/privileges', [
                'key'         => 'reports.view',
                'description' => 'View reports',
            ])
            ->assertCreated()
            ->assertJsonPath('data.key', 'reports.view');
    }

    public function test_moderator_cannot_create_a_privilege(): void
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->postJson('/api/admin/privileges', [
                'key'         => 'reports.view',
                'description' => 'View reports',
            ])
            ->assertForbidden();
    }

    public function test_unauthenticated_cannot_access_privileges(): void
    {
        $this->getJson('/api/admin/privileges')->assertUnauthorized();
    }
}
