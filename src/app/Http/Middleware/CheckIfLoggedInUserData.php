<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App;

class CheckIfLoggedInUserData
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

        $logged_in_user = \Auth::user();
        $modelName = User::class;
        $user = ($request->route("user") instanceof $modelName)? $request->route("user") : User::find($request->route("user"));

        if ($logged_in_user->id !== $user->id) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
