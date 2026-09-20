<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartRequest;
use App\Models\Cart;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


class CartController extends Controller
{
   use ApiResponse; 

   public function index(Request $request)
    { 
       $cartItems = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        return $this->successResponse($cartItems, 'محتويات السلة بنجاح');
    }

public function store(CartRequest $request)
    {
        $userId = $request->user()->id;

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cartItem = Cart::create([
                'user_id'    => $userId,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return $this->successResponse($cartItem->load('product'), 'تم إضافة المنتج للسلة بنجاح', 201);
    }


    public function update(CartRequest $request, $id)
{
    $cartItem = Cart::where('user_id', $request->user()->id)->find($id);

    if (!$cartItem) {
        return $this->errorResponse('العنصر غير موجود في السلة', 404);
    }

    $cartItem->update([
        'quantity' => $request->quantity
    ]);

    return $this->successResponse($cartItem->load('product'), 'تم تحديث الكمية بنجاح');
}



    public function destroy(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', $request->user()->id)->find($id);

        if (!$cartItem) {
            return $this->errorResponse('العنصر غير موجود في السلة', 404);
        }

        $cartItem->delete();

        return $this->successResponse(null, 'تم حذف العنصر من السلة بنجاح');
    }


}
