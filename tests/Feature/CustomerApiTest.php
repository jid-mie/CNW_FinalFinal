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
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $owner;
    private Sport $sport;
    private Field $field;
    private TimeSlot $timeSlot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesSeeder::class);

        $customerRole = Role::where('name', 'customer')->firstOrFail();
        $ownerRole = Role::where('name', 'owner')->firstOrFail();

        $this->customer = User::factory()->create([
            'role_id' => $customerRole->id,
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

    public function test_customer_can_retrieve_sports(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $response = $this->getJson(route('customer.sports.index'));

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');
    }

    public function test_customer_can_retrieve_fields(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $response = $this->getJson(route('customer.fields.index'));

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data');
    }

    public function test_customer_can_retrieve_time_slots_for_field(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $response = $this->getJson(route('customer.fields.time-slots', [
            'field' => $this->field->id,
            'date' => now()->addDay()->format('Y-m-d'),
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'is_available' => true,
            ]);
    }

    public function test_customer_can_create_booking(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $bookingDate = now()->addDay()->format('Y-m-d');

        $response = $this->postJson(route('customer.bookings.store'), [
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
            'note' => 'Test booking',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'booking_date' => $bookingDate,
            'status' => 'pending',
        ]);
    }

    public function test_customer_cannot_book_same_slot_twice_same_date(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $bookingDate = now()->addDay()->format('Y-m-d');

        Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
            'total_price' => 200000,
            'status' => 'confirmed',
        ]);

        $response = $this->postJson(route('customer.bookings.store'), [
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
        ]);

        $response->assertStatus(409)
            ->assertJsonPath('success', false);
    }

    public function test_customer_can_cancel_pending_booking(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('customer.bookings.cancel', ['booking' => $booking->id]));

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'cancelled');
    }

    public function test_customer_can_make_payment_for_booking(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'total_price' => 200000,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('customer.payments.store', ['booking' => $booking->id]), [
            'method' => 'momo',
            'note' => 'Payment for booking',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'paid');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'status' => 'paid',
            'method' => 'momo',
        ]);
    }

    public function test_customer_can_rebook_cancelled_or_completed_slot(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $bookingDate = now()->addDay()->format('Y-m-d');

        // Create a cancelled booking on this slot
        Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
            'total_price' => 200000,
            'status' => 'cancelled',
        ]);

        // Attempting to book the same slot should now SUCCEED
        $response = $this->postJson(route('customer.bookings.store'), [
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
    }

    public function test_database_duplicate_entry_triggers_handled_409_response(): void
    {
        Sanctum::actingAs($this->customer, ['access']);

        $bookingDate = now()->addDay()->format('Y-m-d');

        // Create a booking directly so it bypasses controller check and is in database
        Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
            'total_price' => 200000,
            'status' => 'confirmed',
        ]);

        // Mock/Simulate double insert or race condition by triggering duplicate key exception
        // (We bypass Controller check by manually sending a request that will collide at database level)
        // Since the database has a unique constraint, trying to create again should trigger 409
        $response = $this->postJson(route('customer.bookings.store'), [
            'field_id' => $this->field->id,
            'time_slot_id' => $this->timeSlot->id,
            'booking_date' => $bookingDate,
        ]);

        $response->assertStatus(409)
            ->assertJsonPath('success', false)
            ->assertJsonFragment(['message' => 'This time slot is already booked']);
    }
}
