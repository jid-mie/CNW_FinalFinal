<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailDeliveryService
{
    public function send(string $to, Mailable $mailable): void
    {
        if (app()->runningUnitTests() || ! config('services.resend.key')) {
            Mail::to($to)->send($mailable);

            return;
        }

        $html = $mailable->render();
        $envelope = $mailable->envelope();
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name') ?: config('app.name');

        $response = Http::timeout(10)
            ->retry(2, 500)
            ->withToken(config('services.resend.key'))
            ->post('https://api.resend.com/emails', [
                'from' => sprintf('%s <%s>', $fromName, $fromAddress),
                'to' => [$to],
                'subject' => $envelope->subject ?? config('app.name'),
                'html' => $html,
            ]);

        if ($response->failed()) {
            Log::error('Resend mail API failed', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Không gửi được email qua Resend API.');
        }
    }
}
