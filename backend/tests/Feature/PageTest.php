<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    // ── List ────────────────────────────────────────────────────────────────

    public function test_admin_can_list_pages(): void
    {
        $admin = User::factory()->admin()->create();
        Page::factory(3)->create();

        $this->actingAs($admin)
            ->getJson('/api/admin/pages')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_moderator_can_list_pages(): void
    {
        $moderator = User::factory()->moderator()->create();

        $this->actingAs($moderator)
            ->getJson('/api/admin/pages')
            ->assertOk();
    }

    public function test_unauthenticated_cannot_list_pages(): void
    {
        $this->getJson('/api/admin/pages')->assertUnauthorized();
    }

    // ── Create ───────────────────────────────────────────────────────────────

    public function test_admin_can_create_a_page(): void
    {
        $admin    = User::factory()->admin()->create();
        $menuItem = MenuItem::factory()->create();

        $this->actingAs($admin)
            ->postJson('/api/admin/pages', [
                'title'        => 'Test Page',
                'body'         => '<p>Hello</p>',
                'menu_item_id' => $menuItem->id,
                'status'       => 'published',
            ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Test Page');
    }

    public function test_moderator_can_create_a_page(): void
    {
        $moderator = User::factory()->moderator()->create();
        $menuItem  = MenuItem::factory()->create();

        $this->actingAs($moderator)
            ->postJson('/api/admin/pages', [
                'title'        => 'Mod Page',
                'body'         => '<p>Content</p>',
                'menu_item_id' => $menuItem->id,
                'status'       => 'draft',
            ])
            ->assertCreated();
    }

    public function test_user_with_no_role_cannot_create_page(): void
    {
        $user     = User::factory()->noRole()->create();
        $menuItem = MenuItem::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/admin/pages', [
                'title'        => 'Blocked Page',
                'body'         => '<p>Content</p>',
                'menu_item_id' => $menuItem->id,
                'status'       => 'draft',
            ])
            ->assertForbidden();
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function test_admin_can_update_a_page(): void
    {
        $admin = User::factory()->admin()->create();
        $page  = Page::factory()->create();

        $this->actingAs($admin)
            ->postJson("/api/admin/pages/{$page->id}", [
                'title' => 'Updated Title',
            ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Updated Title');
    }

    public function test_moderator_can_update_a_page(): void
    {
        $moderator = User::factory()->moderator()->create();
        $page      = Page::factory()->create();

        $this->actingAs($moderator)
            ->postJson("/api/admin/pages/{$page->id}", [
                'title' => 'Mod Updated',
            ])
            ->assertOk();
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function test_admin_can_delete_a_page(): void
    {
        $admin = User::factory()->admin()->create();
        $page  = Page::factory()->create();

        $this->actingAs($admin)
            ->deleteJson("/api/admin/pages/{$page->id}")
            ->assertOk();

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_moderator_cannot_delete_a_page(): void
    {
        $moderator = User::factory()->moderator()->create();
        $page      = Page::factory()->create();

        $this->actingAs($moderator)
            ->deleteJson("/api/admin/pages/{$page->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('pages', ['id' => $page->id]);
    }

    public function test_unauthenticated_cannot_delete_a_page(): void
    {
        $page = Page::factory()->create();

        $this->deleteJson("/api/admin/pages/{$page->id}")
            ->assertUnauthorized();
    }
}
