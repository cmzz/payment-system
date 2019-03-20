<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Logger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('app.debug')) {
            return $next($request);
        }

        // before
        $requestInfo = [
            'path' => sprintf('%s:%s', $request->method(), $request->path()),
            'params' => $request->all(),
            'header' => $request->headers->all(),
        ];

        Log::info('request received', $requestInfo);

        /** @var Response $response */
        $response = $next($request);

        // after
        $statusCode = $response->getStatusCode();
        $responseInfo = [
            'status_code' => $statusCode,
            'body' => $response->content()
        ];

        if ($statusCode >= 400) {
            Log::error('request responded error', $responseInfo);
        } else {
            Log::info('request responded success', $responseInfo);
        }

        return $response;
    }
}
