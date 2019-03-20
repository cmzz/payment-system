<?php
declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceNotFoundException extends HttpException
{
    public function __construct(
        $message = "资源未找到",
        int $code = ErrorCodes::RECORD_NOT_FOUND,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
