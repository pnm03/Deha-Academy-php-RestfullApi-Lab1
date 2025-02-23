<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * Danh sách các exception không được report.
     */
    protected $dontReport = [
        //
    ];

    /**
     * Danh sách các input không được đưa vào flash session.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Đăng ký các callback xử lý exception.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render exception thành HTTP response.
     */

    public function render($request, Throwable $e): Response|JsonResponse
    {
        // Kiểm tra nếu URL bắt đầu bằng /api
        if ($request->is('api/*')) {
            // Xử lý lỗi 404
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => [
                        'code' => 404,
                        'message' => 'Resource not found'
                    ]
                ], 404);
            }

            // Xử lý lỗi chung
            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'Server Error'
                ]
            ], 500);
        }

        return parent::render($request, $e);
    }

    protected function handleApiException(Throwable $e): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Resource not found'
                ]
            ], 404);
        }

        return response()->json([
            'error' => [
                'code' => 500,
                'message' => 'Server Error'
            ]
        ], 500);
    }
}