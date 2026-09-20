<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
use ApiResponse;

    public function index()
    {
        $categories = Category::all();
        return $this->successResponse($categories, 'قائمة الأقسام بنجاح');
    }
}
