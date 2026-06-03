<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Role;
use App\Models\Sport;
use App\Models\TimeSlot;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $owner;
    private Role $customerRole;
    private Sport $sport;
    private Field $field;
    private TimeSlot $timeSlot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesSeeder::class);

        $this->customerRole = Role::where('name', 'customer')->firstOrFail();
        $ownerRole = Role::where('name', 'owner')->firstOrFail();

        $this->customer = User::factory()->create([
            'role_id' => $this->customerRole->id,
            'email' => 'customer_test@example.com',
        ]);

        $this->owner = User::factory()->create([
            'role_id' => $ownerRole->id,
            'email' => 'owner_test@example.com',
        ]);

        $this->sport = Sport::create([
            'name' => 'Football',
            'slug' => 'football',
            'description' => 'Football sport description',
            'is_active' => true,
        ]);

        $this->field = Field::create([
            'owner_id' => $this->owner->id,
            'sport_id' => $this->sport->id,
            'name' => 'Field A',
            'code' => 'FA-01',
            'description' => 'Football field A',
            'address' => 'Da Nang',
            'price_per_hour' => 200000,
            'open_time' => '06:00:00',
            'close_time' => '22:00:00',
            'status' => 'active',
        ]);

        $this->timeSlot = TimeSlot::create([
            'field_id' => $this->field->id,
            'start_time' => '18:00:00',
            'end_time' => '19:30:00',
            'is_active' => true,
        ]);
    }

    public function test_guests_cannot_access_customer_dashboard(): void
    {
        $response = $this->get(route('customer.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_other_roles_cannot_access_customer_dashboard(): void
    {
        $response = $this->actingAs($this->owner)->get(route('customer.dashboard'));
        $response->assertStatus(403);
    }

    public function test_customer_can_access_dashboard_with_dynamic_data(): void
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => now()->addDay()->toDateString(),
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('upcomingBookingsCount', 1);
        $response->assertViewHas('completedBookingsCount', 0);
        $response->assertSee($this->field->name);
        $response->assertSee('CHỜ DUYỆT');
    }

    public function test_customer_can_cancel_their_own_booking(): void
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => now()->addDay()->toDateString(),
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->customer)->post(route('customer.bookings.cancel.web', $booking));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Hủy đặt sân thành công.');

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    public function test_customer_cannot_cancel_others_bookings(): void
    {
        $otherCustomer = User::factory()->create([
            'role_id' => $this->customerRole->id,
            'email' => 'other_customer@example.com',
        ]);

        $booking = Booking::create([
            'customer_id' => $otherCustomer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => now()->addDay()->toDateString(),
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->customer)->post(route('customer.bookings.cancel.web', $booking));

        $response->assertStatus(403);
        $this->assertEquals('pending', $booking->fresh()->status);
    }
}
