<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\InvalidHeaderException;
use App\Keys;
use App\Models\App;
use Closure;

class AppInfo
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
        if (!$appKey = $request->header('x-app-key', null)) {
            throw new InvalidHeaderException();
        }

        $app = App::where(App::APP_KEY, $appKey)->firstOrFail();

        session([Keys::SES_APP_ID => $app->id]);
        session([Keys::SES_APP => $app]);

        return $next($request);
    }
}
