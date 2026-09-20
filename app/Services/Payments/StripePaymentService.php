<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;  



class StripePaymentService implements PaymentGatewayInterface 
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
    }

    public function createCheckoutSession(Order $order): string
{
    $response = Http::withToken($this->secretKey)
        ->asForm()
        ->post('https://api.stripe.com/v1/checkout/sessions', [
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => "Order #" . $order->id,
                        ],
                        'unit_amount' => (int) (($order->total_price ?? 10) * 100),
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => url('/api/payment/success?order_id=' . $order->id),
            'cancel_url'  => url('/api/payment/cancel?order_id=' . $order->id),
            'metadata'    => [
                'order_id' => $order->id,
            ],
        ]);

    if ($response->successful()) {
        return $response->json()['url'];
    }

    // هنا تعديل طباعة الخطأ القادم من Stripe مباشرة لمعرفته بدقة
    Log::error('Stripe Checkout Error: ' . $response->body());
    throw new \Exception('Stripe API Error: ' . $response->body());
}
    public function handleWebhook(Request $request)
    {
        $event = $request->all();

        if (isset($event['type']) && $event['type'] === 'checkout.session.completed') {
            $session = $event['data']['object'];
            $orderId = $session['metadata']['order_id'] ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->status !== 'paid') {
                    $order->update([
                        'status'         => 'processing',
                        'payment_status' => 'paid',
                    ]);

                    Log::info("Order #{$orderId} payment successfully verified via Stripe Webhook.");
                }
            }
        }

        return response()->json(['status' => 'success']);
    }



}