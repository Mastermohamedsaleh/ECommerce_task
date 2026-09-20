<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
   protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function checkout(Request $request, Order $order)
    {
        try {
            $checkoutUrl = $this->paymentGateway->createCheckoutSession($order);

            return response()->json([
                'status'       => 'success',
                'message'      => 'Checkout session created successfully',
                'checkout_url' => $checkoutUrl,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        return $this->paymentGateway->handleWebhook($request);
    }

    public function success(Request $request)
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'Payment completed successfully! Order #' . $request->query('order_id'),
        ]);
    }

    public function cancel(Request $request)
    {
        return response()->json([
            'status'  => 'cancelled',
            'message' => 'Payment was cancelled for Order #' . $request->query('order_id'),
        ]);
    }




}
