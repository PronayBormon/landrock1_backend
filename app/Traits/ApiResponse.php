<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(
        string $message = 'Success',
        $data = null,
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'code' => $status,
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse(
        string $message = 'Error',
        int $status = 400,
        $errors = null
    ): JsonResponse {
        return response()->json([
            'code' => $status,
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    protected function validationErrorResponse($errors): JsonResponse
    {
        return response()->json([
            'code' => 422,
            'success' => false,
            'message' => $errors->first(),
            'errors' => $errors,
        ], 422);
    }
}
