<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SeepayWebhookController extends Controller
{
    /**
     * Handle incoming Seepay webhook POST requests.
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('Seepay Webhook received:', $request->all());
        Log::debug('Seepay Webhook headers:', $request->headers->all());

        // 1. Xác thực HMAC-SHA256 signature (ưu tiên) hoặc Bearer token (fallback)
        $expectedToken = config('services.seepay.webhook_token');
        if ($expectedToken) {
            // Check HMAC-SHA256 signature (Seepay gửi trong header X-Sepay-Signature)
            $rawBody = $request->getContent();
            $rawSignature = $request->header('X-Sepay-Signature')
                ?? $request->header('X-Signature')
                ?? $request->header('Signature')
                ?? '';

            if ($rawSignature) {
                // Strip 'sha256=' prefix if present
                $signature = preg_replace('/^sha256=/', '', $rawSignature);
                $timestamp = $request->header('X-Sepay-Timestamp', '');
                // Payload: timestamp + "." + raw body
                $payload = $timestamp ? $timestamp.'.'.$rawBody : $rawBody;
                $expectedSignature = hash_hmac('sha256', $payload, $expectedToken);
                if (! hash_equals($expectedSignature, $signature)) {
                    Log::warning('Seepay Webhook HMAC verification failed. Got: '.$signature.' Expected: '.$expectedSignature.' IP: '.$request->ip());

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid signature',
                    ], 401);
                }
            } else {
                // Fallback: kiểm tra Bearer token / x-seepay-token / query param token
                $authHeader = $request->header('Authorization');
                $headerToken = null;
                if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                    $headerToken = $matches[1];
                }
                $token = $headerToken
                    ?? $request->header('x-seepay-token')
                    ?? $request->input('token');

                if ($token !== $expectedToken) {
                    Log::warning('Seepay Webhook unauthorized attempt. IP: '.$request->ip());

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized',
                    ], 401);
                }
            }
        }

        // 2. Lấy nội dung chuyển khoản và số tiền nhận được
        // Seepay gửi các trường: code, content, transactionContent, transferAmount, accumulatedBalance, referenceCode...
        $content = $request->input('code')
            ?? $request->input('content')
            ?? $request->input('transactionContent')
            ?? '';

        $transferAmount = (float) ($request->input('transferAmount') ?? $request->input('amount') ?? 0);
        $referenceCode = $request->input('referenceCode') ?? $request->input('id') ?? '';

        if (empty($content)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid webhook payload: Missing transaction content',
            ], 422);
        }

        // 3. Phân tích nội dung chuyển khoản để tìm mã Booking (Định dạng PLAYxxxx)
        if (! preg_match('/PLAY(\d+)/i', $content, $matches)) {
            Log::warning("Seepay Webhook: Could not parse booking ID from content: '{$content}'");

            return response()->json([
                'status' => 'error',
                'message' => 'Booking ID not found in transaction content',
            ], 422);
        }

        $bookingId = (int) $matches[1];

        // 4. Tìm kiếm Booking tương ứng
        $booking = Booking::with('payment')->find($bookingId);
        if (! $booking) {
            Log::warning("Seepay Webhook: Booking #{$bookingId} not found");

            return response()->json([
                'status' => 'error',
                'message' => "Booking #{$bookingId} not found",
            ], 404);
        }

        // 5. Nếu booking đã được thanh toán hoặc xác nhận rồi
        if ($booking->status === 'confirmed' || ($booking->payment && $booking->payment->status === 'paid')) {
            Log::info("Seepay Webhook: Booking #{$bookingId} is already paid or confirmed.");

            return response()->json([
                'status' => 'success',
                'message' => 'Booking already processed',
            ], 200);
        }

        // 6. Kiểm tra số tiền chuyển khoản
        if ($transferAmount < (float) $booking->total_price) {
            Log::warning("Seepay Webhook: Booking #{$bookingId} expects ".$booking->total_price.' but received '.$transferAmount);

            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient payment amount',
            ], 400);
        }

        // 7. Cập nhật cơ sở dữ liệu qua Transaction
        try {
            DB::transaction(function () use ($booking, $transferAmount, $referenceCode) {
                // Tạo hoặc cập nhật Payment
                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'amount' => $transferAmount,
                        'method' => 'bank_transfer',
                        'status' => 'paid',
                        'transaction_code' => $referenceCode ?: 'SP-'.Str::upper(Str::random(10)),
                        'paid_at' => now(),
                        'note' => 'Tự động thanh toán qua Seepay Webhook. Ref: '.($referenceCode ?: 'N/A'),
                    ]
                );

                // Cập nhật trạng thái Booking
                $booking->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);
            });

            Log::info("Seepay Webhook: Booking #{$bookingId} successfully confirmed and payment record created.");

            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed and booking confirmed successfully',
            ], 200);

        } catch (\Exception $e) {
            Log::error("Seepay Webhook failed to process transaction for Booking #{$bookingId}: ".$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error while updating booking status',
            ], 500);
        }
    }
}
