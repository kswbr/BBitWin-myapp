<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\VoteService;
use App\Services\ProjectService;
use App;

class CheckIfVoteBelongsToProject
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
        $voteService = App::make(VoteService::class);
        $projectService = App::make(ProjectService::class);

        $modelName = $voteService->getModelName();

        $vote = ($request->route("vote") instanceof $modelName)? $request->route("vote") : $voteService->getById($request->route("vote"));
        $project = $projectService->getCode();

        if ($vote->project !== $project) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
