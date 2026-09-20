<?php

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{

    public function createCheckoutSession(Order $order): string;


    public function handleWebhook(Request $request);
}