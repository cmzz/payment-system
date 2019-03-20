<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PreOrderFailedException extends HttpException
{
    public function __construct(
        $message = '第三方平台下单失败',
        $code = ErrorCodes::PRE_ORDER_FAILED_ERROR,
        $statusCode = Response::HTTP_BAD_REQUEST,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
