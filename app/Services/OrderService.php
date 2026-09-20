<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{ 
protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function processCheckout($user)
    { 
         $cartItems = $this->orderRepository->getUserCart($user->id); 

         if ($cartItems->isEmpty()) {
            throw new Exception('السلة فارغة، لا يمكن إتمام الطلب', 400);
        }
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                throw new Exception("الكمية المطلوبة للمنتج ({$item->product->name}) غير متوفرة في المخزون", 400);
            }
            $totalAmount += $item->product->price * $item->quantity;
            return DB::transaction(function () use ($user, $cartItems, $totalAmount) {
            $order = $this->orderRepository->createOrder($user->id, $totalAmount);

            foreach ($cartItems as $item) {
                $this->orderRepository->createOrderItem(
                    $order->id,
                    $item->product_id,
                    $item->quantity,
                    $item->product->price
                );

                $item->product->decrement('stock', $item->quantity);
            }

            $this->orderRepository->clearUserCart($user->id);

            return $order->load('items.product');
        });
        }
    }
    public function getUserOrders($user)
    {
        return $this->orderRepository->getUserOrders($user->id);
    }
}