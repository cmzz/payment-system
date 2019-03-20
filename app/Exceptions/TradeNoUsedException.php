<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TradeNoUsedException extends HttpException
{
    public function __construct(
        $message = '订单号已存在',
        $code = ErrorCodes::TRADE_NO_USED_ERROR,
        $statusCode = Response::HTTP_BAD_REQUEST,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
