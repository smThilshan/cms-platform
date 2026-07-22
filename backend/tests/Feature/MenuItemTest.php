<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_menu_item(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson('/api/admin/menu-items', [
                'title' => 'Company',
            ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Company');
    }

    public function test_moderator_can_create_a_menu_item(): void
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->postJson('/api/admin/menu-items', [
                'title' => 'About',
            ])
            ->assertCreated();
    }

    public function test_admin_can_reorder_menu_items(): void
    {
        $admin = User::factory()->admin()->create();
        $a     = MenuItem::factory()->create(['order' => 1]);
        $b     = MenuItem::factory()->create(['order' => 2]);

        $this->actingAs($admin)
            ->postJson('/api/admin/menu-items/reorder', [
                'items' => [
                    ['id' => $a->id, 'order' => 2, 'parent_id' => null],
                    ['id' => $b->id, 'order' => 1, 'parent_id' => null],
                ],
            ])
            ->assertOk();

        $this->assertDatabaseHas('menu_items', ['id' => $a->id, 'order' => 2]);
        $this->assertDatabaseHas('menu_items', ['id' => $b->id, 'order' => 1]);
    }

    public function test_moderator_can_reorder_menu_items(): void
    {
        $moderator = User::factory()->moderator()->create();
        $item      = MenuItem::factory()->create(['order' => 1]);

        $this->actingAs($moderator)
            ->postJson('/api/admin/menu-items/reorder', [
                'items' => [
                    ['id' => $item->id, 'order' => 5, 'parent_id' => null],
                ],
            ])
            ->assertOk();
    }

    public function test_admin_can_delete_a_menu_item(): void
    {
        $admin = User::factory()->admin()->create();
        $item  = MenuItem::factory()->create();

        $this->actingAs($admin)
            ->deleteJson("/api/admin/menu-items/{$item->id}")
            ->assertOk();

        $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
    }

    public function test_moderator_cannot_delete_a_menu_item(): void
    {
        $moderator = User::factory()->moderator()->create();
        $item      = MenuItem::factory()->create();

        $this->actingAs($moderator)
            ->deleteJson("/api/admin/menu-items/{$item->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('menu_items', ['id' => $item->id]);
    }
}
