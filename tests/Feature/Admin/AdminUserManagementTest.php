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
}
