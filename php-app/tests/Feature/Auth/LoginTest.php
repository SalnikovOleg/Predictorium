<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.auth.login'), [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'roles'],
                'token',
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.auth.login'), [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_login_with_nonexistent_email(): void
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_login_requires_email(): void
    {
        $response = $this->postJson(route('api.auth.login'), [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_login_requires_password(): void
    {
        $response = $this->postJson(route('api.auth.login'), [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_authenticated_user_can_access_me(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('api.auth.me'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'roles'],
            ]);
    }

    public function test_unauthenticated_user_cannot_access_me(): void
    {
        $response = $this->getJson(route('api.auth.me'));

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('api.auth.logout'));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Logged out successfully',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
