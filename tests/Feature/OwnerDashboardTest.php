<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Sport;
use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $customer;
    private Sport $football;
    private Field $field;
    private TimeSlot $timeSlot;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Roles
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);

        // Setup Users
        $this->owner = User::create([
            'role_id' => $ownerRole->id,
            'name' => 'Test Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->customer = User::create([
            'role_id' => $customerRole->id,
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
        ]);

        // Setup Sport
        $this->football = Sport::create([
            'name' => 'Bóng đá',
            'slug' => 'bong-da',
            'is_active' => true,
        ]);

        // Setup Field
        $this->field = Field::create([
            'owner_id' => $this->owner->id,
            'sport_id' => $this->football->id,
            'name' => 'Sân Số 1',
            'code' => 'S1',
            'address' => 'Test Address',
            'price_per_hour' => 100000,
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
            'status' => 'active',
        ]);

        // Setup TimeSlot
        $this->timeSlot = TimeSlot::create([
            'field_id' => $this->field->id,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'is_active' => true,
        ]);
    }

    public function test_owner_can_view_dashboard_with_stats()
    {
        // 1. Create a booking for today (pending)
        $bookingToday = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => today(),
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        // Create another time slot to avoid overlap
        $anotherTimeSlot = TimeSlot::create([
            'field_id' => $this->field->id,
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'is_active' => true,
        ]);

        // 2. Create another booking for today (completed & paid)
        $bookingTodayCompleted = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $anotherTimeSlot->id,
            'booking_date' => today(),
            'total_price' => 150000,
            'status' => 'completed',
        ]);

        Payment::create([
            'booking_id' => $bookingTodayCompleted->id,
            'amount' => 150000,
            'method' => 'cash',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');
        
        $stats = $response->viewData('stats');
        $this->assertEquals(1, $stats['total_fields']);
        $this->assertEquals(2, $stats['today_bookings']); // 1 pending + 1 completed
        $this->assertEquals(1, $stats['pending_bookings']);
        $this->assertEquals(150000, $stats['today_revenue']);
    }

    public function test_owner_can_confirm_pending_booking()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => today(),
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => 100000,
            'method' => 'bank_transfer',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.bookings.confirm', $booking->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Đã duyệt đặt lịch');

        $this->assertEquals('confirmed', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->confirmed_at);
    }

    public function test_owner_cannot_confirm_unpaid_pending_booking()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => today(),
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.bookings.confirm', $booking->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Không thể duyệt khi chưa thanh toán thành công!');

        $this->assertEquals('pending', $booking->fresh()->status);
        $this->assertNull($booking->fresh()->confirmed_at);
    }

    public function test_owner_can_cancel_pending_booking()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => today(),
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.bookings.cancel', $booking->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Đã huỷ đặt lịch');

        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->cancelled_at);
    }
}
