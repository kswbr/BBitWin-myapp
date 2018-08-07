<?php

namespace App\Http\Controllers\Web\InstantWin;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Services\LotteryService;
use App\Services\EntryService;
use App\Services\PlayerService;
use App\Services\ProjectService;
use App\User;
use Carbon\Carbon;

class FormController extends Controller
{
    protected $campaignService;
    protected $lotteryService;
    protected $entryService;
    protected $playerService;
    protected $projectService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CampaignService $campaignService,
        LotteryService $lotteryService,
        EntryService $entryService,
        PlayerService $playerService,
        ProjectService $projectService
    )
    {
        $this->campaignService = $campaignService;
        $this->lotteryService = $lotteryService;
        $this->entryService = $entryService;
        $this->playerService = $playerService;
        $this->projectService = $projectService;
    }

    public function init(Request $request )
    {
        $project = $this->projectService->getCode();
        $entry_id = decrypt(\Cookie::get("entry_code"));
        $entry = $this->entryService->getById($entry_id);
        if ($entry->state_code !== "win" && $entry->state_code !== "win_special") {
            abort(403, 'Unauthorized action.');
        }

        $lottery = $this->lotteryService->getByCodeForWinner($entry->lottery_code);
        if (!$lottery) {
            abort(500, 'Lottery Not Found InSession');
        }
        return response(["lottery" => $lottery]);
    }

}
