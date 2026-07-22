<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_endpoint_returns_tree(): void
    {
        MenuItem::factory(2)->create();

        $this->getJson('/api/menu')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_menu_requires_no_authentication(): void
    {
        $this->getJson('/api/menu')->assertOk();
    }

    public function test_published_page_is_accessible_by_slug(): void
    {
        $page = Page::factory()->published()->create(['slug' => 'about-us']);

        $this->getJson('/api/pages/about-us')
            ->assertOk()
            ->assertJsonPath('data.slug', 'about-us');
    }

    public function test_draft_page_returns_404(): void
    {
        Page::factory()->draft()->create(['slug' => 'hidden-page']);

        $this->getJson('/api/pages/hidden-page')
            ->assertNotFound();
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->getJson('/api/pages/does-not-exist')
            ->assertNotFound();
    }
}
