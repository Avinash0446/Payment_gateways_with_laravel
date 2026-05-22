<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaypalWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        Log::info('PayPal Webhook Received', [
            'payload' => $payload,
        ]);

        $eventType = $payload['event_type'] ?? null;

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $resource = $payload['resource'] ?? [];

            $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;
            $captureId = $resource['id'] ?? null;
            $amount = $resource['amount']['value'] ?? null;

            Log::info('Payment Success', [
                'order_id' => $orderId,
                'capture_id' => $captureId,
                'amount' => $amount,
            ]);
        }
        return response()->json(['status' => 'ok']);
    }
}
