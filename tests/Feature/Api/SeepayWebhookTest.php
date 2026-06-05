<?php

namespace Tests\Feature\Api;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Sport;
use App\Models\TimeSlot;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SeepayWebhookTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        // Chạy RolesSeeder
        $this->seed(RolesSeeder::class);

        $customerRole = Role::where('name', 'customer')->firstOrFail();
        $ownerRole = Role::where('name', 'owner')->firstOrFail();

        // Cấu hình mã token giả lập cho Seepay
        Config::set('services.seepay.webhook_token', 'test_secret_token');
        Config::set('services.seepay.bank_id', 'vietinbank');
        Config::set('services.seepay.bank_account', '113000045678');
        Config::set('services.seepay.account_name', 'NGUYEN VAN A');

        // Tạo dữ liệu mẫu
        $owner = User::factory()->create(['role_id' => $ownerRole->id]);
        $this->customer = User::factory()->create(['role_id' => $customerRole->id]);

        $sport = Sport::create(['name' => 'Bóng đá', 'slug' => 'bong-da', 'icon' => 'soccer']);
        $field = Field::create([
            'owner_id' => $owner->id,
            'sport_id' => $sport->id,
            'name' => 'Sân 1',
            'code' => 'FA-01',
            'description' => 'Sân 1 mô tả',
            'address' => 'Hà Nội',
            'open_time' => '06:00:00',
            'close_time' => '22:00:00',
            'price_per_hour' => 150000,
            'status' => 'active',
        ]);

        $timeSlot = TimeSlot::create([
            'field_id' => $field->id,
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'is_active' => true,
        ]);

        $this->booking = Booking::create([
            'customer_id' => $this->customer->id,
            'field_id' => $field->id,
            'time_slot_id' => $timeSlot->id,
            'booking_date' => now()->addDay()->toDateString(),
            'total_price' => 150000,
            'status' => 'pending',
        ]);
    }

    public function test_it_rejects_webhook_with_invalid_token()
    {
        $response = $this->postJson('/api/webhooks/seepay', [
            'code' => 'PLAY' . $this->booking->id,
            'transferAmount' => 150000,
        ], [
            'Authorization' => 'Bearer wrong_token'
        ]);

        $response->assertStatus(401);
        $response->assertJsonPath('status', 'error');
    }

    public function test_it_rejects_webhook_if_no_booking_id_in_content()
    {
        $response = $this->postJson('/api/webhooks/seepay', [
            'code' => 'Chuyen khoan dat san da bong',
            'transferAmount' => 150000,
        ], [
            'Authorization' => 'Bearer test_secret_token'
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('status', 'error');
        $response->assertJsonPath('message', 'Booking ID not found in transaction content');
    }

    public function test_it_returns_404_if_booking_does_not_exist()
    {
        $response = $this->postJson('/api/webhooks/seepay', [
            'code' => 'PLAY99999',
            'transferAmount' => 150000,
        ], [
            'Authorization' => 'Bearer test_secret_token'
        ]);

        $response->assertStatus(404);
        $response->assertJsonPath('status', 'error');
    }

    public function test_it_returns_success_if_booking_is_already_confirmed()
    {
        $this->booking->update(['status' => 'confirmed']);

        $response = $this->postJson('/api/webhooks/seepay', [
            'code' => 'PLAY' . $this->booking->id,
            'transferAmount' => 150000,
        ], [
            'Authorization' => 'Bearer test_secret_token'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('message', 'Booking already processed');
    }

    public function test_it_rejects_webhook_if_amount_is_insufficient()
    {
        $response = $this->postJson('/api/webhooks/seepay', [
            'code' => 'PLAY' . $this->booking->id,
            'transferAmount' => 100000, // Thấp hơn 150000
        ], [
            'Authorization' => 'Bearer test_secret_token'
        ]);

        $response->assertStatus(400);
        $response->assertJsonPath('status', 'error');
        $response->assertJsonPath('message', 'Insufficient payment amount');
    }

    public function test_it_successfully_processes_webhook_and_confirms_booking()
    {
        $response = $this->postJson('/api/webhooks/seepay', [
            'code' => 'PLAY' . $this->booking->id,
            'transferAmount' => 150000,
            'referenceCode' => 'SP-TRX12345678',
        ], [
            'Authorization' => 'Bearer test_secret_token'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');

        // Kiểm tra Database xem Booking đã đổi thành confirmed chưa
        $this->booking->refresh();
        $this->assertEquals('confirmed', $this->booking->status);
        $this->assertNotNull($this->booking->confirmed_at);

        // Kiểm tra xem đã tạo Payment record chưa
        $this->assertDatabaseHas('payments', [
            'booking_id' => $this->booking->id,
            'amount' => 150000,
            'status' => 'paid',
            'method' => 'bank_transfer',
            'transaction_code' => 'SP-TRX12345678',
        ]);
    }
}
