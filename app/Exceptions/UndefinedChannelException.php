<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UndefinedChannelException extends HttpException
{
    public function __construct(
        $message = '无法识别的支付渠道',
        $code = ErrorCodes::UNDEFINED_CHANNEL_ERROR,
        $statusCode = Response::HTTP_BAD_REQUEST,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
