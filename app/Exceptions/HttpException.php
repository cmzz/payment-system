<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class HttpException extends Exception
{
    private $statusCode;
    private $headers;

    public function __construct(
        string $message = null,
        int $code = 0,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        \Exception $previous = null,
        array $headers = array()
    ) {
        parent::__construct($message, $code, $previous);

        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
