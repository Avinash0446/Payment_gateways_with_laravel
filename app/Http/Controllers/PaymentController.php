<?php

namespace App\Http\Controllers;

use App\Interfaces\PaymentGatewayInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    public function process(Request $request)
    {
        try {
            Log::info('payment_process', ['request' => $request->all()]);

            $paymentService = app()->make(PaymentGatewayInterface::class,['gateway' => $request->payment_method]);
            
            Log::info('payment_service', ['service' => get_class($paymentService)]);
            $response = $paymentService->pay([
                'amount' => $request->amount
            ]);
            // Razorpay case
            if ($request->payment_method === 'razorpay') {
                return view(
                    'payments.razorpay',
                    compact('response')
                );
            }
            // Stripe + PayPal
            return redirect($response);

        } catch (Exception $e) {
            return back()->withErrors([
                'payment' => $e->getMessage()
            ]);
        }
    }

    public function success($gateway, Request $request)
    {
        $paymentService = app()->make(
            PaymentGatewayInterface::class,
            [
                'gateway' => $gateway
            ]
        );

        $response = $paymentService->success($request->all());
        Log::info('payment_success', ['gateway' => $gateway, 'response' => $response]);
        if ($response) {
            return redirect()->route('home')->with('success', 'Payment Success');
        } else {
            return redirect()->route('home')->with('error', 'Payment failed');
        }

    }

    public function cancel($gateway, Request $request)
    {
        $paymentService = app()->make(
            PaymentGatewayInterface::class,
            [
                'gateway' => $gateway
            ]
        );

        Log::info('payment_cancel', ['gateway' => $gateway, 'request' => $request->all()]);
        $paymentService->cancel($request->all());

        return redirect()->route('home')->with('error', 'Payment Cancelled');
    }
}
