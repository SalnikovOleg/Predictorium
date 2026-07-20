<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_authenticated_user_can_update_name(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'name' => 'Updated Name',
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'User updated successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'roles'],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_authenticated_user_can_update_email(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'email' => 'newemail@example.com',
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'User updated successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'email' => 'newemail@example.com',
        ]);
    }

    public function test_authenticated_user_can_update_password(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
                'current_password' => 'password123',
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'User updated successfully',
            ]);

        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }

    public function test_update_password_requires_current_password(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('current_password');
    }

    public function test_update_password_rejects_wrong_current_password(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
                'current_password' => 'wrong-password',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Current password is incorrect',
            ]);
    }

    public function test_update_requires_valid_email(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'email' => 'not-an-email',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_update_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'email' => 'taken@example.com',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_update_allows_same_email(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('api.auth.update'), [
                'email' => $this->user->email,
            ]);

        $response->assertOk();
    }

    public function test_unauthenticated_user_cannot_update(): void
    {
        $response = $this->putJson(route('api.auth.update'), [
            'name' => 'Hacker',
        ]);

        $response->assertStatus(401);
    }
}
