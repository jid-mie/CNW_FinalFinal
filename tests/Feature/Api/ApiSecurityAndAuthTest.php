<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Sport;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiSecurityAndAuthTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $ownerRole;
    private Role $customerRole;
    private User $ownerUser;
    private User $customerUser;
    private Sport $sport;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $this->ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);
        $this->customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);

        // Create users
        $this->ownerUser = User::create([
            'role_id' => $this->ownerRole->id,
            'name' => 'Owner Account',
            'email' => 'owner@api.com',
            'password' => bcrypt('Password123!'),
        ]);

        $this->customerUser = User::create([
            'role_id' => $this->customerRole->id,
            'name' => 'Customer Account',
            'email' => 'customer@api.com',
            'password' => bcrypt('Password123!'),
        ]);

        $this->sport = Sport::create([
            'name' => 'Tennis',
            'slug' => 'tennis',
            'is_active' => true,
        ]);
    }

    /**
     * Test guest cannot access protected routes
     */
    public function test_guest_cannot_access_protected_api_routes(): void
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);

        $response = $this->getJson('/api/owner/stats');
        $response->assertStatus(401);
    }

    /**
     * Test customer cannot access owner API endpoints
     */
    public function test_customer_cannot_access_owner_api_endpoints(): void
    {
        Sanctum::actingAs($this->customerUser, ['access']);

        $response = $this->getJson('/api/owner/stats');
        $response->assertStatus(403);
    }

    /**
     * Test owner cannot view another owner's fields
     */
    public function test_owner_cannot_view_another_owners_fields(): void
    {
        $otherOwner = User::create([
            'role_id' => $this->ownerRole->id,
            'name' => 'Other Owner',
            'email' => 'otherowner@api.com',
            'password' => bcrypt('Password123!'),
        ]);

        $otherField = Field::create([
            'owner_id' => $otherOwner->id,
            'sport_id' => $this->sport->id,
            'name' => 'Sân A',
            'code' => 'SANA',
            'address' => 'Hanoi',
            'price_per_hour' => 100000,
            'open_time' => '08:00',
            'close_time' => '22:00',
            'status' => 'active',
        ]);

        Sanctum::actingAs($this->ownerUser, ['access']);

        // View other owner's field should return 403 Forbidden
        $response = $this->getJson("/api/owner/fields/{$otherField->id}");
        $response->assertStatus(403);
        
        // Update other owner's field should return 403
        $response = $this->putJson("/api/owner/fields/{$otherField->id}", [
            'name' => 'Sân B',
            'code' => 'SANB',
            'sport_id' => $this->sport->id,
            'address' => 'Hanoi 2',
            'price_per_hour' => 120000,
            'open_time' => '08:00',
            'close_time' => '22:00',
            'status' => 'active',
        ]);
        $response->assertStatus(403);

        // Delete other owner's field should return 403
        $response = $this->deleteJson("/api/owner/fields/{$otherField->id}");
        $response->assertStatus(403);
    }

    /**
     * Test owner can view and manage their own fields
     */
    public function test_owner_can_manage_their_own_fields(): void
    {
        Sanctum::actingAs($this->ownerUser, ['access']);

        // 1. Create a field
        $response = $this->postJson('/api/owner/fields', [
            'name' => 'My Field',
            'code' => 'MYFIELD',
            'sport_id' => $this->sport->id,
            'address' => 'Hanoi',
            'price_per_hour' => 100000,
            'open_time' => '08:00',
            'close_time' => '22:00',
            'status' => 'active',
        ]);

        $response->assertStatus(201);
        $fieldId = $response->json('data.id');

        // 2. View details
        $response = $this->getJson("/api/owner/fields/{$fieldId}");
        $response->assertOk();

        // 3. Update field
        $response = $this->putJson("/api/owner/fields/{$fieldId}", [
            'name' => 'My Field Updated',
            'code' => 'MYFIELD',
            'sport_id' => $this->sport->id,
            'address' => 'Hanoi New',
            'price_per_hour' => 120000,
            'open_time' => '08:00',
            'close_time' => '22:00',
            'status' => 'active',
        ]);
        $response->assertOk();

        // 4. Delete field
        $response = $this->deleteJson("/api/owner/fields/{$fieldId}");
        $response->assertOk();
    }

    /**
     * Test full auth workflow via API
     */
    public function test_api_auth_workflow(): void
    {
        // 1. Register a new user
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'API New User',
            'email' => 'newuser@api.com',
            'phone' => '0987654321',
            'address' => 'Vietnam',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $registerResponse->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'newuser@api.com']);

        // 2. Login
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'newuser@api.com',
            'password' => 'Password123!',
        ]);

        $loginResponse->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in'
                ]
            ]);

        $accessToken = $loginResponse->json('data.access_token');
        $refreshToken = $loginResponse->json('data.refresh_token');

        // 3. Get user details
        $this->withToken($accessToken)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.email', 'newuser@api.com');

        // 4. Refresh token
        $refreshResponse = $this->postJson('/api/refresh-token', [
            'refresh_token' => $refreshToken,
        ]);

        $refreshResponse->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in'
                ]
            ]);

        $newAccessToken = $refreshResponse->json('data.access_token');
        $newRefreshToken = $refreshResponse->json('data.refresh_token');

        // 5. Logout
        $this->withToken($newAccessToken)->postJson('/api/logout', [
            'refresh_token' => $newRefreshToken,
        ])->assertOk();
    }

    /**
     * Test cannot register as owner via API
     */
    public function test_cannot_register_as_owner_via_api(): void
    {
        // Try passing role_id
        $response = $this->postJson('/api/register', [
            'name' => 'API Owner Attempt',
            'email' => 'owner_api@example.com',
            'phone' => '0987654321',
            'address' => 'Vietnam',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role_id' => $this->ownerRole->id,
        ]);
        $response->assertStatus(403);

        // Try passing role
        $response = $this->postJson('/api/register', [
            'name' => 'API Owner Attempt 2',
            'email' => 'owner_api2@example.com',
            'phone' => '0987654321',
            'address' => 'Vietnam',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'owner',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test admin can create a user (owner) via API
     */
    public function test_admin_can_create_user_via_api(): void
    {
        $adminUser = User::create([
            'role_id' => $this->adminRole->id,
            'name' => 'Admin Account',
            'email' => 'admin@api.com',
            'password' => bcrypt('Password123!'),
        ]);

        Sanctum::actingAs($adminUser, ['access']);

        $response = $this->postJson('/api/admin/users', [
            'name' => 'Manual Owner User',
            'email' => 'manualowner@api.com',
            'password' => 'Password123!',
            'role_id' => $this->ownerRole->id,
            'phone' => '0987654321',
            'address' => 'Vietnam',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'manualowner@api.com',
            'role_id' => $this->ownerRole->id,
        ]);
    }

    /**
     * Test non-admin cannot create user via API
     */
    public function test_non_admin_cannot_create_user_via_api(): void
    {
        Sanctum::actingAs($this->customerUser, ['access']);

        $response = $this->postJson('/api/admin/users', [
            'name' => 'Should Fail User',
            'email' => 'shouldfail@api.com',
            'password' => 'Password123!',
            'role_id' => $this->ownerRole->id,
        ]);

        $response->assertStatus(403);
    }
}
