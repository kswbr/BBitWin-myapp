<?php

namespace App\Http\Middleware;

use Closure;
use App;

class CheckIfPlayableProvider
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
        $provider = $request->route("provider");
        $playable_provider = [
          "twitter",
          "line",
        ];
        if (in_array($provider,$playable_provider,true) !== true) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
