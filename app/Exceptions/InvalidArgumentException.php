<?php
declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidArgumentException extends HttpException
{
    public function __construct(
        $message = '参数错误',
        $code = ErrorCodes::INVALID_ARGUMENT_ERROR,
        $statusCode = Response::HTTP_BAD_REQUEST,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
