<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductFilterRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
       use ApiResponse;

public function index(ProductFilterRequest $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }


        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->paginate(10);

      

return $this->successResponse([
        'items' => ProductResource::collection($products),
        'pagination' => [
            'total'        => $products->total(),
            'current_page' => $products->currentPage(),
            'per_page'     => $products->perPage(),
            'last_page'    => $products->lastPage(),
        ]
    ], 'قائمة المنتجات بنجاح');
    }


    public function show($id)
    {
        $product = Product::with(['category', 'reviews.user'])->find($id);

        if (!$product) {
            return $this->errorResponse('المنتج غير موجود', 404);
        }

return $this->successResponse(new ProductResource($product), 'تفاصيل المنتج بنجاح');    }


    
}
