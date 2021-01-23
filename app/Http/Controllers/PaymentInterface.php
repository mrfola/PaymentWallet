<?php

namespace App\Http\Controllers;

interface PaymentInterface
{
    public function redirectToGateway();
    public function handleGatewayCallback();
}
