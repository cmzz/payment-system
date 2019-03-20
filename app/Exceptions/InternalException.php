<?php
declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalException extends HttpException
{
    public function __construct(
        $message = null,
        $code = ErrorCodes::INTERNAL_ERROR,
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
