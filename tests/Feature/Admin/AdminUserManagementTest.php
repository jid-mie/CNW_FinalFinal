<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_create_only_customer_and_owner_accounts(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $owner = User::factory()->create(['role_id' => $ownerRole->id, 'name' => 'Owner User']);
        $customer = User::factory()->create(['role_id' => $customerRole->id, 'name' => 'Customer User']);
        User::factory()->create(['role_id' => $adminRole->id, 'name' => 'Hidden Admin']);

        $indexResponse = $this->actingAs($admin)->get('/admin/users');

        $indexResponse->assertOk();
        $indexResponse->assertViewHas('users', function ($users) use ($owner, $customer): bool {
            return $users->total() === 2
                && $users->getCollection()->contains('id', $owner->id)
                && $users->getCollection()->contains('id', $customer->id);
        });

        $createResponse = $this->actingAs($admin)->get('/admin/users/create');

        $createResponse->assertOk();
        $createResponse->assertViewHas('roles', function ($roles): bool {
            return $roles->count() === 2
                && $roles->pluck('name')->sort()->values()->all() === ['customer', 'owner'];
        });

        $storeResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Managed User',
            'email' => 'managed@example.com',
            'password' => 'password123',
            'role_id' => $ownerRole->id,
            'phone' => '0900000000',
            'address' => 'Hanoi',
        ]);

        $storeResponse->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'managed@example.com',
            'role_id' => $ownerRole->id,
            'phone' => '0900000000',
            'address' => 'Hanoi',
        ]);

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Invalid Admin Role',
            'email' => 'invalid-admin@example.com',
            'password' => 'password123',
            'role_id' => $adminRole->id,
        ])->assertSessionHasErrors('role_id');
    }

    public function test_admin_can_update_user_without_changing_password(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create([
            'role_id' => $ownerRole->id,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => 'password',
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role_id' => $ownerRole->id,
            'password' => '',
        ]);

        $response->assertRedirect('/admin/users');
        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertTrue(\Hash::check('password', $user->password)); // should not have changed
    }

    public function test_admin_can_update_user_and_change_password(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create([
            'role_id' => $ownerRole->id,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => 'OldPasswordHash123',
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role_id' => $ownerRole->id,
            'password' => 'NewPasswordSecure123',
        ]);

        $response->assertRedirect('/admin/users');
        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertNotEquals('OldPasswordHash123', $user->password);
        $this->assertTrue(\Hash::check('NewPasswordSecure123', $user->password));
    }

    public function test_admin_can_delete_user(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create([
            'role_id' => $ownerRole->id,
            'name' => 'ToDelete User',
            'email' => 'todelete@example.com',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertSoftDeleted($user);
    }
}


