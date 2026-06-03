<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\FailedLoginAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AdminSecurityManagementTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $ownerRole;
    private User $adminUser;
    private User $ownerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $this->ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);

        $this->adminUser = User::create([
            'role_id' => $this->adminRole->id,
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('Password123!'),
        ]);

        $this->ownerUser = User::create([
            'role_id' => $this->ownerRole->id,
            'name' => 'Owner User',
            'email' => 'owner@test.com',
            'password' => bcrypt('Password123!'),
        ]);
    }

    /**
     * Test admin can access security settings index
     */
    public function test_admin_can_access_security_settings(): void
    {
        // Log in as admin
        $response = $this->actingAs($this->adminUser)->get('/admin/security');

        $response->assertOk();
        $response->assertViewIs('admin.security.index');
        $response->assertViewHas('systemChecks');
        $response->assertViewHas('tokens');
        $response->assertViewHas('failedAttempts');
    }

    /**
     * Test owner cannot access security settings
     */
    public function test_owner_cannot_access_security_settings(): void
    {
        // Log in as owner (non-admin)
        $response = $this->actingAs($this->ownerUser)->get('/admin/security');

        $response->assertStatus(403);
    }

    /**
     * Test admin can toggle active state of personal access token
     */
    public function test_admin_can_toggle_personal_access_token_status(): void
    {
        // Create a dummy token for owner user
        $token = $this->ownerUser->createToken('test-token');
        
        $dbToken = PersonalAccessToken::findToken($token->plainTextToken);
        $this->assertTrue((bool)$dbToken->is_active);

        // Toggle state to false
        $response = $this->actingAs($this->adminUser)
            ->post("/admin/security/tokens/{$dbToken->id}/toggle");

        $response->assertRedirect('/admin/security');
        $this->assertFalse((bool)$dbToken->refresh()->is_active);

        // Toggle state back to true
        $response = $this->actingAs($this->adminUser)
            ->post("/admin/security/tokens/{$dbToken->id}/toggle");

        $response->assertRedirect('/admin/security');
        $this->assertTrue((bool)$dbToken->refresh()->is_active);
    }

    /**
     * Test admin can clear failed login logs
     */
    public function test_admin_can_clear_failed_login_logs(): void
    {
        // Seed some failed login attempts
        FailedLoginAttempt::create([
            'email' => 'hacker@test.com',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla',
            'attempted_at' => now(),
        ]);

        $this->assertDatabaseCount('failed_login_attempts', 1);

        // Clear logs
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/security/logs/clear');

        $response->assertRedirect('/admin/security');
        $this->assertDatabaseCount('failed_login_attempts', 0);
    }
}
