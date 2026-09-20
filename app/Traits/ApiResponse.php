<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    public function errorResponse(string $message = 'Error occurred', int $statusCode = 400, $errors = null)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode);
    }
}