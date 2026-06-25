<?php

namespace Tests\Feature\Api;

use App\Mail\SendOtpMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendOtpSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_otp_endpoint_sends_mail_and_caches_code(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/register/send-otp', [
            'email' => 'otp_smoke@example.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Mã OTP đã được gửi thành công đến email của bạn.');

        $this->assertSame('123456', Cache::get('otp_otp_smoke@example.com'));

        Mail::assertSent(SendOtpMail::class, function (SendOtpMail $mail) {
            return $mail->hasTo('otp_smoke@example.com') && $mail->code === '123456';
        });
    }
}
