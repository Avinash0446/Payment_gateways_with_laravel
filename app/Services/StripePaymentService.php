<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Money\Currency;
use Stripe\StripeClient;

class StripePaymentService implements PaymentGatewayInterface
{
    protected $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('cashier.secret'));
        Log::info('stripe');
    }

    public function pay(array $data)
    {
        $session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'strip_test',
                        ],
                        'unit_amount' => $data['amount'] * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success', [
                'gateway' => 'stripe'
            ]),

            'cancel_url' => route('payment.cancel', [
                'gateway' => 'stripe'
            ]),
        ]);

        return $session->url;
    }
    public function success(array $data)
    {
        Log::info('stripe_success', ['data' => $data]);
        return true;
    }

    public function cancel(array $data)
    {
        return false;
    }
}
