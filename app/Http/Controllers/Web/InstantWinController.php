<?php

namespace App\Http\Controllers\Web;

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

class InstantWinController extends Controller
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

    public function run(Request $request, $campaign_code = null, $lottery_code = null)
    {
        $project = $this->projectService->getCode();
        if (!$campaign_code || !$lottery_code) {
            $campaign = $this->campaignService->getFirstInProject($project);
            $lottery = $this->lotteryService->getFirstInCampaign($campaign);
        } else {
            $campaign = $this->campaignService->getByProjectAndCode($project,$campaign_code);
            $lottery = $this->lotteryService->getByCode($lottery_code);
        }
        $user = \Auth::user();
        $player = $user->player;
        $prev_entry = $this->entryService->getPrevDataOfPlayerInCampaign($user->player,$campaign);
        if (isset($prev_entry->id)) {
            $prev_entry_created_at = $prev_entry->created_at;
            $prev_entry_state_code = $prev_entry->state_code;
        } else {
            $prev_entry_created_at = 0;
            $prev_entry_state_code = "init";
        }

        $challenged_today = (string)Carbon::today() <= (string)$prev_entry_created_at;
        $is_retry_challenge = $request->is('*/retry');
        $is_looser = $prev_entry_state_code === "lose" || $prev_entry_state_code === "win_posting_expired";
        $is_winner = $prev_entry_state_code === "win" || $prev_entry_state_code === "win_special";
        $state_list = config("contents.entry.state");
        $result = $lottery->result;

        $user->append('instant_win_token');
        $token = $user->instant_win_token;

        if ($prev_entry_state_code === "win_posting_completed" || $lottery->remaining <= 0) {

            //本日初挑戦の場合
            if (!$challenged_today && !$is_retry_challenge) {
                $user->append('retry_token');
                $token = $user->retry_token;
            }

            // 商品残数が0の場合必ず落選
            // 商品残数が0の場合必ず落選(リトライ)
            // 前回当選して応募完了した人は必ず落選
            // 前回当選して応募完了した人は必ず落選(リトライ)
            return response(["result" => false, "finish" => $is_retry_challenge ,"token" => $token ]);
        }

        if ($is_winner) {
            // 前回当選して未応募の場合必ず当選
            // 前回当選(管理画面にて特別当選扱い)して未応募の場合必ず当選
            $user->append('winner_token');
            $token = $user->winner_token;
            return response(["result" => true, "finish" => true,"token" => $token]);
        }

        if ($challenged_today && $is_looser) {
            if ($is_retry_challenge) {
                $state = ($result === true) ? "win" : "lose";
                $this->entryService->create($player, $lottery, $state);
                if ($state === "win") {
                    // 本日当選してリトライ後当選
                    $user->append('winner_token');
                    $token = $user->winner_token;
                } else {
                    // 本日当選してリトライ後落選
                }
                return response(["result" => $result, "finish" => true,"token" => $token]);
            } else {
                $user->append('retry_token');
                $token = $user->retry_token;
                //本日落選して、リトライせずに再応募した場合落選にする
                return response(["result" => false, "finish" => false,"token" => $token]);
            }
        }

        $state = ($result === true) ? "win" : "lose";
        $this->entryService->create($player, $lottery, $state);

        if ($state === "win") {
            // 初回で当選した場合
            $user->append('winner_token');
            $token = $user->winner_token;
        } else {
            // 初回で落選してリトライの権利を得た場合
            $user->append('retry_token');
            $token = $user->retry_token;
        }

        return response(["result" => $result, "finish" => $state === "win", "token" => $token]);
    }

}
