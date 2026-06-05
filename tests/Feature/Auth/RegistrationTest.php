<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_cannot_register_as_owner_via_web(): void
    {
        $ownerRole = \App\Models\Role::firstOrCreate(
            ['name' => 'owner'],
            ['display_name' => 'Owner']
        );

        // Try passing role_id
        $response = $this->post('/register', [
            'name' => 'Test Owner',
            'email' => 'owner@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role_id' => $ownerRole->id,
        ]);
        $response->assertStatus(403);

        // Try passing role name
        $response = $this->post('/register', [
            'name' => 'Test Owner',
            'email' => 'owner@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'owner',
        ]);
        $response->assertStatus(403);
    }

}


