<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_live_overview_metrics(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $owner = User::factory()->create(['role_id' => $ownerRole->id]);
        $customer = User::factory()->create(['role_id' => $customerRole->id]);

        $sportId = DB::table('sports')->insertGetId([
            'name' => 'Football',
            'slug' => 'football',
            'description' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $fieldId = DB::table('fields')->insertGetId([
            'owner_id' => $owner->id,
            'sport_id' => $sportId,
            'name' => 'Main Field',
            'code' => 'F001',
            'description' => null,
            'address' => '123 Main St',
            'price_per_hour' => 100000,
            'open_time' => '06:00:00',
            'close_time' => '22:00:00',
            'image' => null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $timeSlotId = DB::table('time_slots')->insertGetId([
            'field_id' => $fieldId,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $customer->id,
            'field_id' => $fieldId,
            'time_slot_id' => $timeSlotId,
            'booking_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'pending',
            'note' => null,
            'confirmed_at' => null,
            'cancelled_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('payments')->insert([
            'booking_id' => $bookingId,
            'amount' => 50000,
            'method' => 'cash',
            'status' => 'paid',
            'transaction_code' => 'TXN001',
            'paid_at' => now(),
            'note' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewHas('totalUsers', 3);
        $response->assertViewHas('totalOwners', 1);
        $response->assertViewHas('totalCustomers', 1);
        $response->assertViewHas('pendingBookings', 1);
        $response->assertViewHas('totalRevenue', 50000.0);
    }
}
