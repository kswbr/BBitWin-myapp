<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\SerialService;
use App\Services\ProjectService;
use App;

class CheckIfSerialBelongsToProject
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

        $serialService = App::make(SerialService::class);
        $projectService = App::make(ProjectService::class);

        $modelName = $serialService->getModelName();

        $serial = ($request->route("serial") instanceof $modelName)? $request->route("serial") : $serialService->getById($request->route("serial"));
        $project = $projectService->getCode();

        if ($serial->project !== $project) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
