<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminBookingManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    private int $sportId;

    private int $fieldId;

    private int $timeSlotId;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $owner = User::factory()->create(['role_id' => $ownerRole->id]);
        $this->customer = User::factory()->create(['role_id' => $customerRole->id]);

        $this->sportId = DB::table('sports')->insertGetId([
            'name' => 'Football',
            'slug' => 'football',
            'description' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->fieldId = DB::table('fields')->insertGetId([
            'owner_id' => $owner->id,
            'sport_id' => $this->sportId,
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
        ]);

        $this->timeSlotId = DB::table('time_slots')->insertGetId([
            'field_id' => $this->fieldId,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_admin_can_view_bookings_list(): void
    {
        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $this->customer->id,
            'field_id' => $this->fieldId,
            'time_slot_id' => $this->timeSlotId,
            'booking_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/bookings');

        $response->assertOk();
        $response->assertSee('Main Field');
        $response->assertSee('CHỜ DUYỆT');
    }

    public function test_admin_can_view_pending_bookings(): void
    {
        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $this->customer->id,
            'field_id' => $this->fieldId,
            'time_slot_id' => $this->timeSlotId,
            'booking_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/bookings/pending');

        $response->assertOk();
        $response->assertSee('Duyệt ngay');
    }

    public function test_admin_can_view_bookings_calendar(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/bookings/calendar');
        $response->assertOk();
    }

    public function test_admin_can_confirm_booking(): void
    {
        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $this->customer->id,
            'field_id' => $this->fieldId,
            'time_slot_id' => $this->timeSlotId,
            'booking_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/bookings/{$bookingId}/confirm");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'confirmed',
        ]);
    }

    public function test_admin_can_cancel_booking(): void
    {
        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $this->customer->id,
            'field_id' => $this->fieldId,
            'time_slot_id' => $this->timeSlotId,
            'booking_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/bookings/{$bookingId}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'cancelled',
        ]);
    }

    public function test_admin_can_checkin_booking(): void
    {
        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $this->customer->id,
            'field_id' => $this->fieldId,
            'time_slot_id' => $this->timeSlotId,
            'booking_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/bookings/{$bookingId}/checkin");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'completed',
        ]);
    }
}
