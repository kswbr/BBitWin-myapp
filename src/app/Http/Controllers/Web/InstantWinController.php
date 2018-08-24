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
        if (!$campaign_code) {
            $campaign = $this->campaignService->getFirstInProject($project);
        } else {
            $campaign = $this->campaignService->getByProjectAndCode($project,$campaign_code);
        }

        if (!$lottery_code) {
            $lottery = $this->lotteryService->getFirstInCampaign($campaign);
        } else {
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

        if (!$lottery) {
            abort(404, 'キャンペーンはすでに終了いたしました');
        }

        $challenged_today = (string)Carbon::today() <= (string)$prev_entry_created_at;
        $is_retry_challenge = $request->is('*/retry');
        $is_looser = $prev_entry_state_code === "lose" || $prev_entry_state_code === "win_posting_expired";
        $is_winner = $prev_entry_state_code === "win" || $prev_entry_state_code === "win_special";
        $state_list = config("contents.entry.state");
        $lottery->append('result');
        $result = $lottery->result;

        $user->append('playable_token');
        $token = $user->playable_token;
        $wrong_lottery_choice = false;

        if ($is_winner) {

            // 前回当選して未応募の場合必ず当選
            // 前回当選(管理画面にて特別当選扱い)して未応募の場合必ず当選
            $user->append('winner_token');
            $token = $user->winner_token;
            $winning_lottery = $this->lotteryService->getByCodeForWinner($prev_entry->lottery_code);

            //賞品選択パターンで異なる賞品を選んだ場合は状態を変えずに必ず落選させる
            if ($lottery_code !== null && $prev_entry->lottery_code !== $lottery_code) {
                $wrong_lottery_choice = true;
            } else {
                return response([
                    "result" => true,
                    "finish" => true,
                    "token" => $token,
                    "winning_lottery" => $winning_lottery,
                    "winning_entry_code" => encrypt($prev_entry->id)
                ]);
            }
        }

        if ($prev_entry_state_code === "win_posting_completed" || $lottery->remaining <= 0 || $wrong_lottery_choice === true) {

            //本日初挑戦の場合
            if ( (!$challenged_today || $wrong_lottery_choice) && !$is_retry_challenge) {
                $user->append('retry_token');
                $token = $user->retry_token;
            }

            // 商品残数が0の場合必ず落選
            // 商品残数が0の場合必ず落選(リトライ)
            // 前回当選して応募完了した人は必ず落選
            // 前回当選して応募完了した人は必ず落選(リトライ)
            $this->entryService->create($player, $lottery, "lose");
            return response(["result" => false, "finish" => $is_retry_challenge ,"token" => $token,"winning_lottery" => null, "winning_entry_code" => null]);
        }


        if ($challenged_today && $is_looser) {
            if ($is_retry_challenge) {
                $state = ($result === true) ? "win" : "lose";
                $entry = $this->entryService->create($player, $lottery, $state);
                $entry_code = null;
                $winning_lottery = null;
                if ($state === "win") {
                    // 本日当選してリトライ後当選
                    $user->append('winner_token');
                    $token = $user->winner_token;
                    $winning_lottery = $this->lotteryService->getByCodeForWinner($lottery->code);
                    $entry_code = encrypt($entry->id);
                } else {
                    // 本日当選してリトライ後落選
                }
                return response(["result" => $result, "finish" => true,"token" => $token, "winning_lottery" => $winning_lottery,"winning_entry_code" => $entry_code]);
            } else {
                $user->append('retry_token');
                $token = $user->retry_token;
                //本日落選して、リトライせずに再応募した場合落選にする
                return response(["result" => false, "finish" => false,"token" => $token, "winning_lottery" => null,"winning_entry_code" => null]);
            }
        }

        $state = ($result === true) ? "win" : "lose";
        $entry = $this->entryService->create($player, $lottery, $state);
        $entry_code = null;
        $winning_lottery = null;

        if ($state === "win") {
            // 初回で当選した場合
            $user->append('winner_token');
            $token = $user->winner_token;
            $entry_code = encrypt($entry->id);
            $winning_lottery = $this->lotteryService->getByCodeForWinner($lottery->code);
        } else {
            // 初回で落選してリトライの権利を得た場合
            $user->append('retry_token');
            $token = $user->retry_token;
        }

        return response(["result" => $result, "finish" => $state === "win", "token" => $token, "winning_lottery" => $winning_lottery, "winning_entry_code" => $entry_code]);
    }

    public function winner_regist(Request $request)
    {
        $project = $this->projectService->getCode();
        $entry_id = decrypt($request->input("winning_entry_code"));
        $entry = $this->entryService->getById($entry_id);
        if ($entry->state_code !== "win" && $entry->state_code !== "win_special") {
            abort(403, 'Unauthorized action.');
        }

        $user = \Auth::user();
        $user->append('form_token');
        $token = $user->form_token;
        $form_url = config("contents.form.url.start");

        return response(["token" => $token, "form_url" => $form_url])->cookie(
            'entry_code', $request->input("winning_entry_code"), 24 * 60
        );
    }

}
