<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewRequest;
use App\Models\Review;
use App\Traits\ApiResponse;

class ReviewController extends Controller
{
    use ApiResponse;

    public function store(ReviewRequest $request)
    {
        $userId = $request->user()->id;

        $review = Review::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $request->product_id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return $this->successResponse($review, 'تم إضافة التقييم بنجاح', 201);
    }
}