<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->admin()->create(['email' => 'admin@test.com']);

        $this->postJson('/api/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'privileges'],
            ]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'user@test.com']);

        $this->postJson('/api/login', [
            'email'    => 'user@test.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized();
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $this->postJson('/api/login', [
            'email'    => 'nobody@test.com',
            'password' => 'password',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_fetch_me(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'role', 'privileges'],
            ]);
    }

    public function test_unauthenticated_me_returns_401(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->postJson('/api/logout')
            ->assertOk();
    }
}
