<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\CampaignService;
use App\Services\ProjectService;
use App;

class CheckIfCampaignBelongsToProject
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

        $campaignService = App::make(CampaignService::class);
        $projectService = App::make(ProjectService::class);

        $modelName = $campaignService->getModelName();

        $campaign = ($request->route("campaign") instanceof $modelName)? $request->route("campaign") : $campaignService->getById($request->route("campaign"));
        $project = $projectService->getCode();

        if ($campaign->project !== $project) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
