<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection; 


class OrderRepository
{ 
     
 public function getUserCart(int $userId): Collection
    {
        return Cart::with('product')->where('user_id', $userId)->get();
    }


    public function createOrder(int $userId, float $totalAmount): Order
    {
        return Order::create([
            'user_id'      => $userId,
            'total_amount' => $totalAmount,
            'status'       => 'pending',
        ]);
    }

    public function createOrderItem(int $orderId, int $productId, int $quantity, float $price): OrderItem
    {
        return OrderItem::create([
            'order_id'   => $orderId,
            'product_id' => $productId,
            'quantity'   => $quantity,
            'price'      => $price,
        ]);
    }

    public function clearUserCart(int $userId): void
    {
        Cart::where('user_id', $userId)->delete();
    }


    public function getUserOrders(int $userId): Collection
    {
        return Order::with('items.product')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
}