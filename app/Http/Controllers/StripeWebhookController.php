<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpointSecret = config('cashier.webhook.secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        Log::info('Stripe Webhook Debug', [
            'endpoint_secret' => $endpointSecret,
            'payload' => $payload,
            'signature_header' => $sigHeader,
        ]);
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (UnexpectedValueException $e) {
            return response()->json([
                'error' => 'Invalid payload'
            ], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json([
                'error' => 'Invalid signature'
            ], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $paymentIntent = $session->payment_intent;
            $customerEmail = $session->customer_details->email ?? null;
            Log::info('Stripe Payment Success', [
                'payment_intent' => $paymentIntent,
                'customer_email' => $customerEmail,
            ]);
        }

        return response()->json([
            'success' => true
        ], Response::HTTP_OK);
    }
}
