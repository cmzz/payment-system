<?php
declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentRequestException extends HttpException
{
    public function __construct(
        $message = '发起第三方支付失败',
        $code = ErrorCodes::REQUEST_PAYMENT_PLATFORM_FAILED_ERROR,
        $statusCode = Response::HTTP_BAD_REQUEST,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
