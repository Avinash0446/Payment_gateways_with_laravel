<?php

namespace App\Interfaces;

interface PaymentGatewayInterface
{
    public function pay(array $data);

    public function success(array $data);

    public function cancel(array $data);
}
