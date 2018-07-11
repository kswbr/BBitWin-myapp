<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\LotteryService;
use App\Services\EntryService;
use App;

class CheckIfEntryBelongsToLottery
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
        $entryService = App::make(EntryService::class);
        $lotteryService = App::make(LotteryService::class);

        $lotteryModelName = $lotteryService->getModelName();
        $entryModelName = $entryService->getModelName();

        $lottery = ($request->route("lottery") instanceof $lotteryModelName)? $request->route("lottery") : $lotteryService->getById($request->route("lottery"));
        $entry = ($request->route("entry") instanceof $entryModelName)? $request->route("entry") : $entryService->getById($request->route("entry"));


        if ($lottery->code !== $entry->lottery_code) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
