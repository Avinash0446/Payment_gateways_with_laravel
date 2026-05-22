<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $secret = config('services.razorpay.webhook_secret');

        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        // Verify signature
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        if ($expectedSignature !== $signature) {
            Log::warning('Razorpay webhook signature mismatch');
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = json_decode($payload, true);

        if (!isset($data['event'])) {
            Log::error('Razorpay webhook missing event', $data);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Handle events
        switch ($data['event']) {
            case 'payment.captured':
                $payment = $data['payload']['payment']['entity'] ?? null;

                Log::info('Payment Captured', [
                    'payment_id' => $payment['id'] ?? null,
                    'order_id' => $payment['order_id'] ?? null,
                    'amount' => $payment['amount'] ?? null,
                    'email' => $payment['email'] ?? null,
                ]);

                break;

            case 'payment.failed':
                Log::info('Payment Failed', $data);
                break;

            case 'order.paid':
                Log::info('Order paid', $data);
                break;

            default:
                Log::info('Unhandled Razorpay event', [
                    'event' => $data['event']
                ]);
        }

        return response()->json(['status' => 'ok']);
    }
}