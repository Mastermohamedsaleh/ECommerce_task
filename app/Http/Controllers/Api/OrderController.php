<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    use ApiResponse;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(Request $request)
    {
        try {
            $order = $this->orderService->processCheckout($request->user());
            return $this->successResponse($order, 'تم إنشاء الطلب بنجاح', 201);
        } catch (Exception $e) {
            $statusCode = $e->getCode() >= 400 && $e->getCode() <= 500 ? $e->getCode() : 500;
            return $this->errorResponse($e->getMessage(), $statusCode);
        }
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getUserOrders($request->user());
        return $this->successResponse($orders, 'قائمة الطلبات بنجاح');
    }
}
