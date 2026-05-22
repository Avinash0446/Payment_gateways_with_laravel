<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class RazorpayPaymentService implements PaymentGatewayInterface
{
    protected $razorpay;
    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
        Log::info('razorpay');
    }

    public function pay(array $data)
    {
        $order = $this->razorpay->order->create([
            'receipt' => uniqid(),
            'amount' => $data['amount'] * 100,
            'currency' => 'INR',
        ]);

        return [
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'currency' => $order['currency']
        ];
    }

    public function success(array $data)
    {
        try {

            $attributes = [
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);

            Log::info('razorpay_success', ['data' => $data]);

            return true;

        } catch (\Exception $e) {
            Log::error('razorpay_error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function cancel(array $data)
    {
        return false;
    }
}
