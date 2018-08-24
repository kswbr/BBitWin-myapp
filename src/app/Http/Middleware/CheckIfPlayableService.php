<?php

namespace App\Http\Middleware;

use Closure;
use App;

class CheckIfPlayableService
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
        $service = $request->route("service");
        $playable_services = [
          "instantwin"
        ];
        if (in_array($service,$playable_services,true) !== true) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
