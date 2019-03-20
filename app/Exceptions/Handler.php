<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        Log::error(
            'ExceptionHandler',
            [
                'url' => $request->fullUrl(),
                'exception' => (string)$exception,
                'request' => (array)$request->getContent()
            ]
        );

        $message = $exception->getMessage();
        $code = $exception->getCode() > 0 ? $exception->getCode() : ErrorCodes::INTERNAL_ERROR;
        $statusCode = $this->getStatusCode($exception);

        if ($exception instanceof ValidationException) {
            $statusCode = 400;
            $code = ErrorCodes::INVALID_ARGUMENT_ERROR;
            $message = sprintf(
                'The given data was invalid. (%s)',
                implode(' ', array_keys($exception->errors()))
            );
        } elseif ($exception instanceof ModelNotFoundException) {
            $statusCode = 500;
            $code = ErrorCodes::RECORD_NOT_FOUND;
            $message = 'No query results';
        } else {
            switch ($statusCode = $this->getStatusCode($exception)) {
                case 404:
                    $message = 'Not found';
            }
        }

        $data = [
            'request_id' => request()->requestId,
            'code' => $code,
            'status_code' => $orgStatusCode ?? $statusCode,
            'message' => $message,
        ];

        return response()->json($data, $statusCode);
    }

    protected function getStatusCode(Exception $exception)
    {
        return $exception instanceof HttpExceptionInterface ?
            $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
