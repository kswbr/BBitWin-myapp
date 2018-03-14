<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\CampaignService;
// use App\Services\LotteryService;
use App;

class CheckIfLotteryBelongsToCampaign
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
        // $lotteryService = App::make(LotteryService::class);

        $campaignModelName = $campaignService->getModelName();
        // $lotteryModelName = "TODO";

        $campaign = ($request->route("campaign") instanceof $campaignModelName)? $request->route("campaign") : $campaignService->getById($request->route("campaign"));
        // $lottery = ($request->route("lottery") instanceof $lotteryModelName)? $request->route("lottery") : $lotteryService->getById($request->route("lottery"));

        // if ($lottery->campaign->id !== $campaign->id) {
        //     abort(403, 'Unauthorized action');
        // }
        return $next($request);
    }
}
