<?php

namespace App\Providers;

use App\Enums\PaymentGateway;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\PaypalPaymentService;
use App\Services\RazorpayPaymentService;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app, $params) {
            $gateway = $params['gateway'];
            Log::info('PAYMENT_SERVICE_PROVIDER', ['gateway' => $gateway]);
            return match ($gateway) {
                PaymentGateway::STRIPE->value =>
                new StripePaymentService(),

                PaymentGateway::PAYPAL->value =>
                new PaypalPaymentService(),

                PaymentGateway::RAZORPAY->value =>
                new RazorpayPaymentService(),

                default =>
                throw new \InvalidArgumentException(
                    "Invalid Payment Gateway"
                ),
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
