<?php

namespace App\Core;

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleException(\Throwable $e): void
    {
        if ($e instanceof AppException) {
            http_response_code($e->getStatusCode());
            echo json_encode(['error' => $e->getMessage()]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error']);
            error_log($e);
        }
    }
}
