<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Lỗi API / từ xa: message + HTTP status (dùng chung cho middleware, repository…).
 */
class RemoteException extends HttpException
{
    public function __construct(
        string $message = '',
        int $statusCode = 500,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
