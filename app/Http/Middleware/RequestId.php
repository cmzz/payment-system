<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use Closure;

class RequestId
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('x-request-id')) {
            $request->requestId = $request->header('x-request-id');
        } else {
            $request->requestId = $this->genRequestId();
        }

        return $next($request);
    }

    protected function genRequestId()
    {
        try {
            $id = Uuid::uuid4()->toString();
            return $id;
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
