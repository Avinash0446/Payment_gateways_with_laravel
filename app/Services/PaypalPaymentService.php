<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentService implements PaymentGatewayInterface
{
    protected $paypal;
    public function __construct()
    {
        $this->paypal = new PayPalClient();
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->getAccessToken();

        Log::info('paypal');
    }

    public function pay(array $data)
    {
        $response = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $data['amount'],
                    ],
                    'description' => 'paypal_test'
                ]
            ],
            'application_context' => [
                'return_url' => route('payment.success', ['gateway' => 'paypal']),
                'cancel_url' => route('payment.cancel', ['gateway' => 'paypal']),
            ],
        ]);

        Log::info('paypal', ['response' => $response]);
        Log::info('paypal_links', ['response' => $response['links']]);

        foreach ($response['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }
    }

    public function success(array $data)
    {
        if (!isset($data['token'])) {
            return false;
        }

        // Capture payment after approval
        $response = $this->paypal->capturePaymentOrder(
            $data['token']
        );
        Log::info('paypal_success', ['token' => $response]);

        return isset($response['status']) &&
            $response['status'] == 'COMPLETED';
    }

    public function cancel(array $data)
    {
        return false;
    }
}
