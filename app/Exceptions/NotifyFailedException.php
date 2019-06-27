<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotifyFailedException extends HttpException
{
    public function __construct(
        $message = '异步通知失败',
        $code = ErrorCodes::NOTIFY_FAILED_ERROR,
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}