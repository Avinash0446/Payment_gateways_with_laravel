<?php

namespace App\Enums;

enum PaymentGateway :string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case RAZORPAY= 'razorpay';
}
