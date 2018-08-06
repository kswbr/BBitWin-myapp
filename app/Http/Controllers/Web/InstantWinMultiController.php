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

class InstantWinMultiController extends Controller
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

    public function run(Request $request, $campaign_code = null)
    {
        $project = $this->projectService->getCode();
        if (!$campaign_code) {
            $campaign = $this->campaignService->getFirstInProject($project);
        } else {
            $campaign = $this->campaignService->getByProjectAndCode($project,$campaign_code);
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
        $results = $this->lotteryService->performInCampaign($campaign);

        $user->append('instant_win_token');
        $token = $user->instant_win_token;


        if ($prev_entry_state_code === "win_posting_completed") {

            //本日初挑戦の場合
            if (!$challenged_today && !$is_retry_challenge) {
                $user->append('retry_token');
                $token = $user->retry_token;
            }

            // 前回当選して応募完了した人は必ず落選
            // 前回当選して応募完了した人は必ず落選(リトライ)
            foreach ($results["lotteries"] as $lottery) {
                $this->entryService->create($player, $lottery, "lose");
            }
            return response(["result" => false, "finish" => $is_retry_challenge ,"token" => $token, "winning_lottery" => null, "winning_entry_code" => null]);
        }

        if ($is_winner) {
            // 前回当選して未応募の場合必ず当選
            // 前回当選(管理画面にて特別当選扱い)して未応募の場合必ず当選
            $user->append('winner_token');
            $token = $user->winner_token;
            $winning_lottery = $this->lotteryService->getByCode($prev_entry->lottery_code);
            return response(["result" => true, "finish" => true,"token" => $token, "winning_lottery" => $winning_lottery, "winning_entry_code" => encrypt($prev_entry->id)]);
        }

        if ($challenged_today && $is_looser) {
            if ($is_retry_challenge) {
                $state = ($results["is_winner"] === true) ? "win" : "lose";
                foreach ($results["losed_lotteries"] as $lottery) {
                    $this->entryService->create($player, $lottery, "lose");
                }

                $entry_code = null;
                if ($state === "win") {
                    // 本日落選してリトライ後当選
                    $user->append('winner_token');
                    $token = $user->winner_token;
                    $entry = $this->entryService->create($player, $results["winning_lottery"], "win");
                    $entry_code = encrypt($entry->id);
                } else {
                    // 本日落選してリトライ後落選
                }
                return response([
                  "result" => $results["is_winner"],
                  "finish" => true,
                  "token" => $token,
                  "winning_lottery" => isset($results["winning_lottery"]->code) ? $results["winning_lottery"] : null,
                  "winning_entry_code" => $entry_code
                ]);
            } else {
                $user->append('retry_token');
                $token = $user->retry_token;
                //本日落選して、リトライせずに再応募した場合落選にする
                return response(["result" => false, "finish" => false,"token" => $token, "winning_lottery" => null, "winning_entry_code" => null]);
            }
        }

        $state = ($results["is_winner"] === true) ? "win" : "lose";
        foreach ($results["losed_lotteries"] as $lottery) {
            $this->entryService->create($player, $lottery, "lose");
        }

        $entry_code = null;
        if ($state === "win") {
            // 初回で当選した場合
            $user->append('winner_token');
            $token = $user->winner_token;
            $entry = $this->entryService->create($player, $results["winning_lottery"], "win");
            $entry_code = encrypt($entry->id);
        } else if (!$is_retry_challenge){
            // 初回で落選してリトライの権利を得た場合
            $user->append('retry_token');
            $token = $user->retry_token;
        }

        return response([
          "result" => $results["is_winner"],
          "finish" => $results["is_winner"] || $is_retry_challenge,
          "token" => $token,
          "winning_lottery" => $results["winning_lottery"] ? $results["winning_lottery"] : null,
          "winning_entry_code" => $entry_code
        ]);

    }

}
